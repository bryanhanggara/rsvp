<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\UserPointHistory;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserPointsExport implements FromCollection,WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return UserPointHistory::getTotalPointsByMonthForAllUsers($this->month, $this->year);
    }

    public function headings(): array
    {
        return [
            'User',
            'Total Points',
            'Minimum Points',
            'Status'
        ];
    }

    public function map($point): array
    {
        $eventPoints = Event::getEventPointsByMonth($this->month, $this->year);
        $minimumPoint = $eventPoints->minimum_point ?? 0;
        $status = $point->total_points < $minimumPoint ? 'Kurang' : 'Cukup';

        return [
            $point->user->name,
            $point->total_points,
            $minimumPoint,
            $status
        ];
    }

}
