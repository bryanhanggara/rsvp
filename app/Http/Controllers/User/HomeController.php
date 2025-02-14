<?php

namespace App\Http\Controllers\User;

use App\Models\Rsvp;
use App\Models\Event;
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
}
