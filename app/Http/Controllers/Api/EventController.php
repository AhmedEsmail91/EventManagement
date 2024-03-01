<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        return Event::all();
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
        return $event;
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
        return $event;
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
        
        return $event;
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
