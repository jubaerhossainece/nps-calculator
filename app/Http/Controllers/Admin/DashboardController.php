<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLinkFeedback;
use App\Models\User;
use App\Services\ChartService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $total_users = User::count();
        $users = User::select('id', 'name', 'email')->get();
        $nps = ProjectLinkFeedback::count();
        $project_query = Project::select('id', 'name');
        $total_project = $project_query->count();
        $projects = $project_query->get();

        return view('admin.dashboard.index', compact('users', 'nps', 'projects', 'total_project', 'total_users'));
    }


    public function recentUser()
    {
        $users = User::select('id', 'name', 'email', 'status')
            ->withCount('projects')
            ->withCount('feedbacks')
            ->latest('id')
            ->limit(5);

        return DataTables::of($users)
            ->addColumn('projects', function ($user) {
                return $user->projects_count ?? 0;
            })
            ->addColumn('feedbacks', function ($user) {
                return $user->feedbacks_count ?? 0;
            })
            ->addColumn('status', function ($user) {
                return $user->status ? "<span class='text-success'><i class='mr-2 fas fa-circle fa-xs'></i>Active</span>" : "<span class='text-danger'><i class='mr-2 fas fa-circle fa-xs'></i>Inactive</span>";
            })
            ->addColumn('action', function ($user) {

                return view('components.status', compact('user'));
            })
            ->rawColumns(['status', 'action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function userChartData(Request $request)
    {
        $startDate = Carbon::parse(request('startDate')); 
        $endDate = Carbon::parse(request('endDate')); 
        $diff = $endDate->diffInDays($startDate);
        $query = User::query();

        $chart_data = new ChartService();
        $chart_data->start_date = $startDate;
        $chart_data->end_date = $endDate;
        $chart_data->query = $query;

        if($diff <= 1){
            $data = $chart_data->hourlyData();
        }elseif($diff <= 90){
            $data = $chart_data->dailyData();
        }elseif ($diff < 180) {
            $data = $chart_data->weeklyData();
        }elseif ($diff >= 180){
            $data = $chart_data->monthlyData();
        }

        return successResponseJson($data);
    }

    public function projectFeedbackChartData(Request $request)
    {
        $startDate = Carbon::parse(request('startDate')); 
        $endDate = Carbon::parse(request('endDate'));
        $diff = $endDate->diffInDays($startDate);
        $query = ProjectLinkFeedback::query();

        if(request('user_id')){
            if(request('project_id')){
                $query = $query->where('project_id', request('project_id'));
            }else{
                $projectId = Project::where('user_id', request('user_id'))->pluck('id')->all();
                $query = $query->whereIn('project_id', $projectId);
            }
        }

        $chart_data = new ChartService();
        $chart_data->start_date = $startDate;
        $chart_data->end_date = $endDate;
        $chart_data->query = $query;
        
        if($diff <= 1){
            $data = $chart_data->hourlyData();
        }elseif($diff <= 90){
            $data = $chart_data->dailyData();
        }elseif ($diff < 180) {
            $data = $chart_data->weeklyData();
        }elseif ($diff >= 180){
            $data = $chart_data->monthlyData();
        }

        return successResponseJson($data);
    }


    function projectChartData() {
        
        $startDate = Carbon::parse(request('startDate')); 
        $endDate = Carbon::parse(request('endDate')); 
        $diff = $endDate->diffInDays($startDate);
        $query = Project::query();

        $chart_data = new ChartService();
        $chart_data->start_date = $startDate;
        $chart_data->end_date = $endDate;
        $chart_data->query = $query;
        
        if($diff <= 1){
            $data = $chart_data->hourlyData();
        }elseif($diff <= 90){
            $data = $chart_data->dailyData();
        }elseif ($diff < 180) {
            $data = $chart_data->weeklyData();
        }elseif ($diff >= 180){
            $data = $chart_data->monthlyData();
        }
        return successResponseJson($data);
    }


    public function userProjects($user){
        $projects = Project::select('id', DB::raw('name as text'))->where('user_id', $user)->get();

        return successResponseJson($projects);
    }
}
