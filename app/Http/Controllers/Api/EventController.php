<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use CanLoadRelationships;
    /**
     * Display a listing of the resource.
     */
    // make constructor
    public function __construct()
    {
        // authenticate all methods except show and index which are public information.
        // instead of adding it in the route for each method just make a general auth middleware that will apply to every single instance
        $this->middleware('auth:sanctum')->except(['index','show']); 
    }
    private $relations = ['user','attendees','attendees.user'];
    public function index()
    {
        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate()
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid_request=$request->validate(
            [
                'name'=>['required','string','max:255'],
                'description'=>['string','nullable'],
                'start_time'=>['required','date'],
                'end_time'=>['required','date','after:start_time']
            ]
        );
        $event = Event::create([...$valid_request,'user_id'=>$request->user()->id]);// in requests the data is passing through all the api.
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
        return new EventResource(
            $this->loadRelationships($event)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Event $event)
    {
        $valid_request=$request->validate(
            [
                'name'=>['string','max:255'],
                'description'=>['string','nullable'],
                'start_time'=>['date'],
                'end_time'=>['date','after:start_time']
            ]
        );
        $event->update($valid_request); // refernce calling.
        
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        // we return a message to the server to tell that we have deleted the resource successfully and not the event cause it doesn't exist any more.
        return  response()->json(['message' => 'Deleted Successfully']);
        // or simply return the code which meaning that: 204
        // return response('',status:204);
    }
}
