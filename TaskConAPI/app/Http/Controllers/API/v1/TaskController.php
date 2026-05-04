<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
// use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = request()->user();
        $tasks = $user->task()->with('user')->paginate(2);
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $input = $request->validated();

        $input["user_id"] = $request->user()->id;

        $createdTask = Task::create($input);

        return new TaskResource($createdTask);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $user = request()->user();

        if ($user->id != $task->user_id) {
            abort(403, "Not Allowed");
        };

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

        $user = request()->user();

        if ($user->id != $task->user_id) {
            abort(403, "Not Allowed");
        };

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
        $user = request()->user();

        if ($user->id != $task->user_id) {
            abort(403, "Not Allowed");
        };
        return response()->noContent();
    }
}
