<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LinkRequest;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Resources\LinkResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectLink;
use App\Services\ProjectLinkService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProjectLinkController extends Controller
{
    protected $projectLinkService;

    public function __construct(ProjectLinkService $linkService)
    {
        $this->projectLinkService = $linkService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $project = Project::where([
            'user_id' => auth('sanctum')->user()->id,
            'id' => request('project_id')
        ])->first();

        $links = $project ? $project->links : [];

        return successResponseJson([
            'links' => LinkResource::collection($links)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LinkRequest $request)
    {
        $data = $this->projectLinkService->create($request->validated());

        return successResponseJson([
            'project' => new LinkResource($data)
        ], 'Link created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $code)
    {
        $link = ProjectLink::with('project')
            ->where('code', $code)
            ->first();

        if (!$link) {
            return errorResponseJson('No link found', 404);
        }

        return successResponseJson(['link' => new LinkResource($link)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code)
    {
        $data = ProjectLink::where('code', $code)
            ->whereHas('project', function ($query) {
                return $query->where('user_id', auth('sanctum')->user()->id);
            })
            ->update([
                'name' => $request->name,
                'response' => $request->response
            ]);

        return successResponseJson(null, 'Link updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
