<?php

namespace Tests\ShopBundle\Controller;

use Mz\ShopBundle\Service\ProductService;
use Mz\ShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductServiceTest extends WebTestCase
{
    public function test()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        /** @var ProductService $productService */
        $productService = $container->get('mz.shop.product');
        /** @var Product $product */
        $product = $productService->getProductById(1);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($product->getId(), 1);

        $product = new Product();
        $product->setPrice(99);
        $product->setTitle('Test Name');
        $productService->saveProduct($product);
        $this->assertGreaterThan(0, $product->getId());

        $productId = $product->getId();
        $productService->removeProduct($product);
        $product = $productService->getProductById($productId);
        $this->assertNull($product);
    }


}
