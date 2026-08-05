<?php

namespace App\Observers;

use App\Models\Admin;

class AdminObserver
{
    /**
     * Handle the Admin "saved" event.
     */
    public function saved(Admin $admin): void
    {
        $this->clearCache($admin);
    }

    /**
     * Handle the Admin "deleted" event.
     */
    public function deleted(Admin $admin): void
    {
        $this->clearCache($admin);
    }

    /**
     * Clear cached data.
     */
    protected function clearCache(Admin $admin): void
    {
        $prefix = 'admin';
        $store = cache()->memo();
        $store->forget($prefix . '-' . $admin->id);
        $store->forget($prefix . ':email-' . hash('sha256', $admin->email));

        if ($admin->isDirty('email')) {
            $store->forget($prefix . ':email-' . hash('sha256', $admin->getOriginal('email')));
        }
    }
}
