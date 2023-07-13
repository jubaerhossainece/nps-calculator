<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeedbackPaginateResouce;
use App\Http\Resources\LinkResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\ProjectLink;
use App\Models\ProjectLinkFeedback;
use App\Models\User;
use App\Services\ChartService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    function test(Request $request) {
        
    }


    function chartDate()
    {
        // if(request('project_id')){
        //     $projectId = [request('project_id')];
        //     $link = ProjectLink::where('project_id', request('project_id'))->first();
        // }else{
        //     $projectId = Project::where('user_id', auth()->user()->id)->pluck('id')->all();
        //     $link = '';
        // }

        $projectId = [ 12, 13, 14, 15, 31, 32, 33, 34, 35, 36, 39, 68, 75, 80, 92, 93, 94, 98, 99, 100, 102, 117, 118, 120, 128, 129 ];

        // $project = Project::whereIn('id', $projectId)->where('user_id', auth()->user()->id)->get();
        // if ($project->isEmpty()) {
        //     return errorResponseJson('No project found!', 404);
        // }

        $feedbacks = ProjectLinkFeedback::whereIn('project_id', $projectId);
        // $feedbacks = ProjectLinkFeedback::query();

        //filter by response
        if (request('response') === 'detractor') {
            $feedbacks = $feedbacks->whereIn('rating', ProjectLinkFeedback::DETRACTOR);
        } elseif (request('response') === 'passive') {
            $feedbacks = $feedbacks->whereIn('rating', ProjectLinkFeedback::PASSIVE);
        } elseif (request('response') === 'promoter') {
            $feedbacks = $feedbacks->whereIn('rating', ProjectLinkFeedback::PROMOTER);
        }

        //filter by comment
        if (request('comment') === 'true') {
            $feedbacks = $feedbacks->whereNotNull('comment');
        } elseif (request('comment') === 'false') {
            $feedbacks = $feedbacks->whereNull('comment');
        }

        //filter by date
        $from = request('from');
        $to = request('to') ?? now();
        
        if(request('search_param')){
            $anonymous = ['anonymous', 'anonimous', 'annonymous', 'anonymious', 'annonimous'];
            
            if(in_array(strtolower(request('search_param')), $anonymous)){
                $feedbacks = $feedbacks->where(function($q){
                    return $q->where('name', null)
                    ->where('email', null);
                });
            }else{
                $feedbacks = $feedbacks->where(function($q){
                    return $q->where('name', 'like', "%".request('search_param')."%")
                    ->orWhere('email', 'like', "%".request('search_param')."%");
                });
            }
        }

        
        $startDate = Carbon::parse($from, 'Asia/Dhaka');
        $endDate = Carbon::parse($to, 'Asia/Dhaka');

        $diff = $endDate->diffInDays($startDate);

        // graph data
        $graph_data = new ChartService();
        $graph_data->start_date = clone $startDate;
        $graph_data->end_date = clone $endDate;

        if($diff <= 1){
            $startDate = $startDate->tz('GMT');
            $endDate = $endDate->tz('GMT');

            $graph_data->start_date = clone $startDate;
            $graph_data->end_date = clone $endDate;

            $feedbacks->where('created_at', '>=', $startDate);
            $feedbacks->where('created_at', '<=', $endDate);

            $graph_data->query = clone $feedbacks;
            $graph = $graph_data->hourlyData();
        }elseif($diff <= 90){
            $feedbacks->whereDate('created_at', '>=', $startDate);
            $feedbacks->whereDate('created_at', '<=', $endDate);

            $graph_data->query = clone $feedbacks;
            $graph = $graph_data->dailyData();
        }elseif ($diff < 180) {
            $feedbacks->whereDate('created_at', '>=', $startDate);
            $feedbacks->whereDate('created_at', '<=', $endDate);

            $graph_data->query = clone $feedbacks;
            $graph = $graph_data->weeklyData();
        }elseif ($diff >= 180){
            $feedbacks->whereDate('created_at', '>=', $startDate);
            $feedbacks->whereDate('created_at', '<=', $endDate);

            $graph_data->query = clone $feedbacks;
            $graph = $graph_data->monthlyData();
        }

        // if ($from) {
        //     $feedbacks = $feedbacks->where('created_at', '>=', $from)->where('created_at', '<=', $to);
        // }
        
        $feedbacks = $feedbacks->latest()->paginate(10);

        return successResponseJson([
            'graph' => $graph,
            'feedbacks' => new FeedbackPaginateResouce($feedbacks),
            // 'link' => $link ? new LinkResource($link) : ''
        ]);
    }
}
