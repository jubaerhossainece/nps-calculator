<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChartService
{
    public $start_date;
    public $end_date;
    public $diff;
    private $data = [];
    private $label = [];

    public function hourlyData()
    {
        $data = User::select(DB::raw("COUNT(*) as count"), DB::raw("HOUR(created_at) as hour"))
        ->where('created_at', '>=', $this->start_date)
        ->where('created_at', '<=', $this->end_date)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        while ($this->start_date <= $this->end_date) {
            $this->data[] = $data->where('hour', $this->start_date->format('h'))->pluck('count')->first() ?? 0;
            $this->label[] = $this->start_date->format('d M, y');
            $this->start_date->addDay();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Day'];
    }

    public function dailyData()
    {
        $data = User::select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
        ->whereDate('created_at', '>=', $this->start_date)
        ->whereDate('created_at', '<=', $this->end_date)
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        while ($this->start_date <= $this->end_date) {
            $this->data[] = $data->where('date', $this->start_date->format('Y-m-d'))->pluck('count')->first() ?? 0;
            $this->label[] = $this->start_date->format('d M, y');
            $this->start_date->addDay();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Day'];
    }


    public function weeklyData()
    {
        $data = User::select(DB::raw("COUNT(*) as count"), DB::raw("WEEK(created_at) as week"), DB::raw("YEAR(created_at) as year"))
        ->whereDate('created_at', '>=', $this->start_date)
        ->whereDate('created_at', '<=', $this->end_date)
        ->groupBy(DB::raw("WEEK(created_at)"), DB::raw("YEAR(created_at)"))
        ->orderBy(DB::raw("YEAR(created_at)"))
        ->orderBy(DB::raw("WEEK(created_at)"))
        ->get();

        while ($this->start_date->format('Y-m-d') <= $this->end_date->format('Y-m-d')) {
            $this->data[] = $data->where('week', $this->start_date->weekOfYear)
            ->where('year', $this->start_date->format('Y'))
            ->pluck('count')
            ->first() ?? 0;
            
            $this->label[] = $this->start_date->format('d M, y');
            $this->start_date->addWeek();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Week'];
    }


    public function monthlyData() 
    {
        $data = User::select(DB::raw("MONTH(created_at) AS month"), DB::raw("YEAR(created_at) AS year"), DB::raw('COUNT(*) AS count'))
        ->whereDate('created_at', '>=', $this->start_date)
        ->whereDate('created_at', '<=', $this->end_date)
        ->groupBy('month', 'year')
        ->orderBy('month')
        ->orderBy('year')
        ->get();

        while ($this->start_date->format('Y-m') <= $this->end_date->format('Y-m')) {
            $this->data[] = $data->where('year', $this->start_date->format('Y'))
            ->where('month', $this->start_date->format('m'))
            ->pluck('count')
            ->first() ?? 0;
            
            $this->label[] = $this->start_date->format('M, y');
            $this->start_date->addMonth();
        }
        
        return ['data' => $this->data, 'label' => $this->label, 'type' => 'Month'];
    }
}
