<?php

namespace App\Clients;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    use HasFactory;

    public function checkAvailability(Stock $stock): ClientResponse
    {
        $results = new Product([
            'onlineAvailability' => true,
            'salePrice' => 299.99
        ]);

        return new ClientResponse(
            $results['onlineAvailability'],
            (int)($results['salePrice'] * 100)
        );
    }

    protected function endpoint($sku): string
    {
        $key = config('services.clients.bestBuy.key');
        return "https://www.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }
}
