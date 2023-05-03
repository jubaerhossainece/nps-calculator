<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPaginateResouce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'feedbacks' => ProjectResource::collection($this->items()),
            'meta' => [
                'current_page' => $this->currentPage(),
                'total_items' => $this->total(),
                'items_per_page' => $this->perPage(),
                'total_pages' => $this->lastPage()
            ]
        ];
    }
}
