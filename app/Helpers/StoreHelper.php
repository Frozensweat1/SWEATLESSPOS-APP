<?php

namespace App\Helpers;

use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StoreHelper
{
    /**
     * Get the currently active store for the authenticated user
     *
     * @return Store|null
     */
    public static function getCurrentStore()
    {
        if (!Auth::check()) {
            return null;
        }

        // Check if store_id is in session
        $storeId = Session::get('current_store_id');

        if ($storeId) {
            return Store::find($storeId);
        }

        // Otherwise, get user's assigned store
        $user = Auth::user();
        if ($user && $user->store_id) {
            Session::put('current_store_id', $user->store_id);
            return $user->store;
        }

        return null;
    }

    /**
     * Set the current store for the authenticated user
     *
     * @param int|Store $store
     * @return void
     */
    public static function setCurrentStore($store)
    {
        $storeId = $store instanceof Store ? $store->id : $store;

        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Super admins can switch to any existing store
        if ($user->hasRole('super-admin') && Store::whereKey($storeId)->exists()) {
            Session::put('current_store_id', $storeId);
            return;
        }

        // Other users can only use their assigned store
        if ($user->store_id === $storeId) {
            Session::put('current_store_id', $storeId);
        }
    }

    /**
     * Get current store ID
     *
     * @return int|null
     */
    public static function getCurrentStoreId()
    {
        $store = self::getCurrentStore();
        return $store ? $store->id : null;
    }

    /**
     * Check if user has access to a specific store
     *
     * @param Store|int $store
     * @return bool
     */
    public static function userHasStoreAccess($store)
    {
        if (!Auth::check()) {
            return false;
        }

        $storeId = $store instanceof Store ? $store->id : $store;
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole('super-admin')) {
            return Store::whereKey($storeId)->exists();
        }

        return $user->store_id === $storeId;
    }
}
