<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Project;
use App\Models\ProjectLink;
use App\Models\ProjectLinkFeedback;
use App\Services\ProjectLinkService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        if (!$project) {
            return errorResponseJson('No project found!', 404);
        }

        $link = $project ? $project->link : [];

        return successResponseJson([
            'link' => new LinkResource($link)
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
        $request->validate([
            'name' => ['required', 'string'],
            'response' => ['required', 'string', 'max:2000']
        ]);

        $link = ProjectLink::where('code', $code)
            ->whereHas('project', function ($query) {
                return $query->where('user_id', auth('sanctum')->user()->id);
            })->first();

        $link->update([
            'name' => $request->name,
            'response' => $request->response
        ]);

        return successResponseJson(null, 'Link updated successfully');
    }

    public function submitFeedback(Request $request)
    {
        $link = ProjectLink::where('code', $request->code)->first();

        if (!$link) {
            return errorResponseJson('No data found!', 404);
        }

        $request->merge([
            'project_link_id' => $link->id,
            'project_id' => $link->project->id
        ]);

        $validated = $request->validate([
            'code' => ['required', 'string'],
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'rating' => ['required', 'string', Rule::in(ProjectLinkFeedback::RATING_VALUE)],
            'comment' => ['nullable', 'string', 'max:5000'],
            'project_link_id' => ['required', 'integer'],
            'project_id' => ['required', 'integer']
        ]);

        $validated['data'] = json_encode([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),

        ]);

        $link->feedbacks()->create($validated);

        return successResponseJson(['message' => $link->response], 'Feedback submitted successfully.');
    }
}
