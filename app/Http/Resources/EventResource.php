<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
            'id'=>$this->id,
            'event name'=>$this->name,
            'event description'=>$this->description,
            'start time'=>$this->end_time,
            'end time'=>$this->start_time,
            // 'end time'=>$this->start_time,
            // 'Organizer info'=> new UserResource([$this->user->name,$this->user->email])
            // just in loading
            'Organizer info'=> new UserResource($this->whenLoaded('user')),
            'Attendees'=> AttendeeResource::collection($this->whenLoaded('attendees'))

        ];
    }
}
