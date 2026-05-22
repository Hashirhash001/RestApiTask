<?php

namespace App\Models;

use App\Models\Projects;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to',
    ];

    public function project()
    {
        return $this->belongsTo(Projects::class);
    }

    public function assigned_to()
    {
        return $this->belongsTo(User::class);
    }
}
