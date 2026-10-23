<?php

namespace App\Livewire\Website;

use App\Models\Product;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class ProductDetail extends Component
{
    public int $productId;

    public function mount($id): void
    {
        $this->productId = Product::public()->findOrFail($id)->id;
    }

    public function render()
    {
        $product = Product::public()->with('category:id,name')->findOrFail($this->productId);

        $related = $product->category_id
            ? Product::public()->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)->limit(4)->get()
            : collect();

        $image = $product->image && ! str_ends_with($product->image, 'noimage.png')
            ? asset('storage/' . $product->image)
            : null;

        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags((string) $product->description),
            'image' => $image,
            'offers' => [
                '@type' => 'Offer',
                'price' => number_format((float) $product->price, 2, '.', ''),
                'priceCurrency' => config('app.currency_code', 'GHS'),
                'availability' => $product->stock_status === 'out'
                    ? 'https://schema.org/OutOfStock'
                    : 'https://schema.org/InStock',
            ],
        ];

        return view('livewire.website.product-detail', [
            'product' => $product,
            'relatedProducts' => $related,
            'image' => $image,
            'phone' => WebsiteSetting::get('phone'),
            'whatsapp' => preg_replace('/\D+/', '', (string) WebsiteSetting::get('whatsapp_number', '')),
        ])->layoutData([
            'title' => $product->name,
            'description' => strip_tags((string) $product->description) ?: 'Details for ' . $product->name,
            'image' => $image,
            'jsonLd' => $jsonLd,
        ]);
    }
}
