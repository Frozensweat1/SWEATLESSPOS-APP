<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class ProductDetail extends Component
{
    public $product;
    public $relatedProducts = [];
    public $phoneNumber = '';

    public function mount($id)
    {
        $this->product = Product::where('store_id', 1)->findOrFail($id);

        // Get related products from same category
        if ($this->product->category_id) {
            $this->relatedProducts = Product::where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->where('store_id', 1)
                ->limit(4)
                ->get();
        }

        // Get phone number from settings
        $this->phoneNumber = config('app.phone_number', '+233 XXX XXX XXX');
    }

    public function callUs()
    {
        $this->dispatch('notify', type: 'info', message: 'Please call us: ' . $this->phoneNumber);
        return view('livewire.website.product-detail');
    }
}
