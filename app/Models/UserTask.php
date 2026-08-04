<?php

namespace App\Models;

use Database\Factories\UserTaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $user_id
 * @property int $task_id
 * @property-read User $user
 * @property-read Task $task
 */
class UserTask extends Model
{
    /** @use HasFactory<UserTaskFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'task_id',
        'due_at',
        'completed_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
