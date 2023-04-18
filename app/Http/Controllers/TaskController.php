<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::where('project_id', $request->project_id)->orderBy('priority', 'desc')->get();
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|integer',
            'name' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = DB::table('tasks')
                        ->where('project_id', $request->input('project_id'))
                        ->where('name', $value)
                        ->exists();
                    if ($exists) {
                        $fail("The combination of project_id and name already exists.");
                    }
                }
            ],
        ]);
        $validated['priority'] = 1;
        $task = Task::create($validated);

        $tasks = Task::whereBelongsTo(Project::find($request->project_id))
            ->orderBy('priority', 'desc')
            ->get()
            ->toArray();

        $this->updateTasks($tasks);
        return new TaskResource($task->fresh());
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'filled|string',
            'project_id' => 'filled|integer',
        ]);

        $task->update($validated);
        if ($request->has('project_id')) {
            $task->update(['priority' => 1]);
            $tasks = Task::whereBelongsTo(Project::find($request->project_id))
                ->orderBy('priority', 'desc')
                ->get()
                ->toArray();
            $this->updateTasks($tasks);
        }
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $tasks = Task::whereBelongsTo(Project::find($task->project_id))
            ->whereNot('id', $task->id)
            ->orderBy('priority', 'desc')
            ->get()
            ->toArray();

        $task->delete();
        $this->updateTasks($tasks);

        $myTasks = Task::find($tasks)->sortByDesc('priority');

        return TaskResource::collection($myTasks);
    }

    public function reorder(Request $request)
    {
        $this->updateTasks($request->tasks);

        return response()->json([
            'data' => [
                'message' => 'Tasks successfully reordered!'
            ]
        ], 200);
    }

    private function updateTasks($tasks)
    {
        foreach ($tasks as $key => $newTask) {
            $newTask['priority'] = count($tasks) - $key;
            Task::where('id', $newTask['id'])->update(['priority' => $newTask['priority']]);
        }
    }
}
