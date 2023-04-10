<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Resources\LinkResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectLinkService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth('sanctum')->user()->projects()->get();
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectRequest $request, string $id)
    {
        $project = auth('sanctum')->user()->projects()->find($id);
        if ($project) {
            $project->update($request->validated());
        } else {
            return errorResponseJson('Project not found', 404);
        }
        return successResponseJson(null, 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);

        if ($project) {
            $project->delete();
            return successResponseJson(null, 'Project deleted.');
        }

        return errorResponseJson('Project not found', 404);
    }
}
