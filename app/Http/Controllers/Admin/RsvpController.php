<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Rsvp;
use App\Models\User;
use App\Models\Event;
use App\Models\UserPoint;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserPointHistory;
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
        $priode = $event->priode;

        // Update status RSVP
        $rsvp->update(['status' => $request->status]);

        // Ambil atau buat UserPoint berdasarkan priode
        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $user->id, 'periode' => $priode],
            ['point' => 0]
        );

        // Jika status diubah menjadi APPROVED, tambahkan poin
        if ($request->status === 'APPROVED') {
        
            $userPoint->increment('point', $event->point);

            // Buat catatan di UserPointHistory
            UserPointHistory::create([
                'user_id' => $user->id,
                'user_point_id' => $userPoint->id,
                'point' => $event->point,
                'description' => "Menghadiri event: " . $event->name
            ]);
        }

        // Jika status diubah menjadi absent, kurangi point
        elseif ($request->status === 'ABSENT') {
            // Kurangi point dari UserPoint
            $userPoint->decrement('point', $event->point/2);

            // Buat catatan di UserPointHistory
            UserPointHistory::create([
                'user_id' => $user->id,
                'user_point_id' => $userPoint->id,
                'point' => -$event->point/2,
                'description' => "Tidak hadir di event: " . $event->name
            ]);
        }

        Alert::success('Berhasil', 'Status RSVP berhasil diperbarui');
        return redirect()->back();
    }

    public function bulkUpdateStatus(Request $request)
    {
        $rsvpIds = $request->rsvp_ids;
        $newStatus = $request->status;

        // Ambil semua RSVP yang dipilih
        $rsvps = Rsvp::whereIn('id', $rsvpIds)->get();

        // Cek apakah ada yang sudah memiliki status yang sama
        foreach ($rsvps as $rsvp) {
            if ($rsvp->status === $newStatus) {
                Alert::error('Gagal', 'Salah satu peserta sudah memiliki status ' . $newStatus . '. Ulangi lagi.');
                return redirect()->back();
            }
        }

        // Siapkan array untuk batch insert ke UserPointHistory
        $histories = [];

        foreach ($rsvps as $rsvp) {
            $event = $rsvp->event;
            $user = $rsvp->user;
            $priode = $event->priode;

            // Update status RSVP
            $rsvp->update(['status' => $newStatus]);

            // Ambil atau buat UserPoint berdasarkan priode
            $userPoint = UserPoint::firstOrCreate(
                ['user_id' => $user->id, 'periode' => $priode],
                ['point' => 0]
            );

            // Jika status diubah menjadi APPROVED, tambahkan poin
            if ($newStatus === 'APPROVED') {
                $userPoint->increment('point', $event->point);

                $histories[] = [
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'user_point_id' => $userPoint->id,
                    'point' => $event->point,
                    'description' => "Menghadiri event: " . $event->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Jika status diubah menjadi Absent atau tidak hadir, kurangi point
            elseif ($newStatus === 'ABSENT') {
                $userPoint->decrement('point', $event->point/2);

                $histories[] = [
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'user_point_id' => $userPoint->id,
                    'point' => -$event->point/2,
                    'description' => "Tidak hadir di event: " . $event->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert semua history point secara bulk
        UserPointHistory::insert($histories);

        Alert::success('Berhasil', 'Status RSVP berhasil diperbarui secara massal');
        return redirect()->back();
    }

    public function deductPointsForNonRsvp($eventId, $userId)
    {
        $event = Event::findOrFail($eventId);
        $user = User::findOrFail($userId);

        // Cek apakah UserPoint tersedia
        $userPoint = UserPoint::where('user_id', $user->id)
                            ->where('periode', $event->priode)
                            ->first();

        if (!$userPoint) {
            Alert::error('Oops!', 'User tidak memiliki poin di periode ini.');
            return redirect()->back();
        }

        // Hitung pengurangan point tanpa menjadi negatif
        $newPoint = max($userPoint->point - $event->point, 0);

        // Update poin di UserPoint
        $userPoint->update([
            'point' => $newPoint
        ]);

        // Catat riwayat pengurangan di UserPointHistory
        UserPointHistory::create([
            'user_id' => $user->id,
            'user_point_id' => $userPoint->id,
            'point' => -$event->point, // Gunakan nilai negatif untuk pengurangan
            'periode' => $event->priode,
            'description' => 'Tidak konfirmasi hadir di event ' . $event->name
        ]);

        Alert::success('Yeay', 'Pengurangan Point Berhasil Dilakukan');
        return redirect()->back();
    }

}
