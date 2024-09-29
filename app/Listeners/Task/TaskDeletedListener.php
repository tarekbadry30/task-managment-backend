<?php

namespace App\Listeners\Task;

use App\Events\Task\TaskDeletedEvent;
use App\Services\FireStoreService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TaskDeletedListener implements ShouldQueue
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
    public function handle(TaskDeletedEvent $event): void
    {
        try {
            FireStoreService::getInstance()->deleteTask($event->task_id);
        }catch (Exception $exception){
            Log::error('failed to delete task',$exception->getTrace());
        }
    }
}
