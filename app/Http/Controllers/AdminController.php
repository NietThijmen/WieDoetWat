<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminTaskRequest;
use App\Http\Requests\StoreAdminUserRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('admin');

        $tenantId = tenant('id');

        $users = User::query()
            ->where('tenant_id', $tenantId)
            ->latest()
            ->get(['id', 'name', 'email', 'is_admin', 'created_at']);

        $tasks = Task::query()
            ->where('tenant_id', $tenantId)
            ->withCount('users')
            ->latest()
            ->get(['id', 'title', 'description', 'weight', 'created_at']);

        return Inertia::render('Admin/Index', [
            'users' => $users,
            'tasks' => $tasks,
        ]);
    }

    public function storeUser(StoreAdminUserRequest $request): RedirectResponse
    {
        Gate::authorize('admin');

        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tenant_id' => tenant('id'),
            'is_admin' => $validated['is_admin'] ?? false,
        ]);

        return redirect()->route('admin.index');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        Gate::authorize('admin');

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.index');
    }

    public function storeTask(StoreAdminTaskRequest $request): RedirectResponse
    {
        Gate::authorize('admin');

        Task::create([
            ...$request->validated(),
            'tenant_id' => tenant('id'),
        ]);

        return redirect()->route('admin.index');
    }

    public function destroyTask(Task $task): RedirectResponse
    {
        Gate::authorize('admin');

        $task->delete();

        return redirect()->route('admin.index');
    }
}
