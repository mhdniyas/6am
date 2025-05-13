<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sponsor extends Model
{
    /** @use HasFactory<\Database\Factories\SponsorFactory> */
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'category',
        'contact_person',
        'email',
        'phone',
        'committed_amount',
        'paid_amount',
        'logo_path',
        'notes',
    ];
    
    protected $casts = [
        'committed_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];
    
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    
    // Calculate the balance (committed_amount - paid_amount)
    public function getBalanceAttribute()
    {
        return $this->committed_amount - $this->paid_amount;
    }
}
