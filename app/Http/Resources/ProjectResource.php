<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo ? Storage::disk('public')->url($this->logo) : null,
            'wt_visibility' => boolval($this->wt_visibility),
            'name_field_visibility' => boolval($this->name_field_visibility),
            'email_field_visibility' => boolval($this->email_field_visibility),
            'comment_field_visibility' => boolval($this->comment_field_visibility),
            'welcome_text' => $this->welcome_text,
            'question' => $this->question,
            'comment' => $this->comment,
        ];
    }
}
