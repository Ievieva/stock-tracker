<?php

namespace Tests;

use App\Clients\ClientResponse;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function mockClientRequest($available = true, $price = 29900)
    {
        ClientFactory::shouldReceive('make->checkAvailability')
            ->andReturn(new ClientResponse($available, $price));
    }
}
