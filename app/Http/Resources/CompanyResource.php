<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'rating' => $this->rating,
            'phone' => $this->phone,
            'specialties' => $this->specialties,
            'plans_count' => $this->when(isset($this->plans_count), $this->plans_count),
            'callback_requests_count' => $this->when(isset($this->callback_requests_count), $this->callback_requests_count),
            'plans' => PlanResource::collection($this->whenLoaded('plans')),
            'callback_requests' => CallbackRequestResource::collection($this->whenLoaded('callbackRequests')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
