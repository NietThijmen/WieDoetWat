<?php

namespace App\Actions\Task;

use App\Events\Tasks\TaskCreatedEvent;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class CreateTask
{
    /**
     * Create a task, validate the input, and dispatch the TaskCreatedEvent.
     *
     * @param  array<string, string>  $input
     */
    public function create(
        array $input
    ): Task {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'description' => 'nullable|string|max:255',
            'weight' => 'required|integer|min:1|max:10',
        ])->validate();

        $task = Task::create($input);

        TaskCreatedEvent::dispatch($task);

        return $task;
    }
}
