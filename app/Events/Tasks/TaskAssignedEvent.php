<?php

namespace App\Events\Tasks;

use App\Models\UserTask;
use Illuminate\Foundation\Events\Dispatchable;

class TaskAssignedEvent
{
    use Dispatchable;

    public function __construct(
        public UserTask $userTask
    ) {}
}
