<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Workspace extends Model
{
    use Billable;

    protected $fillable = [
        'name',
        'subdomain',
        'stripe_customer_id',
        'stripe_subscription_id',
        'status',
        'trial_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the users for the workspace.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the entries for the workspace.
     */
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * Check if workspace is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
