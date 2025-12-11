<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;

class EntryPolicy
{
    /**
     * Determine if the user can update the entry.
     */
    public function update(User $user, Entry $entry): bool
    {
        return $user->workspace_id === $entry->workspace_id;
    }

    /**
     * Determine if the user can delete the entry.
     */
    public function delete(User $user, Entry $entry): bool
    {
        return $user->workspace_id === $entry->workspace_id;
    }
}
