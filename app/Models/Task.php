<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property string $title
 * @property string $description
 * @property int $weight
 * @property-read Collection<UserTask> $users
 */
class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'weight',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(UserTask::class);
    }
}
