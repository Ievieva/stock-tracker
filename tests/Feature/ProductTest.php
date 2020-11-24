<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function checks_stock_for_products_at_retailers()
    {
        $switch = Product::create(['name' => 'Nintendo Switch']);
        $bestBuy = Retailer::create(['name' => 'Best Buy']);

        $this->assertFalse($switch->inStock());

        $stock = new Stock([
            'price' => 10000,
            'url' => 'http://smth.com',
            'sku' => '12345',
            'in_stock' => false
        ]);

        $bestBuy->addStock($switch, $stock);

        $this->assertTrue($switch->inStock());
    }
}
