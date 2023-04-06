<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LinkRequest;
use App\Http\Requests\Api\StoreProjectRequest;
use App\Http\Resources\LinkResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectLink;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProjectLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        return
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LinkRequest $request)
    {
        $validated = $request->validated();
        $validated['code'] = Str::random(10);

        $link = ProjectLink::create($validated);

        return successResponseJson([
            'project' => new LinkResource($link)
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
    public function update(StoreProjectRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
