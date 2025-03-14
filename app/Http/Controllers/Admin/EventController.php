<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rsvp;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $availablePeriods = Event::pluck('priode')->unique()->sort();
        
        // Ambil periode dari request, kalau kosong pakai periode saat ini
        $currentPeriod = Event::getCurrentPeriod();
        $selectedPeriod = $request->priode ?? $currentPeriod;

        $events = Event::where('priode', $selectedPeriod)->get();

        return view('pages.admin.event.index', compact('events', 'availablePeriods', 'selectedPeriod'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
            'point' => 'required',
            'priode' => 'required',
        ]);

        $periode = Event::getCurrentPeriod();

         Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'point' => $request->point,
            'priode' => $periode, // Otomatis ambil periode
        ]);

        Alert::success('Yeay', 'Acara Sudah Dibuat');
        return redirect()->route('event.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findorfail($id);
        $rsvps = $event->rsvp;

        $allUsers = User::all();

        $usersWhoRSVP = $rsvps->pluck('user_id')->toArray();

        $usersWithoutRSVP = $allUsers->whereNotIn('id', $usersWhoRSVP);

        return view('pages.admin.event.show', compact('event', 'rsvps', 'usersWithoutRSVP'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findorfail($id);
        $event->delete();

        Alert::success('Yeay', 'Acara sudah dihapus');
        return redirect()->back();
    }
}
