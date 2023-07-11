<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\LinkResource;
use App\Http\Resources\FeedbackPaginateResouce;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectLink;
use App\Models\ProjectLinkFeedback;
use App\Services\ChartService;
use App\Services\ProjectLinkService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth('sanctum')->user()->projects;
        return successResponseJson(['projects' => ProjectResource::collection($projects)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        //@params description to upload image respectively request, field_name, upload_path(destination) and exist_file as exist file path.
        $validated['logo'] = imageUpload($request,'logo','upload/images/project-logo',null);

        $project = Project::create($validated);

        $link_data = [
            'project_id' => $project->id,
            'name' => $project->name,
            'response' => 'We really appreciate your feedback'
        ];
        $link_data = (new ProjectLinkService())->create($link_data);

        return successResponseJson([
            'project' => new ProjectResource($project),
            'link' => new LinkResource($link_data),
        ], 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::where('id', $id)->first();
        return successResponseJson(['project' => new ProjectResource($project)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectRequest $request, Project $project)
    {
        if (!$project) {
            return errorResponseJson('Project not found', 404);
        }
        $exist_file = $project->logo ?? null;
        $validated = $request->validated();

        //@params description to upload image respectively request, field_name, upload_path(destination) and exist_file as exist file path.
        $validated['logo'] = imageUpload($request,'logo','upload/images/project-logo',$exist_file);

        $project->update($validated);

        return successResponseJson([
            'project' => new ProjectResource($project),
            'link' => new LinkResource($project->link),
        ], 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return errorResponseJson('Project not found', 404);
        }

        $project->delete();
        return successResponseJson(null, 'Project deleted.');
    }

    public function getFeedbacks()
    {
        if(request('project_id')){
            $projectId = [request('project_id')];
            $link = ProjectLink::where('project_id', request('project_id'))->first();
        }else{
            $projectId = Project::where('user_id', auth()->user()->id)->pluck('id')->all();
            $link = '';
        }

        $project = Project::whereIn('id', $projectId)->where('user_id', auth()->user()->id)->get();
        if ($project->isEmpty()) {
            return errorResponseJson('No project found!', 404);
        }

        $feedbacks = ProjectLinkFeedback::whereIn('project_id', $projectId);

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

        // graph data
        $graph_data = new ChartService();
        $startDate = Carbon::parse($from);
        $endDate = Carbon::parse($to);
        
        $graph_data->start_date = $startDate;
        $graph_data->end_date = $endDate;
        $graph_data->query = clone $feedbacks;

        $diff = $endDate->diffInDays($startDate);

        if($diff <= 1){
            $graph = $graph_data->hourlyData();
        }elseif($diff <= 90){
            $graph = $graph_data->dailyData();
        }elseif ($diff < 180) {
            $graph = $graph_data->weeklyData();
        }elseif ($diff >= 180){
            $graph = $graph_data->monthlyData();
        }

        if ($from) {
            $feedbacks = $feedbacks->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        }
        
        $feedbacks = $feedbacks->paginate(10);

        return successResponseJson([
            'graph' => $graph,
            'feedbacks' => new FeedbackPaginateResouce($feedbacks),
            'link' => $link ? new LinkResource($link) : ''
        ]);
    }

    public function getProjectScore()
    {
        if(request('project_id')){
            $projectId = [request('project_id')];
        }else{
            $projectId = Project::where('user_id', auth()->user()->id)->pluck('id')->all();
        }

        $project = Project::whereIn('id', $projectId)
            ->where('user_id', auth()->user()->id)
            ->get();

        if ($project->isEmpty()) {
            return errorResponseJson('No project found!', 404);
        }

        $feedbacks = ProjectLinkFeedback::whereIn('project_id', $projectId)->get();

        //TODO: isEmpty method can be removed
        if ($feedbacks->isEmpty()) {
            return successResponseJson(['score' => [
                'DETRACTOR' => 0,
                'PASSIVE' => 0,
                'PROMOTER' => 0,
                'score' => 0,
            ]]);
        }

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

        return successResponseJson(['score' => $nps_score]);
    }

    public function getProjectUsers($projectId)
    {
        $project = Project::where('id', $projectId)->where('user_id', auth()->user()->id)->first();
        if (!$project) {
            return errorResponseJson('No project found!', 404);
        }

        $users = ProjectLinkFeedback::where('project_id', $projectId)->get(['id', 'name']);

        return successResponseJson(['users' => $users]);
    }

}
