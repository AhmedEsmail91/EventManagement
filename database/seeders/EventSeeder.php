<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users=  \App\Models\User::inRandomOrder()->limit(200)->get();
        foreach ($users as $user) {
            Event::factory()->create(['user_id'=>$user->id]);
        }
        // $users->each(function($user){
            
        //     Event::factory()->create(['user_id'=>$user->id]);
        // });
    }
    
}
