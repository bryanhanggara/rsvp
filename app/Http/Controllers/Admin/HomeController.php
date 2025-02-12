<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
         // Ambil semua periode yang tersedia dari Event
        $availablePeriods = Event::select('priode')->distinct()->pluck('priode');

        // Periode dan bulan dipilih user
        $periode = $request->periode ?? $availablePeriods->first();
        $bulan = $request->bulan;

        // Ambil data user dan total poin dari RSVP
        $users = User::with(['approvedRsvps' => function ($query) use ($periode, $bulan) {
            $query->whereHas('event', function ($query) use ($periode) {
                    $query->where('priode', $periode);
                })
                ->when($bulan, function ($query) use ($bulan) {
                    $query->whereMonth('created_at', $bulan);
                })
                ->with('event')
                ->get();
        }])->get();

        // Hitung total poin per user
        $users->map(function ($user) {
            $user->total_point = $user->approvedRsvps->sum(function ($rsvp) {
                return $rsvp->event->point;
            });
            return $user;
        });

        return view('pages.admin.all_users_points', compact('users', 'availablePeriods', 'periode', 'bulan'));
    }


}
