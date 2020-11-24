<?php


namespace App\UseCases;


use App\Clients\ClientResponse;
use App\Models\History;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\ImportantStockUpdate;

class TrackStock
{
    protected Stock $stock;
    protected ClientResponse $response;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function handle()
    {
        $this->checkAvailability();
        $this->notifyUser();
        $this->refreshStock();
        $this->recordToHistory();
    }

    protected function checkAvailability()
    {
        $this->response = $this->stock->retailer
            ->client()
            ->checkAvailability($this->stock);
    }

    protected function notifyUser()
    {
        if ($this->isNowInStock()) {
            User::first()->notify(
                new ImportantStockUpdate($this->stock));
        }
    }

    protected function refreshStock()
    {
        $this->stock->update([
            'in_stock' => $this->response->available,
            'price' => $this->response->price
        ]);
    }

    protected function recordToHistory()
    {
        History::create([
            'price' => $this->stock->price,
            'in_stock' => $this->stock->in_stock,
            'product_id' => $this->stock->product_id,
            'stock_id' => $this->stock->id
        ]);
    }

    protected function isNowInStock(): bool
    {
        return !$this->stock->in_stock && $this->response->available;
    }
}
