<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $url = filter_var($this->image, FILTER_VALIDATE_URL);
        return [
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image ? ($url ? $this->image : Storage::disk('public')->url('organization/' . $this->image)) : null,
        ];
    }
}
