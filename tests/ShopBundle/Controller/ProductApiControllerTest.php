<?php

namespace Tests\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductApiControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/api/products');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
        $this->assertTrue($client->getResponse()->isSuccessful());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('products', $data);
        $this->assertArrayHasKey('pager', $data);

        $this->assertGreaterThan(0, count($data['products']));

        foreach ($data['products'] as $row) {
            $this->assertArrayHasKey('id', $row);
            $this->assertArrayHasKey('title', $row);
            $this->assertArrayHasKey('price', $row);
        }
    }

    public function testCRUD()
    {
        $client = static::createClient();

        // Create product
        $client->request('POST', '/api/products', array(
            'title' => 'Test product',
            'price' => 100
        ));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $productId = $data['id'];

        // Update product
        $client->request('PUT', '/api/products/'.$productId, array(
            'title' => 'Test update',
            'price' => 200,
            'id' => $productId
        ));
        $this->assertTrue($client->getResponse()->isSuccessful());
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('price', $data);
        $this->assertEquals('Test update', $data['title']);
        $this->assertEquals(200, $data['price']);
        $this->assertEquals($productId, $data['id']);

        // Delete product
        $client->request('DELETE', '/api/products/'.$productId, array(
        ));

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}
