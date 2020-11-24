<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    protected $signature = 'track';

    protected $description = 'Track all product stock.';

    public function handle()
    {
        Product::all()
            ->tap(fn($products) => $this->output->progressStart($products->count()))
            ->each(function ($product) {
                $product->track();

                $this->output->progressAdvance();
            });

        $this->showResults();
    }

    protected function showResults(): void
    {
        $this->output->progressFinish();

        $data = Product::query()
            ->leftJoin('stock', 'stock.product_id', '=', 'products_id')
            ->get(['name', 'price', 'url', 'in_stock']);

        $this->table(
            ['Name', 'Price', 'Url', 'In Stock'],
            $data
        );
    }
}
