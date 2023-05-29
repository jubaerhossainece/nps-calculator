<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ReportAbuseForProjectLink;
use Illuminate\Http\Request;

class ReportAbuseController extends Controller
{
    public function index(){
        return view('admin.audiences.index');
    }

    public function list($type){

        return $reports = ReportAbuseForProjectLink::select('id', 'name')
        ->with('user')
        ->with('reports')
        ->whereHas('reports')
        ->get();

        if($type == 'inactive'){
            $users = $users->where('status', false);
        }elseif($type == 'active'){
            $users = $users->where('status', true);
        }
        
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
