<?php

namespace App\Actions\Task;

use App\Events\Tasks\TaskAssignedEvent;
use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class AssignTask
{
    /**
     * Assign a task to a user, has validation that the due date is after today
     * Also fires the TaskAssignedEvent (for notifying the user ETC)
     */
    public function assign(
        Task $task,
        User $user,
        Carbon $dueDate
    ): UserTask {
        Validator::make([
            'dueDate' => $dueDate,
        ], [
            'dueDate' => 'required|date|after:today',
        ])->validate();

        $userTask = UserTask::create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'due_date' => $dueDate,
        ]);

        TaskAssignedEvent::dispatch($userTask);

        return $userTask;
    }
}
