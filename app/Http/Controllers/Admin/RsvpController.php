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
            'image' => 'required|image|mimes:jpg,png,jpeg|max:10240',
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

        // //mengecek user dah ada user_point di priode acara ini 
        // $userPoint = UserPoint::where('user_id', $user->id)
        //                   ->where('periode', $event->priode)
        //                   ->first();

        // if ($userPoint) {
        // // Jika user_point sudah ada di periode ini, update point
        // $userPoint->increment('point', $event->point);
        // } else {
        //     // Jika periode baru, buat user_point baru
        //     UserPoint::create([
        //         'user_id' => $user->id,
        //         'periode' => $event->priode,
        //         'point' => $event->point,
        //     ]);
        // }

        Alert::success('Yeay', 'Berhasil rsvp acara ini, Mohon tunggu verifikasi internal ya');
        return redirect()->back();
    }

    public function updateStatus(Request $request, $id)
    {
        $rsvp = Rsvp::findOrFail($id);
        $event = $rsvp->event;
        $user = $rsvp->user;

        $rsvp->update(['status' => $request->status]);

         // Jika status diubah menjadi APPROVED, tambahkan poin ke UserPoint
        if ($request->status === 'APPROVED') {
            $userPoint = UserPoint::where('user_id', $user->id)
                                ->where('periode', $event->priode)
                                ->first();

            if ($userPoint) {
                // Update poin jika sudah ada
                $userPoint->increment('point', $event->point);
            } else {
                // Buat record baru jika belum ada
                UserPoint::create([
                    'user_id' => $user->id,
                    'periode' => $event->priode,
                    'point' => $event->point,
                ]);
            }
        }

        Alert::success('Berhasil', 'Status RSVP berhasil diperbarui');
        return redirect()->back();
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'rsvp_ids' => 'required|array',
            'rsvp_ids.*' => 'exists:rsvps,id',
            'status' => 'required|in:PENDING,APPROVED,REJECTED',
        ]);

        $rsvps = Rsvp::whereIn('id', $request->rsvp_ids)->get();

        foreach ($rsvps as $rsvp) {
            $rsvp->update(['status' => $request->status]);

            // Jika status berubah menjadi APPROVED, tambahkan poin ke UserPoint
            if ($request->status === 'APPROVED') {
                $event = $rsvp->event;
                $user = $rsvp->user;

                $userPoint = UserPoint::where('user_id', $user->id)
                                    ->where('periode', $event->priode)
                                    ->first();

                if ($userPoint) {
                    // Update poin jika sudah ada
                    $userPoint->increment('point', $event->point);
                } else {
                    // Buat record baru jika belum ada
                    UserPoint::create([
                        'user_id' => $user->id,
                        'periode' => $event->priode,
                        'point' => $event->point,
                    ]);
                }
            }
        }

        Alert::success('Berhasil', 'Status RSVP berhasil diperbarui untuk beberapa pengguna');
        return redirect()->back();
    }

}
