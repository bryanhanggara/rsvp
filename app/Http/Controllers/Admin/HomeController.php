<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\UserPointHistory;
use App\Exports\UserPointsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function index()
    {
        $users_count = User::get()->count();
        $events_count = Event::get()->count();

        $totalEvents = Event::countEventsInCurrentPeriod();

        return view('pages.admin.dashboard', compact('users_count', 'events_count', 'totalEvents'));
    }

    public function rankingBeswan(Request $request)
    {
        $availablePeriods = Event::pluck('priode')->unique()->sort();
        
        // Ambil periode dari request, kalau kosong pakai periode saat ini
        $currentPeriod = Event::getCurrentPeriod();
        $selectedPeriod = $request->periode ?? $currentPeriod;

        $ranking = User::with([
                            'points' => function ($query) use ($selectedPeriod) {
                                $query->where('periode', $selectedPeriod);
                            },
                            'rsvp' => function ($query) use ($selectedPeriod) {
                                $query->whereHas('event', function ($q) use ($selectedPeriod) {
                                    $q->where('priode', $selectedPeriod);
                                });
                            }
                        ])
                        ->withSum(['points as total_points' => function ($query) use ($selectedPeriod) {
                            $query->where('periode', $selectedPeriod);
                        }], 'point') // Menghitung total poin user
                        ->withCount(['rsvp as total_rsvp' => function ($query) use ($selectedPeriod) {
                            $query->whereHas('event', function ($q) use ($selectedPeriod) {
                                $q->where('priode', $selectedPeriod);
                            });
                        }]) // Menghitung jumlah RSVP user
                        ->orderByDesc('total_points')
                        ->get();

        return view('pages.admin.ranking', compact('ranking', 'selectedPeriod','availablePeriods'));
    }

    public function pointsByMonth(Request $request)
    {
        // Ambil bulan dan tahun dari request (default bulan dan tahun sekarang)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Dapatkan data total poin per bulan untuk semua user
        $userPoints = UserPointHistory::getTotalPointsByMonthForAllUsers($month, $year);

        // Dapatkan akumulasi point dari event per bulan
        $eventPoints = Event::getEventPointsByMonth($month, $year);

        return view('pages.admin.all_users_points', compact('userPoints', 'month', 'year', 'eventPoints'));
    }

    public function exportPoints(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $fileName = 'user_points_' . $month . '_' . $year . '.xlsx';
        return Excel::download(new UserPointsExport($month, $year), $fileName);
    }

}
