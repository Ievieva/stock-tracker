<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Retailer;
use App\Models\Stock;
use Database\Factories\UserFactory;
use Facades\App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class RetailerWithProductSeeder extends Seeder
{
    use HasFactory;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $switch = Product::create(['name' => 'Nintendo Switch']);

        $bestBuy = Retailer::create(['name' => 'Best Buy']);

        $bestBuy->addStock($switch, new Stock([
            'price' => 10000,
            'url' => 'http://smth.com',
            'sku' => '12345',
            'in_stock' => false
        ]));

        new UserFactory(['email' => 'ieva@example.com']);
    }
}
