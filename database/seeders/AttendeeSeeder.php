<?php

namespace Database\Seeders;

use App\Models\Attendee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users= \App\Models\User::all();
        $events= \App\Models\Event::all();
        foreach ($users as $users) {
            $eventToAttend= $events->random(rand(1,3)); 
            // Create attendees for each user and associate them with a random number of events between 1 and 3
            foreach ($eventToAttend as $event) {
                Attendee::create([
                    'user_id'=>$users->id,
                    'event_id'=>$event->id
                ]);
            }
        }
    }
}
