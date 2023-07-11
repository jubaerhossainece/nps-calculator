<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLink;
use App\Models\ReportAbuseForProjectLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportAbuseController extends Controller
{
     /**
     * Display the listing resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.survey_reports.index');
    }

     /**
     * Display the listing resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function list($type)
    {

        $projectLinkIds = DB::table('report_abuse_for_project_links')
            ->distinct()
            ->pluck('project_link_id');

        $reports = DB::table('project_links as plink')
            ->join('projects as p', 'p.id', '=', 'plink.project_id')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->whereIntegerInRaw('plink.id', $projectLinkIds)
            ->select('plink.id as project_link_id', 'plink.code', 'plink.status as project_link_status', 'p.id as project_id', 'p.name as project_name', 'u.id as user_id', 'u.name as user_name', 'u.status as status', 'u.email as user_email');

        if ($type == 'inactive') {
            $reports = $reports->where('plink.status', false);
        } elseif ($type == 'active') {
            $reports = $reports->where('plink.status', true);
        }

        $active_status = "<span class='text-success'><i class='mr-2 fas fa-circle fa-xs'></i>Active</span>";
        $inactive_status = "<span class='text-danger'><i class='mr-2 fas fa-circle fa-xs'></i>Inactive</span>";

        return DataTables::of($reports)
            ->addColumn('user_name', function ($report) {
                return $report->user_name;
            })
            ->addColumn('project_name', function ($report) {
                return $report->project_name;
            })
            ->addColumn('code', function ($report) {
                return $report->code;
            })
            ->addColumn('project_link_status', function ($report) use ($active_status, $inactive_status) {
                return $report->project_link_status ?  $active_status : $inactive_status;
            })
            ->addColumn('status', function ($report) use ($active_status, $inactive_status) {
                return $report->status ? $active_status : $inactive_status;
            })
            ->addColumn('action', function ($report) {

                return view('components.report-list-status', compact('report'));
            })
            ->rawColumns(['status', 'action', 'project_link_status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getReportRecords($project_link_id)
    {
        $link_reports = DB::table('report_abuse_for_project_links as rep')
            ->join('project_links as plink', 'plink.id', '=', 'rep.project_link_id')
            ->join('projects as p', 'p.id', '=', 'plink.project_id')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->where('rep.project_link_id', '=', $project_link_id)
            ->select('rep.id as id', 'rep.report_abuse_option_id as report_type_id', 'rep.comment as comment', DB::raw('date(rep.created_at) as date'), 'plink.id as project_link_id', 'plink.code', 'plink.status as project_link_status', 'p.id as project_id', 'p.name as project_name', 'u.id as user_id', 'u.name as user_name', 'u.status as status');

        return DataTables::of($link_reports)
            ->addColumn('comment', function ($report) {
                return $report->comment;
            })
            ->addColumn('report_type_id', function ($report) {
                $model = new ReportAbuseForProjectLink();
                $model->report_type_id = $report->report_type_id;
                return $model->getReportType();
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function showAllReportRecords($project_link_id)
    {
        $projectLink = DB::table('project_links as plink')
            ->join('projects as p', 'p.id', '=', 'plink.project_id')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->where('plink.id', '=', $project_link_id)
            ->select('plink.id', 'plink.name as project_name', 'plink.code', 'u.name as user_name', 'u.email as user_email')
            ->first();

        if (!$projectLink) {
            return redirect()->back();
        }
        return view('admin.survey_reports.full-report-list', compact('projectLink'));
    }

    public function getReportRecordsTopFive($project_link_id)
    {
        $link_reports = ReportAbuseForProjectLink::select('id', 'report_abuse_option_id', 'comment')->where('project_link_id', $project_link_id)->latest('id')->take(5)->get();
        return view('admin.survey_reports.modal.modal_table', compact('link_reports', 'project_link_id'));
    }
}
