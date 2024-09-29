<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskUpdatedEvent;
use App\Services\FireStoreService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TaskUpdatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskUpdatedEvent $event): void
    {
        try {
            FireStoreService::getInstance()->updateTask($event->task->id,['status'=>$event->task->status]);
        }catch (Exception $exception){
            Log::error('failed to create task',$exception->getTrace());
        }

    }
}
