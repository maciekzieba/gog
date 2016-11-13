<?php

namespace Tests\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartApiControllerTest extends WebTestCase
{

    public function test()
    {
        $client = static::createClient();

        // Create cart
        $client->request('POST', '/api/cart', array(
        ));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $cartId = $data['id'];

        // Add product
        $client->request('PUT', "/api/cart/$cartId/product/1", array(
        ));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('itemsCount', $data);
        $this->assertEquals($data['itemsCount'], 1);

        // remove product
        $client->request('DELETE', "/api/cart/$cartId/product/1", array(
        ));

        $this->assertTrue($client->getResponse()->isSuccessful());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('itemsCount', $data);
        $this->assertEquals($data['itemsCount'], 0);

        // remove cart
        $client->request('DELETE', "/api/cart/$cartId", array(
        ));
        $this->assertTrue($client->getResponse()->isSuccessful());


    }

}
