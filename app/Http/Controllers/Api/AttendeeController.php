<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use App\Policies\AttendeePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttendeeController extends Controller
{
    use CanLoadRelationships;
    /**
     * Display a listing of the resource.
     */
    public function __construct() {
        // $this->middleware('auth:sanctum')->except(['index','show','update']);
        // $this->authorizeResource(Attendee::class,'')
    }
    public function index(Event $event)
    {
        $attendees=$this->loadRelationships(Event::find($event->id)->with('attendees'),['user']);
        
        return AttendeeResource::collection($attendees->paginate());

        // return $attendees;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Event $event)
    {

        $attendee=$event->attendees()->create(
            [
                'user_id'=>1
            ]
        );
        return  new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee  $attendee)
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event,Attendee $attendee)
    {
        $this->authorize('delete-attendee',[$event, $attendee]);
        $id=$attendee->id;
        $attendee->delete();
        return  response()->json(['message' => 'Deleted Successfully ID: '.$id]);
    }
}
