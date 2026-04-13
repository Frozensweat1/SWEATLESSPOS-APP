<?php

namespace App\Livewire\Website;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class Products extends Component
{
    use WithPagination;

    public $category_id = null;
    public $search = '';
    public $sortBy = 'name';
    public $heroBackgroundUrl = '';

    protected $paginationTheme = 'bootstrap';

    public $categories = [];

    public function mount()
    {
        $this->categories = Category::where('store_id', 1)->get();

        // Build background URL
        $productsBackground = WebsiteSetting::get('products_background_image');
        $this->heroBackgroundUrl = $productsBackground
            ? 'url(' . asset('storage/' . $productsBackground) . ')'
            : 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
    }

    public function render()
    {
        $query = Product::where('store_id', 1);

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $products = $query->orderBy($this->sortBy)->paginate(12);

        return view('livewire.website.products', [
            'products' => $products,
            'categories' => $this->categories,
        ]);
    }
}
