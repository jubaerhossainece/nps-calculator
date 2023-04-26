<?php

namespace App\Http\Resources;

use App\Models\ProjectLinkFeedback;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'rating' => $this->rating,
            'type' => ProjectLinkFeedback::type($this->rating),
            'comment' => $this->comment,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
