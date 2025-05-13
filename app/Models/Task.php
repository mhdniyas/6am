<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'task_name',
        'assigned_to',
        'due_date',
        'done',
        'notes',
        'team_id',
        'sponsor_id',
    ];
    
    protected $casts = [
        'due_date' => 'date',
        'done' => 'boolean',
    ];
    
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }
}
