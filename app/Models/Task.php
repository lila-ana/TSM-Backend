<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'title',
        'description',
        'start_date',
        'end_date',
        'assigned_to',
        // 'status',
        'actual_progress',

    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }
}
