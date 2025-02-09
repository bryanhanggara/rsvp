<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rsvp;
use App\Models\Event;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $event = Event::find($request->event_id);
        $user = Auth::user();

        $imagePath = $request->file('image')->store('rsvp_images', 'public');

        // mengecek apakah user sudah RSVP ke acara ini
        if (Rsvp::where('user_id', $user->id)->where('event_id', $event->id)->exists()) {
            Alert::error('Ups!', 'Kamu sudah pernah RSVP ke acara ini');
            return redirect()->back();
        }

        // kalau ga ada buat rsvp 
        Rsvp::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'image' => $imagePath,
            'status' => 'PENDING',
        ]);

        //mengecek user dah ada user_point di priode acara ini 
        $userPoint = UserPoint::where('user_id', $user->id)
                          ->where('periode', $event->periode)
                          ->first();

        if ($userPoint) {
        // Jika user_point sudah ada di periode ini, update point
        $userPoint->increment('point', $event->point);
        } else {
            // Jika periode baru, buat user_point baru
            UserPoint::create([
                'user_id' => $user->id,
                'periode' => $event->periode,
                'point' => $event->point,
            ]);
        }

        Alert::success('Yeay', 'Berhasil rsvp acara ini, Mohon tunggu verifikasi internal ya');
        return redirect()->back();
    }
}
