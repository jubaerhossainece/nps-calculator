<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ReportAbuseForProjectLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportAbuseController extends Controller
{
    public function index(){
        return view('admin.survey_reports.index');
    }

    public function list($type){

        $reports = DB::table('report_abuse_for_project_links as rep')
        ->join('project_links as plink', 'plink.id', '=', 'rep.project_link_id')
        ->join('projects as p', 'p.id', '=', 'plink.project_id')
        ->join('users as u', 'u.id', '=', 'p.user_id')
        ->select('rep.id as id','rep.report_abuse_option_id as report_type_id','rep.comment as comment',DB::raw('date(rep.created_at) as date'), 'plink.code', 'p.id as project_id', 'p.name as project_name', 'u.id as user_id', 'u.name as user_name', 'u.status as status')
        ->get();

        if($type == 'inactive'){
            $reports = $reports->where('status', false);
        }elseif($type == 'active'){
            $reports = $reports->where('status', true);
        }

        return DataTables::of($reports)
        ->addColumn('name', function($report){
            return $report->user_name;
        })
        ->addColumn('project_name', function($report){
            return $report->project_name;
        })
        ->addColumn('code', function($report){
            return $report->code;
        })
        ->addColumn('report_type_id', function($report){
            $model = new ReportAbuseForProjectLink();
            $model->report_type_id = $report->report_type_id;
            return $model->getReportType();
        })
        ->addColumn('status', function($report){
            return $report->status ? "<span class='text-success'><i class='mr-2 fas fa-circle fa-xs'></i>Active</span>" : "<span class='text-danger'><i class='mr-2 fas fa-circle fa-xs'></i>Inactive</span>";
        })
        ->addColumn('action', function($report){
            
            return view('components.report-list-status', compact('report'));
        })
        ->rawColumns(['status', 'action'])
        ->addIndexColumn()
        ->make(true);       
        
       
    }

}
