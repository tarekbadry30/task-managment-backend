<?php

namespace App\Http\Controllers\Api\Task;

use App\Events\Task\TaskCreatedEvent;
use App\Events\Task\TaskDeletedEvent;
use App\Events\Task\TaskUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskCollectionResource;
use App\Http\Resources\Task\TaskResource;
use App\Http\Traits\ApiTrait;
use App\Models\Task;
use App\Services\FireStoreService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $list=Task::when($request->filled('status'),function ($task) use ($request){
            return $task->where('status',$request->input('status'));
        })->when($request->filled('search'),function ($task) use ($request){
            return $task->where('title','LIKE',"%{$request->input('search')}%");
        })->orderBy('id','desc')->paginate(15);
        return $this->successResponse(new TaskCollectionResource($list),'get tasks list');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $task=Task::create($request->validated());
        event(new TaskCreatedEvent($task));
        return $this->successResponse(new TaskResource($task),'new task created',201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task=Task::find($id);
        if(!isset($task))
            return $this->errorResponse('task not found',[],404);

        return $this->successResponse(new TaskResource($task),'get task details',200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        $task=Task::find($id);
        $oldStatus=$task->status;
        if(!isset($task))
            return $this->errorResponse('task not found',[],404);
        $task->update($request->validated());

        if($oldStatus!=$request->input('status'))
            event(new TaskUpdatedEvent($task));

        return $this->successResponse(new TaskResource($task),'the task updated success',200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task=Task::find($id);
        if(!isset($task))
            return $this->errorResponse('task not found',[],404);
        $task->delete();
        event(new TaskDeletedEvent($task));

        return $this->successResponse([],'the task deleted success',200);
    }
}
