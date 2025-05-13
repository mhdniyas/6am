<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'expense_type',
        'description',
        'amount',
        'status',
        'date',
        'team_id',
        'receipt_path',
        'notes',
    ];
    
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];
    
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
