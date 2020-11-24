<?php


namespace App\Clients;


use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Http;

class Target implements Client
{
    use HasFactory;

    public function checkAvailability(Stock $stock): ClientResponse
    {
        $results = Http::get('http://smth.test')->json();

        return new ClientResponse(
            $results['available'],
            $results['price']
        );
    }
}
