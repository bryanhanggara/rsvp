<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPointHistory extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 
        'user_point_id', 
        'point', 
        'description', 
        'created_at'
    ];
    
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke UserPoint
    public function userPoint()
    {
        return $this->belongsTo(UserPoint::class);
    }

    public static function getTotalPointsByMonth($userId, $month = null, $year = null)
    {
        $query = self::where('user_id', $userId);

        // Filter berdasarkan bulan dan tahun jika ada
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        return $query->sum('point');
    }

    public static function getTotalPointsByMonthForAllUsers($month = null, $year = null)
    {
        $query = self::query();

        // Filter berdasarkan bulan dan tahun jika ada
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        if ($year) {
            $query->whereYear('created_at', $year);
        }

        // Grouping berdasarkan user dan bulan
        return $query->selectRaw('user_id, SUM(point) as total_points, DATE_FORMAT(created_at, "%Y-%m") as month')
                    ->groupBy('user_id', 'month')
                    ->orderBy('month', 'desc')
                    ->get();
    }


}
