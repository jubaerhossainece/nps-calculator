<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\LinkResource;
use App\Http\Resources\FeedbackPaginateResouce;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectLinkFeedback;
use App\Services\ProjectLinkService;
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

    public function getFeedbacks(string $projectId)
    {
        $project = Project::where('id', $projectId)->where('user_id', auth()->user()->id)->first();
        if (!$project) {
            return errorResponseJson('No project found!', 404);
        }

        $feedbacks = ProjectLinkFeedback::where('project_id', $projectId);


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
        if ($from) {
            $feedbacks = $feedbacks->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        }

        if (\request('users')) {
            $users = explode(',', \request('users'));

            if (gettype($users) === 'array' && count($users) > 0) {
                $feedbacks->whereIn('id', $users);
            }
        }


//        $f = $d->selectRaw('MONTH(created_at) as month')->get();

        //show graph
        $graph['categories'] = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];

        //TODO: optimize query
        foreach ($graph['categories'] as $key => $month) {
            $d = clone $feedbacks;
            $graph['data'][] = $d->whereMonth('created_at', $key + 1)->count();
        }

        $feedbacks = $feedbacks->paginate(10);

        return successResponseJson([
            'graph' => $graph,
            'feedbacks' => new FeedbackPaginateResouce($feedbacks),
            'link' => new LinkResource($project->link)
        ]);
    }

    public function getProjectScore($projectId)
    {
        $project = Project::where('id', $projectId)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$project) {
            return errorResponseJson('No project found!', 404);
        }

        $feedbacks = ProjectLinkFeedback::where('project_id', $projectId)->get();

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
