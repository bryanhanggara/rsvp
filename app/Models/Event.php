<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    public $increamenting = false;

    protected $keyType = 'string';

    protected $fillable = [
      'name',
      'description',
      'date',
      'point',
      'priode',
      'category'
    ];

    public function rsvp()
    {
        return $this->hasMany(Rsvp::class, 'event_id');
    }

    public static function getCurrentPeriod()
    {
        $month = now()->month;
        $year = now()->year;

        if ($month >= 1 && $month <= 2) return "Januari - Februari $year";
        if ($month >= 3 && $month <= 4) return "Maret - April $year";
        if ($month >= 5 && $month <= 6) return "Mei - Juni $year";
        if ($month >= 7 && $month <= 8) return "Juli - Agustus $year";
        if ($month >= 9 && $month <= 10) return "September - Oktober $year";
        return "November - Desember $year";
    }

    public static function countEventsInCurrentPeriod()
    {
        $currentPeriod = self::getCurrentPeriod();

        return self::where('priode', $currentPeriod)->count();
    }

    public static function getPeriodForDate(string $dateString)
    {
        $timestamp = strtotime($dateString);
        $month = (int) date('n', $timestamp);
        $year = (int) date('Y', $timestamp);

        if ($month >= 1 && $month <= 2) return "Januari - Februari $year";
        if ($month >= 3 && $month <= 4) return "Maret - April $year";
        if ($month >= 5 && $month <= 6) return "Mei - Juni $year";
        if ($month >= 7 && $month <= 8) return "Juli - Agustus $year";
        if ($month >= 9 && $month <= 10) return "September - Oktober $year";
        return "November - Desember $year";
    }

    public static function getEventPointsByMonth($month = null, $year = null)
    {
        $query = self::query()
                ->where('category', 'akumulasi'); 

        // Filter berdasarkan bulan dan tahun event date, bukan tanggal event dibuat
        if ($month) {
            $query->whereMonth('date', $month);
        }
        if ($year) {
            $query->whereYear('date', $year);
        }

        // Grouping berdasarkan bulan dan tahun
        return $query->selectRaw('
                MONTH(date) as bulan,
                YEAR(date) as tahun,
                SUM(point) as total_point,
                SUM(point) * 0.5 as minimum_point
            ')
            ->groupBy('bulan', 'tahun')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->first();
    }


}
