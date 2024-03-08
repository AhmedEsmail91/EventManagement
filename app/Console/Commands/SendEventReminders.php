<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders'; // the commands name (calling name in cmd)

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all event attendees that event starts soon.';

    /**
     * Execute the console command.
     */
    public function handle() // the logic happens in this method
    {
        // get all events where start_date is less than 24 hours from now, but not yet started
        $events = Event::with('attendees.user')
            ->whereBetween('start_time',[now(),now()->addDays(1)])->get();
        $eventCount=$events->count();
        $eventLabel=Str::plural('event',$eventCount);
        $events->each(
            fn($event)=>$event->attendees()->each(
                fn($attendee)=>$this->info("notifing $attendee->user_id")
            )
            );
        $this->info("Found $eventCount $eventLabel."); // will display some information inside the console once we execute this method
        $this->info('Reminder notifications sent successfully!'); // will display some information inside the console once we execute this method
        

        // $this->info($events->load('attendees:user_id'));

    }
}
