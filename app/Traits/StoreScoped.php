<?php

namespace App\Traits;

use App\Helpers\StoreHelper;

trait StoreScoped
{
    /**
     * Automatically filter queries by the current store
     * Apply this to any model query to ensure multi-store isolation
     *
     * Usage in Livewire:
     * use StoreScoped;
     *
     * In mount():
     * $this->store_id = StoreHelper::getCurrentStoreId();
     *
     * In queries:
     * Product::where('store_id', $this->store_id)->get();
     */

    /**
     * Get the current store ID for use in queries
     *
     * @return int|null
     */
    protected function getStoreId()
    {
        return StoreHelper::getCurrentStoreId();
    }

    /**
     * Init store_id in component
     */
    protected function initStoreId()
    {
        if (!isset($this->store_id)) {
            $this->store_id = $this->getStoreId();
        }
    }
}
