<?php

namespace Tests\ShopBundle\Controller;

use Mz\ShopBundle\Entity\Cart;
use Mz\ShopBundle\Service\CartService;
use Mz\ShopBundle\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartServiceTest extends WebTestCase
{
    public function test()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        /** @var CartService $cartService */
        $cartService = $container->get('mz.shop.cart');

        /** @var ProductService $productService */
        $productService = $container->get('mz.shop.product');

        // Create cart
        $cart = new Cart();
        $cartService->saveCart($cart);
        $this->assertGreaterThan(0, $cart->getId());

        // get cart by id
        /** @var Cart $cart */
        $cart = $cartService->getCartById($cart->getId());
        $this->assertInstanceOf(Cart::class, $cart);

        // add product to cart
        $cartService->addProductToCart($cart, $productService->getProductById(1));
        $this->assertGreaterThan(0, $cart->getItemsCount());

        // is product in cart
        $result = $cartService->checkProductInCart($cart, $productService->getProductById(1));
        $this->assertTrue($result);

        // remove product from cart
        $cartService->removeProductFromCart($cart, $productService->getProductById(1));
        $this->assertEquals(0, $cart->getItemsCount());

        // remove cart
        $cartId = $cart->getId();
        $cartService->removeCart($cart);
        $cart = $cartService->getCartById($cartId);
        $this->assertNull($cart);

    }


}
