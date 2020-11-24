<?php

namespace Tests\Clients;

use App\Clients\BestBuy;
use App\Models\Stock;
use Database\Seeders\RetailerWithProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function tracks_a_product()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $stock = tap(Stock::first())->update([
            'sku' => '6257139',
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-lite-turquoise/6257139.p?skuId=6257139'
        ]);

        try {
            (new BestBuy())->checkAvailability($stock);
        } catch (\Exception $e) {
            $this->fail('Failed to track the BestBuy API properly.' . $e->getMessage());
        }

        $this->assertTrue(true);
    }

    /** @test */
    function creates_the_proper_stock_status_response()
    {
        Http::fake(fn() => ['salePrice' => 299.99, 'onlineAvailability' => true]);

        $stockStatus = (new BestBuy())->checkAvailability(new Stock());

        $this->assertEquals(29999, $stockStatus->price);
        $this->assertEquals(true, $stockStatus->available);
    }
}
