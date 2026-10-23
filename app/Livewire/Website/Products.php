<?php

namespace App\Livewire\Website;

use App\Models\Category;
use App\Models\Product;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.website')]
class Products extends Component
{
    private const PER_PAGE = 12;
    private const SORTS = [
        'name' => ['name', 'asc'],
        'price_asc' => ['price', 'asc'],
        'price_desc' => ['price', 'desc'],
        'newest' => ['id', 'desc'],
    ];

    #[Url(as: 'category', except: '')]
    public $category_id = '';

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'sort', except: 'name')]
    public string $sortBy = 'name';

    public int $limit = self::PER_PAGE;

    public function updated($property): void
    {
        if (in_array($property, ['category_id', 'search', 'sortBy'], true)) {
            $this->limit = self::PER_PAGE;
        }
    }

    public function setCategory($id): void
    {
        $this->category_id = (string) $id;
        $this->limit = self::PER_PAGE;
    }

    public function loadMore(): void
    {
        $this->limit += self::PER_PAGE;
    }

    public function render()
    {
        [$column, $direction] = self::SORTS[$this->sortBy] ?? self::SORTS['name'];

        $query = Product::public()->with('category:id,name');

        if ($this->category_id !== '' && $this->category_id !== null) {
            $query->where('category_id', (int) $this->category_id);
        }

        if (trim($this->search) !== '') {
            $term = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], trim($this->search));
            $query->where('name', 'like', '%' . $term . '%');
        }

        $products = $query->orderBy($column, $direction)->limit($this->limit + 1)->get();
        $hasMore = $products->count() > $this->limit;

        return view('livewire.website.products', [
            'products' => $products->take($this->limit),
            'hasMore' => $hasMore,
            'categories' => Category::where('store_id', Product::WEBSITE_STORE_ID)->orderBy('name')->get(['id', 'name']),
            'heroBackgroundUrl' => WebsiteSetting::backgroundCss('products_background_image', 'linear-gradient(135deg, #ec4899, #f59e0b)'),
        ])->layoutData([
            'title' => 'Products',
            'description' => 'Browse our products and find what you need.',
        ]);
    }
}
