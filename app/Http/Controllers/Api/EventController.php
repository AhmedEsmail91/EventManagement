<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // return new UserResource;/
        // to get the events with its user (organizer)
        $query=Event::query(); // start a query builder for the event class.
        // attendees.user --> means that the binding between the attendee and the user which is the same data with level of abstraction
        $relations=['user','attendees','attendees.user']; // to specify what relations can be loaded in the event.
        foreach($relations as $relation){
            // $query->when(condition, fn())// only add when condition if it is true the fn() will be executed.
            
            $query->when(
                $this->shouldIncludeRelation($relation),
                fn($q)=>$q->with($relation)
            );
        }
        return EventResource::collection(
            $query->paginate()
        );
        
        

    }
    // The following function checks if the requested relation which is in the include request parameter
    // should be included or not, and execute which is exist and ignore which is not granteed.
    protected function shouldIncludeRelation(string $relation):bool{
        $include=request()->query('include'); // get the query of include 
        if(!$include){
            return false;
        }
        /* handling include the string
        $relations=array_map(fn($string)=>trim($string),explode(',', $include)); 
        $relations=array_map(fn($string)=>strtolower(trim($string)),explode(',', $include));//or simply: => do the following
        */  
        
        $relations=array_map('trim',explode(',', $include));

        return in_array($relation,$relations);
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
        $event = Event::create([...$valid_request,'user_id'=>1]);
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
        return new EventResource($event->load(['user','attendees']));
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
        
        return new EventResource($event);
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
