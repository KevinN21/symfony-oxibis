<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DemoControllerTest extends WebTestCase
{
    // public function testSomething(): void
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'Hello World');
    // }

    public function testDemo2(): void
    {
        $client = static::createClient();
        $client->request('GET', '/demo2');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Demo2');
    }

    public function testDemo4(): void
    {
        $client = static::createClient();
        $client->request('GET', '/demo4');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertResponseHasHeader('X-Token');
        $this->assertTrue(
            $response->headers->contains('X-Token', md5('hello')));
    }
}
