<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->status,
            'note'       => $this->note,
            'created_at' => $this->created_at->toIso8601String(),
            'event'      => $this->whenLoaded('event', fn () => [
                'id'         => $this->event->id,
                'title'      => $this->event->title,
                'location'   => $this->event->location,
                'start_time' => $this->event->start_time->toIso8601String(),
                'status'     => $this->event->status,
            ]),
            'user'       => $this->whenLoaded('user', fn () => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]),
        ];
    }
}
