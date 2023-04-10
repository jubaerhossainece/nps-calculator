<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Resources\FeedbackResource;
use App\Http\Resources\LinkResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectLinkFeedback;
use App\Services\ProjectLinkService;

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
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $image_name = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('upload/images/project-logo', $image_name, 'public');
            $validated['logo'] = $path;
        }

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
    public function update(StoreProjectRequest $request, string $id)
    {
        $project = auth('sanctum')->user()->projects()->find($id);

        if (!$project) {
            return errorResponseJson('Project not found', 404);
        }

        $project->update($request->validated());
        return successResponseJson(null, 'Project updated successfully');
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
//       return ProjectLinkFeedback::type(1);
        $project = Project::find($projectId);

        if (!$project) {
            return errorResponseJson('Project not found', 404);
        }

        $feedbacks = $project->feedbacks;

        return successResponseJson(['feedbacks' => FeedbackResource::collection($feedbacks)]);
    }

}
