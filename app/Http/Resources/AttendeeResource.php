<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
class AttendeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    

    public function toArray(Request $request): array
    {   
        $data = parent::toArray($request);
        try{
            $data = Arr::except($data, ['id','user_id']);
        }
        catch(\Exception $ex){
            return  [];
        }

        return $data;
    }
}
