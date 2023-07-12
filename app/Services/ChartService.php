<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChartService
{
    public $start_date;
    public $end_date;
    public $query;
    
    private $data = [];
    private $label = [];

    public function hourlyData()
    {
        $data = $this->query->select(DB::raw("COUNT(*) as count"), DB::raw("HOUR(created_at) as hour"));
        
        $data = $data->groupBy('hour')
        ->orderBy('hour')
        ->get();

        while ($this->start_date <= $this->end_date) {
            $this->data[] = $data->where('hour', $this->start_date->hour)->pluck('count')->first() ?? 0;
            $from = $this->start_date;
            $this->label[] = $from->setTimezone('Asia/Dhaka')->format('h:i A d, M');
            $this->start_date->addHour();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Hours'];
    }


    public function dailyData()
    {
        $data = $this->query->select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"));

        $data = $data->groupBy('date')
        ->orderBy('date')
        ->get();

        while ($this->start_date <= $this->end_date) {
            $this->data[] = $data->where('date', $this->start_date->format('Y-m-d'))->pluck('count')->first() ?? 0;
            $from = $this->start_date;
            $this->label[] = $from->setTimezone('Asia/Dhaka')->format('d M, y');
            $this->start_date->addDay();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Days'];
    }


    public function weeklyData()
    {
        $data = $this->query->select(DB::raw("COUNT(*) as count"), DB::raw("WEEK(created_at) as week"), DB::raw("YEAR(created_at) as year"));

        if($this->start_date){
            $data->whereDate('created_at', '>=', $this->start_date);
        }

        if($this->end_date){
            $data->whereDate('created_at', '<=', $this->end_date);
        }

        $data = $data->groupBy(DB::raw("WEEK(created_at)"), DB::raw("YEAR(created_at)"))
        ->orderBy(DB::raw("YEAR(created_at)"))
        ->orderBy(DB::raw("WEEK(created_at)"))
        ->get();

        while ($this->start_date->format('Y-m-d') <= $this->end_date->format('Y-m-d')) {
            $this->data[] = $data->where('week', $this->start_date->weekOfYear)
            ->where('year', $this->start_date->year)
            ->pluck('count')
            ->first() ?? 0;
            $from = $this->start_date;
            $this->label[] = $from->setTimezone('Asia/Dhaka')->format('d M, y');
            $this->start_date->addWeek();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Weeks'];
    }


    public function monthlyData() 
    {
        $data = $this->query->select(DB::raw("MONTH(created_at) AS month"), DB::raw("YEAR(created_at) AS year"), DB::raw('COUNT(*) AS count'));

        if($this->start_date){
            $data->whereDate('created_at', '>=', $this->start_date);
        }

        if($this->end_date){
            $data->whereDate('created_at', '<=', $this->end_date);
        }

        $data = $data->groupBy(DB::raw("MONTH(created_at)"), DB::raw("YEAR(created_at)"))
        ->groupBy('month', 'year')
        ->orderBy('month')
        ->orderBy('year')
        ->get();

        while ($this->start_date->format('Y-m') <= $this->end_date->format('Y-m')) {
            $this->data[] = $data->where('year', $this->start_date->year)
            ->where('month', $this->start_date->month)
            ->pluck('count')
            ->first() ?? 0;

            $from = $this->start_date;
            $this->label[] = $from->setTimezone('Asia/Dhaka')->format('M, y');
            $this->start_date->addMonth();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Months'];
    }
}
