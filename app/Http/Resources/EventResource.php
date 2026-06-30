<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'title'                => $this->title,
            'description'          => $this->description,
            'location'             => $this->location,
            'banner_url'           => $this->banner ? asset('storage/' . $this->banner) : null,
            'start_time'           => $this->start_time->toIso8601String(),
            'end_time'             => $this->end_time->toIso8601String(),
            'capacity'             => $this->capacity,
            'registrations_count'  => $this->registrations_count ?? $this->registrations->count(),
            'status'               => $this->status,
            'category'             => $this->whenLoaded('category', fn () => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'tags'                 => $this->whenLoaded('tags', fn () =>
                $this->tags->map(fn ($tag) => [
                    'id'   => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ])
            ),
            'organizer'            => $this->whenLoaded('user', fn () => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ]),
            'created_at'           => $this->created_at->toIso8601String(),
        ];
    }
}
