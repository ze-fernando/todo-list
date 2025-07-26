<?php

namespace App\Models;

use App\Enums\Priority;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'todos';

    protected $fillable = [
        'title',
        'description',
        'expire',
        'done',
        'priority',
        'user_id'
    ];

    protected $casts = [
        'priority' => Priority::class,
        'done' => 'boolean',
    ];

    protected $attributes = [
        'priority' => Priority::LOW->value,
        'done' => false,
    ];

    public function setDone()
    {
        $this->done = true;
    }
}
