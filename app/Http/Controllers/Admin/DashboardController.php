<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLinkFeedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $audiences = User::count();
        $nps = ProjectLinkFeedback::count();
        $projects = Project::count();
        return view('admin.dashboard.index', compact('audiences', 'nps', 'projects'));
    }


    public function recentAudience()
    {
        $users = User::select('id', 'name', 'email', 'status')
        ->withCount('projects')
        ->withCount('feedbacks')
        ->latest()
        ->limit(5);

        return DataTables::of($users)
        ->addColumn('projects', function($user){
            return $user->projects_count ?? 0;
        })
        ->addColumn('feedbacks', function($user){
            return $user->feedbacks_count ?? 0;
        })
        ->addColumn('status', function($user){
            return $user->status ? "<span class='text-success'><i class='mr-2 fas fa-circle fa-xs'></i>Active</span>" : "<span class='text-danger'><i class='mr-2 fas fa-circle fa-xs'></i>Inactive</span>";
        })
        ->addColumn('action', function($user){
            
            return view('components.status', compact('user'));
        })
        ->rawColumns(['status', 'action'])
        ->addIndexColumn()
        ->make(true);

    }

    public function audienceChartData($type)
    {
        $db_data = User::select(DB::raw("(COUNT(*)) as count"),DB::raw($type." (created_at) as ".$type))
        ->whereYear('created_at', date('Y'))
        ->groupBy($type)
        ->orderBy($type)
        ->get();

        if($type == 'year'){
            $db_data = User::select(
                DB::raw("(COUNT(*)) as count"),
                DB::raw("YEAR(created_at) as year")
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();
        }

        $temp = [];
        foreach ($db_data as $value) {
            $temp[$value->$type] = $value->count;
        }

        $data = [];
        if($type == 'week'){

            for ($i=1; $i <= 52 ; $i++) { 
                $label[] = $i;
                $data[] = $temp[$i] ?? 0;
            }

        }elseif($type == 'month'){

            $label = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            foreach ($label as $key => $value) {
                $data[$key] = $temp[$key+1] ?? 0;
            }

        }elseif ($type == 'year') {
            
            $year = array_keys($temp);
            $start = min($year);
            $end = date("Y");
            for($i = $start; $i <= $end; $i++){
                $label[] = $i;
                $data[] = $temp[$i] ?? 0;
            }
            
        }

        return response([
            'label' => $label,
            'audience' => $data,
        ]);


        $monthly = User::select(DB::raw("(COUNT(*)) as count"),DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        if($type == 'yearly'){

            $label = [];

        }elseif($type == 'monthly'){
            $label = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        }elseif($type == 'weekly'){
            $weekly = User::select(DB::raw("(COUNT(*)) as count"),DB::raw("WEEK(created_at) as week"))
            ->whereDate('created_at', date('Y-m-d'))
            ->groupBy('week')
            ->orderBy('week')
            ->get();

            return successResponseJson([$label, $data]);
        }
    }
}
