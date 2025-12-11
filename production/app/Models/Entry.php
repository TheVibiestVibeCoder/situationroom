<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'workspace_id',
        'category',
        'text',
        'visible',
        'focused',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'focused' => 'boolean',
    ];

    /**
     * Get the workspace that owns the entry.
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    /**
     * Scope for visible entries.
     */
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    /**
     * Scope for focused entry.
     */
    public function scopeFocused($query)
    {
        return $query->where('focused', true);
    }
}
