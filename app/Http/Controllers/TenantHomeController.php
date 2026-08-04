<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TenantHomeController extends Controller
{
    public function __invoke(): Response
    {
        $tasks = Task::query()
            ->where('tenant_id', tenant('id'))
            ->select(['id', 'title', 'description', 'weight'])
            ->get();

        $userTask = Auth::user()
            ?->tasks()
            ->with('task')
            ->whereNull('completed_at')
            ->latest('due_at')
            ->first();

        return Inertia::render('Tenant/Home', [
            'tasks' => $tasks,
            'targetTask' => $userTask?->task,
        ]);
    }
}
