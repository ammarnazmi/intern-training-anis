<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "saved" event.
     */
    public function saved(User $user): void
    {
        $this->clearCache($user);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->clearCache($user);
    }

    /**
     * Clear cached data.
     */
    protected function clearCache(User $user): void
    {
        $prefix = 'user';
        $store = cache()->memo();
        $store->forget($prefix . '-' . $user->id);
        $store->forget($prefix . ':email-' . hash('sha256', $user->email));

        if ($user->isDirty('email')) {
            $store->forget($prefix . ':email-' . hash('sha256', $user->getOriginal('email')));
        }
    }
}
