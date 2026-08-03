<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\User;
use App\Models\Rsvp;
use App\Models\UserPointHistory;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class UserPointsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $month;
    protected $year;
    protected $events;
    protected $minimumPoint;

    public function __construct($month, $year)
    {
        // Pastikan month adalah integer
        $this->month = (int) $month;
        $this->year = (int) $year;
        
        // Ambil semua event di bulan/tahun yang dipilih
        // Tampilkan semua event yang ada, bahkan yang belum ada RSVP-nya
        $this->events = Event::whereMonth('date', $this->month)
                            ->whereYear('date', $this->year)
                            ->orderBy('date')
                            ->get();
        
        // Hitung minimum point dari event dengan category 'akumulasi' di bulan tersebut (total point / 2)
        $eventPoints = Event::getEventPointsByMonth($this->month, $this->year);
        $this->minimumPoint = $eventPoints ? $eventPoints->minimum_point : 0;
        
        // Debug: uncomment untuk cek event yang diambil
        // \Log::info('Events found for month ' . $this->month . '/' . $this->year . ': ' . $this->events->count());
    }

    public function collection()
    {
        // Ambil semua user yang memiliki RSVP APPROVED/ABSENT untuk event di bulan ini
        // Tampilkan semua user yang mungkin belum punya RSVP untuk beberapa event
        $userIds = \DB::table('rsvps')
            ->join('events', 'rsvps.event_id', '=', 'events.id')
            ->whereIn('rsvps.status', ['APPROVED', 'ABSENT'])
            ->whereMonth('events.date', $this->month)
            ->whereYear('events.date', $this->year)
            ->pluck('rsvps.user_id')
            ->unique();

        // Jika tidak ada user, tetap ambil semua user untuk menampilkan kolom event (meski 0)
        if ($userIds->isEmpty()) {
            // Jika tidak ada event sama sekali, return empty
            if ($this->events->isEmpty()) {
                return collect([]);
            }
            // Ambil semua user untuk menampilkan struktur tabel
            $users = User::all()->keyBy('id');
        } else {
            $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        }

        // Query semua poin per user per event dalam satu query
        $eventIds = $this->events->pluck('id');
        
        $pointsData = \DB::table('rsvps')
            ->join('events', 'rsvps.event_id', '=', 'events.id')
            ->join('user_point_histories', function($join) {
                $join->on('user_point_histories.user_id', '=', 'rsvps.user_id')
                     ->whereRaw("user_point_histories.description LIKE CONCAT('%', events.name, '%')");
            })
            ->whereIn('rsvps.user_id', $userIds)
            ->whereIn('rsvps.event_id', $eventIds)
            ->whereIn('rsvps.status', ['APPROVED', 'ABSENT'])
            ->select(
                'rsvps.user_id',
                'rsvps.event_id',
                \DB::raw('SUM(user_point_histories.point) as points')
            )
            ->groupBy('rsvps.user_id', 'rsvps.event_id')
            ->get()
            ->groupBy('user_id');

        // Buat collection dengan data per user per event
        return $users->map(function($user) use ($pointsData) {
            $userData = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'events' => []
            ];

            $totalPoints = 0;
            $userPoints = $pointsData->get($user->id, collect([]));

            // Hitung poin per event
            foreach ($this->events as $event) {
                $pointRecord = $userPoints->firstWhere('event_id', $event->id);
                $points = $pointRecord ? $pointRecord->points : 0;
                
                $userData['events'][$event->id] = $points;
                $totalPoints += $points;
            }

            $userData['total_points'] = $totalPoints;
            return (object) $userData;
        })->values();
    }

    public function headings(): array
    {
        $headings = ['Nama User'];
        
        // Tambahkan nama event sebagai kolom
        foreach ($this->events as $event) {
            $headings[] = $event->name;
        }
        
        // Tambahkan kolom total, minimum point, dan status
        $headings[] = 'Total Points';
        $headings[] = 'Minimum Point';
        $headings[] = 'Status';
        
        return $headings;
    }

    public function map($userData): array
    {
        $row = [$userData->user_name];
        
        // Tambahkan poin per event
        foreach ($this->events as $event) {
            $row[] = $userData->events[$event->id] ?? 0;
        }
        
        // Tambahkan total, minimum point, dan status
        $row[] = $userData->total_points;
        $row[] = $this->minimumPoint;
        
        // Hitung status: Cukup jika total_points >= minimumPoint, Kurang jika sebaliknya
        $status = $userData->total_points >= $this->minimumPoint ? 'Cukup' : 'Kurang';
        $row[] = $status;
        
        return $row;
    }

}
