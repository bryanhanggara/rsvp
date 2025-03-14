<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AkumulasiController extends Controller
{
    public function totalPointEventPerBulan()
    {
        // Menghitung total point dari event per bulan
        $pointsPerMonth = Event::selectRaw('
            MONTH(created_at) as bulan, 
            YEAR(created_at) as tahun, 
            SUM(point) as total_point,
            SUM(point) * 0.5 as minimum_point
        ')
        ->groupBy('bulan', 'tahun')
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc')
        ->get();

        // Nama bulan untuk ditampilkan di view
        $month = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('pages.admin.event.accumulation', compact('pointsPerMonth', 'month'));
    }
}
