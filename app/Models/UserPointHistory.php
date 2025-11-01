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
        // Query dari RSVP yang disetujui untuk event di bulan/tahun yang dipilih
        // Kemudian ambil history poin dari UserPointHistory yang terkait
        
        $query = \DB::table('rsvps')
            ->join('events', 'rsvps.event_id', '=', 'events.id')
            ->join('user_point_histories', function($join) {
                $join->on('user_point_histories.user_id', '=', 'rsvps.user_id')
                     ->whereRaw("user_point_histories.description LIKE CONCAT('%', events.name, '%')");
            })
            ->whereIn('rsvps.status', ['APPROVED', 'ABSENT']); // Include both approved and absent

        // Filter berdasarkan bulan dan tahun event, bukan tanggal history dibuat
        if ($month) {
            $query->whereMonth('events.date', $month);
        }
        if ($year) {
            $query->whereYear('events.date', $year);
        }

        // Grouping berdasarkan user
        $results = $query->selectRaw('user_point_histories.user_id, SUM(user_point_histories.point) as total_points')
                    ->groupBy('user_point_histories.user_id')
                    ->get();

        // Eager load users untuk performa lebih baik
        $userIds = $results->pluck('user_id')->unique();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        // Convert to collection of model instances with user relationship
        $histories = collect();
        foreach ($results as $result) {
            $history = new self();
            $history->user_id = $result->user_id;
            $history->total_points = $result->total_points;
            $history->setRelation('user', $users->get($result->user_id));
            $histories->push($history);
        }

        return $histories;
    }


}
