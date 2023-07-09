<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLinkFeedback;
use App\Models\User;
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
        $startDate = Carbon::parse($request['startDate']); 
        $endDate = Carbon::parse($request['endDate']); 
        $diff = $endDate->diffInDays($startDate);
        
        if($diff < 1){
            $group = 'hour';
            $type = 'hour';

        }elseif($diff <= 90){
            $group = 'day';
            $db_data = User::select(DB::raw("COUNT(*) as count"), DB::raw("DAY(created_at) as day"))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy(DB::raw("DAY(created_at)"))
            ->orderBy(DB::raw("DAY(created_at)"))
            ->get();
        }elseif ($diff < 180) {
            $group = 'week';
            $db_data = User::select(DB::raw("COUNT(*) as count"), DB::raw("WEEK(created_at) as week"))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy(DB::raw("WEEK(created_at)"))
            ->orderBy(DB::raw("WEEK(created_at)"))
            ->get();
        }else {
            $group = 'month';

            $db_data = User::select(DB::raw("COUNT(*) as count"), DB::raw("MONTH(created_at) as month"),DB::raw("YEAR(created_at) AS year"))
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy(DB::raw("YEAR(created_at), MONTH(created_at)"))
            ->orderBy(DB::raw("YEAR(created_at), MONTH(created_at)"))
            ->get();
        }


        return response([
            'start' => $request->startDate,
            'end' => $request->endDate,
            'db_data' => $db_data
        ]);

        // $db_data = User::select(DB::raw("(COUNT(*)) as count"), DB::raw($type . " (created_at) as " . $type))
        //     ->whereDate('created_at', '>=', $startDate)
        //     ->whereDate('created_at', '<=', $endDate)
        //     ->groupBy($type)
        //     ->orderBy($type)
        //     ->get();
            return $db_data;

        if ($type == 'year') {
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
        if ($type == 'week') {

            for ($i = 1; $i <= 52; $i++) {
                $label[] = $i;
                $data[] = $temp[$i] ?? 0;
            }
        } elseif ($type == 'month') {

            $label = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            foreach ($label as $key => $value) {
                $data[$key] = $temp[$key + 1] ?? 0;
            }
        } elseif ($type == 'year') {

            $year = array_keys($temp);
            $start = min($year);
            $end = date("Y");
            for ($i = $start; $i <= $end; $i++) {
                $label[] = $i;
                $data[] = $temp[$i] ?? 0;
            }
        }

        return response([
            'label' => $label,
            'user' => $data,
        ]);


        $monthly = User::select(DB::raw("(COUNT(*)) as count"), DB::raw("MONTH(created_at) as month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        if ($type == 'yearly') {

            $label = [];
        } elseif ($type == 'monthly') {
            $label = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        } elseif ($type == 'weekly') {
            $weekly = User::select(DB::raw("(COUNT(*)) as count"), DB::raw("WEEK(created_at) as week"))
                ->whereDate('created_at', date('Y-m-d'))
                ->groupBy('week')
                ->orderBy('week')
                ->get();

            return successResponseJson([$label, $data]);
        }
    }

    public function projectFeedbackChartData(Request $request)
    {
        $type = 'month';
        $project_id = $request->project_id;
        $to_date = $request->to_date;
        $from_date = $request->from_date;

        $db_data_query = ProjectLinkFeedback::query();


        if ($project_id) {
            $db_data_query->where('project_id', $project_id);
        }

        if ($from_date && $to_date) {
            $db_data_query->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
        } else {
            $db_data_query->whereYear('created_at', date('Y'));
        }

        $feedbacks =  $db_data_query->get();
        $nps_score = [];

        $detractor_count = $feedbacks->whereIn('rating', ProjectLinkFeedback::DETRACTOR)->count();
        $passive_count = $feedbacks->whereIn('rating', ProjectLinkFeedback::PASSIVE)->count();
        $promoter_count = $feedbacks->whereIn('rating', ProjectLinkFeedback::PROMOTER)->count();
        $total_response = $feedbacks->count();

        $nps_score['DETRACTOR'] = round( $detractor_count / $total_response * 100, 2);
        $nps_score['PASSIVE'] = round( $passive_count / $total_response * 100, 2);
        $nps_score['PROMOTER'] = round( $promoter_count / $total_response * 100, 2);

        // nps_score = (number of promoters - number of detractors) / number of responses * 100
        $nps_score['score'] = round( ($promoter_count - $detractor_count) / $feedbacks->count() * 100, 2);
        
        

        $data = [];

        $label = ["DETRACTOR", "PASSIVE", "PROMOTER"];

        $data[0] =  $nps_score['DETRACTOR'] ?? 0;
        $data[1] =  $nps_score['PASSIVE'] ?? 0;
        $data[2] =  $nps_score['PROMOTER'] ?? 0;

        return response([
            'label' => $label,
            'data' => $data,
            'score' => $nps_score['score'] ?? 0,
        ]);
    }

    public function npsScoreChartData(Request $request){

        $type = 'date';
        $from_date = $request->from_date ??  Carbon::today()->startOfMonth()->toDateString();
        $to_date = $request->to_date ?? Carbon::today()->endOfMonth()->toDateString();
        $project_ids = $request->project_id ? [$request->project_id] : Project::orderBy('id','asc')->pluck('id')->toArray();

        $score_ranges = [
            'DETRACTOR' => [0,6],
            'PASSIVE' => [7,8],
            'PROMOTER' => [9,10],
        ];

        $collections = ProjectLinkFeedback::select( 
        DB::raw('SUM(CASE WHEN rating >= ' . $score_ranges['DETRACTOR'][0] . ' AND rating <= ' . $score_ranges['DETRACTOR'][1] . ' THEN 1 ELSE 0 END) AS DETRACTOR'),
        DB::raw('SUM(CASE WHEN rating >= ' . $score_ranges['PASSIVE'][0] . ' AND rating <= ' . $score_ranges['PASSIVE'][1] . ' THEN 1 ELSE 0 END) AS PASSIVE'),
        DB::raw('SUM(CASE WHEN rating >= ' . $score_ranges['PROMOTER'][0] . ' AND rating <= ' . $score_ranges['PROMOTER'][1] . ' THEN 1 ELSE 0 END) AS PROMOTER'), DB::raw($type . " (created_at) as " . $type))
        ->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)
        ->whereIntegerInRaw('project_id',$project_ids)
        ->groupBy($type)
        ->orderBy($type)
        ->get();


        foreach($collections as $key => $_data){
            $label[] = $_data->date;
            $data[] =  round( ($_data->PROMOTER - $_data->DETRACTOR) / ( $_data->PROMOTER + $_data->PASSIVE +  $_data->DETRACTOR) * 100, 2) ?? 0;
        }
        return response([
            'label' => $label ?? [],
            'data' => $data ?? [],
        ]);
    }
}
