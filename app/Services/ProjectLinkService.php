<?php

namespace App\Services;

use App\Models\ProjectLink;
use Illuminate\Support\Str;

class ProjectLinkService
{
    public function create(array $data): ProjectLink
    {
        $data['code'] = Str::random(10);

        return ProjectLink::create($data);
    }
}
