<?php

namespace App\Http\Controllers\User;

use App\Models\Rsvp;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
       // Ambil bulan dan tahun dari request, default ke bulan dan tahun saat ini
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Filter event berdasarkan bulan dan tahun
        $events = Event::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->latest()
                    ->get();

        $userId = Auth::user()->id;
        $rsvp_count = Rsvp::where('user_id', $userId)->count(); 

        return view('pages.users.dashboard', compact('events', 'rsvp_count', 'month', 'year'));
    }

    public function show($id)
    {
        $event = Event::findorfail($id);
        return view('pages.users.acara.index', compact('event'));
    }

    public function historyRsvp()
    {
        $userId = Auth::user()->id;

        $rsvps = Rsvp::where('user_id', $userId)
                        ->whereHas('event')
                        ->with('event')
                        ->get()
                        ->groupBy('event.priode');
        
        return view('pages.users.history_rsvp', compact('rsvps'));                
    }

    public function leaderboardPeriode(Request $request)
    {
        $availablePeriods = Event::pluck('priode')->unique()->sort();
        $currentPeriod = Event::getCurrentPeriod();
        $selectedPeriod = $request->periode ?? $currentPeriod;

        $ranking = User::with(['rsvp' => function ($query) use ($selectedPeriod) {
                                $query->where('status', 'APPROVED')
                                      ->whereHas('event', function ($q) use ($selectedPeriod) {
                                          $q->where('priode', $selectedPeriod);
                                      })
                                      ->with('event');
                            }])
                            ->get()
                            ->map(function ($user) {
                                $user->total_points = $user->rsvp->sum(function ($rsvp) {
                                    return (float) ($rsvp->event->point ?? 0);
                                });
                                return $user;
                            })
                            ->sortByDesc('total_points')
                            ->values();

        return view('pages.users.leaderboard_periode', compact('ranking', 'selectedPeriod', 'availablePeriods'));
    }

    public function leaderboardMonth(Request $request)
    {
        $month = (int) $request->input('month', date('m'));
        $year = (int) $request->input('year', date('Y'));

        $users = User::whereHas('rsvp', function ($query) use ($month, $year) {
                            $query->where('status', 'APPROVED')
                                  ->whereHas('event', function ($q) use ($month, $year) {
                                      $q->whereMonth('date', $month)
                                        ->whereYear('date', $year);
                                  });
                        })
                        ->with(['rsvp' => function ($query) use ($month, $year) {
                            $query->where('status', 'APPROVED')
                                  ->whereHas('event', function ($q) use ($month, $year) {
                                      $q->whereMonth('date', $month)
                                        ->whereYear('date', $year);
                                  })
                                  ->with('event');
                        }])
                        ->get();

        $ranking = $users->map(function ($user) {
                            $user->total_points = $user->rsvp->sum(function ($rsvp) {
                                return (float) ($rsvp->event->point ?? 0);
                            });
                            return $user;
                        })
                        ->sortByDesc('total_points')
                        ->values();

        return view('pages.users.leaderboard_month', compact('ranking', 'month', 'year'));
    }
}
