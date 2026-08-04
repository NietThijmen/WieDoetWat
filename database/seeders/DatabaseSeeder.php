<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::create([
            'id' => 'test',
        ]);
        $tenant->domains()->create(['domain' => 'test']);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'tenant_id' => $tenant->id,
        ]);

        $tasks = Task::factory()->count(5)->create([
            'tenant_id' => $tenant->id,
        ]);

        UserTask::factory()->count(3)->create([
            'user_id' => $user->id,
            'task_id' => fn () => $tasks->random()->id,
        ]);
    }
}
