<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskCreatedEvent;
use App\Services\FireStoreService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TaskCreatedListener implements ShouldQueue
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
    public function handle(TaskCreatedEvent $event): void
    {
        try {
            FireStoreService::getInstance()->createTask($event->task->id,['status'=>$event->task->status]);
        }catch (Exception $exception){
            Log::error('failed to create task',$exception->getTrace());
        }
    }
}
