<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLinkFeedback;
use App\Models\User;
use Illuminate\Http\Request;
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
}
