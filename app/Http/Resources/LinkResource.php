<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'link' => config('custom.shareable_app_url') . $this->code,
            'link_code' => $this->code,
            'response' => $this->response,
            'project' => new ProjectResource($this->whenLoaded('project'))
        ];
    }
}
