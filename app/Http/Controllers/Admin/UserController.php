<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(){
        return view('admin.audiences.index');
    }

    public function list($type){

        $users = User::select('id', 'name', 'email', 'status')
        ->withCount('projects')
        ->withCount('feedbacks');

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

    public function toggleStatus($id){
        $user = User::find($id);

        $user->status = !$user->status;
        $user->save();
        
    }
}
