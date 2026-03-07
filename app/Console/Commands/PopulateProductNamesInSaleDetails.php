<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SaleDetail;
use App\Models\Product;

class PopulateProductNamesInSaleDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-product-names-in-sale-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate product_name field for existing sale details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to populate product names in sale_details...');

        $saleDetails = SaleDetail::whereNull('product_name')->get();
        $count = $saleDetails->count();

        if ($count === 0) {
            $this->info('All sale details already have product names.');
            return;
        }

        foreach ($saleDetails as $detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                $detail->update(['product_name' => $product->name]);
                $this->line("Updated Sale Detail #{$detail->id} with product: {$product->name}");
            } else {
                // If product is deleted, use 'Deleted Product' as fallback
                $detail->update(['product_name' => 'Deleted Product']);
                $this->line("Updated Sale Detail #{$detail->id} with: Deleted Product");
            }
        }

        $this->info("Completed! Updated {$count} sale details.");
    }
}
