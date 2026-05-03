<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = TaskResource::collection(Task::with('user')->paginate(2));
        return response()->json([
            $tasks
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $input = $request->validated();

        $input["user_id"] = 1;

        $createdTask = Task::create($input);

        return new TaskResource($createdTask);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json([
            "task" => new TaskResource($task)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $input = $request->validated();

        $updated = $task->update($input);

        return response()->json([
            "updated_task" => new TaskResource($updated)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
