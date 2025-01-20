<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MoneyControllerTest extends WebTestCase{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/money');

        self::assertResponseIsSuccessful();
    }
}
