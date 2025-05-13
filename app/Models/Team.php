<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'team_name',
        'contact_person',
        'email',
        'phone',
        'owner',
        'cash_paid',
        'notes',
    ];
    
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    
    // Calculate the total due from all expenses
    public function getTotalDueAttribute()
    {
        return $this->expenses()->sum('amount');
    }
    
    // Calculate the balance (total due - cash paid)
    public function getBalanceAttribute()
    {
        return $this->getTotalDueAttribute() - $this->cash_paid;
    }
}
