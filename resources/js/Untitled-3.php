<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'project_id' => 'required|integer',
        ]);

        $tasks = Task::whereBelongsTo(Project::find($request->project_id))->get()->toArray();

        if (count($tasks) == 0) {
            $validated['priority'] = 1;
        } else {
            $lastTask = end($tasks);
            $validated['priority'] = $lastTask['priority'] + 1;
        }

        $task = Task::create($validated);
        return new TaskResource($task->fresh());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'filled|string',
            'project_id' => 'filled|integer',
            'priority' => 'filled|integer'
        ]);

        $projectId = $validated['project_id'] ?? $task->project_id;
        $tasks = Task::whereBelongsTo(Project::find($projectId))->orderBy('priority', 'asc')->get()->toArray();
        // $tasks = Task::whereBelongsTo(Project::find($request->project_id))->orderBy('priority', 'desc')->toArray();
        // return $tasks;

        // Item to move
        $itemToMove = $tasks[$task->priority - 1];



        // $myArray = array("apple", "banana", "cherry", "date");



        $task->update(['priority' => $request->priority]);

        // return $task;
        // New index for the item
        $newIndex = $task->priority - 1;


        // Find the index of the item to move
        $itemIndex = array_search($itemToMove, $tasks);

        $removedItem = array_splice($tasks, $itemIndex, 1);
        array_splice($tasks, $newIndex, 0, $removedItem);

        $tasks[$task->priority - 1]['priority'] = $request->priority;

        // $results = (object) $tasks;
        // return $tasks;
        // return (object) $tasks;




        $firstItem = $request->priority;
        $newTasks = array_slice($tasks, $firstItem, count($tasks) - $firstItem);
        // return [
        //     'newTasks' => $newTasks,
        //     'oldTasks' => $tasks
        // ];
        foreach (collect($newTasks) as $task) {
            // $task->update(['priority' => $task->priority + 1]);
            $newTask = Task::find($task['id']);
            $newTask->update(['priority' => $newTask->priority + 1]);
        }
        return Task::whereBelongsTo(Project::find($projectId))->orderBy('priority', 'asc')->get();

        if ($request->has('priority')) {
            if ($request->priority > count($tasks)) {
                $validated['priority'] = count($tasks) + 1;
            } elseif ($request->priority <= count($tasks)) {
                $validated['priority'] = $request->priority;
                $firstItem = $request->priority - 1;
                $newTasks = array_slice($tasks, $firstItem, count($tasks) - $firstItem);
                foreach ($newTasks as $task) {
                    $task['priority'] = $task['priority'] + 1;
                }
            }
        }

        $task->update($validated);
        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
