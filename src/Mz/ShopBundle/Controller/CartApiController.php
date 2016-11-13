<?php

namespace Mz\ShopBundle\Controller;

use Mz\ShopBundle\Entity\Cart;
use Mz\ShopBundle\Entity\Product;
use Mz\ShopBundle\Service\CartService;
use Mz\ShopBundle\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;

class CartApiController extends Controller
{
    /**
     * @DI\Inject("mz.shop.cart")
     * @var CartService
     */
    protected $cartService;

    /**
     * @DI\Inject("mz.shop.product")
     * @var ProductService
     */
    protected $productService;

    /**
     * @Route("/cart/{cartId}", name="mz_shop_cart", requirements={"cartId": "\d+"})
     * @Method({"GET","HEAD"})
     */
    public function listAction($cartId)
    {
        $response = new JsonResponse();
        $cart = $this->cartService->getCartById($cartId);
        if (!$cart instanceof Cart) {
            return \ApiControllerHelper::createErrorResponse(404, "Cart not found");
        }

        $response->setData($cart);
        return $response;
    }

    /**
     * @Route("/cart", name="mz_shop_cart_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $response = new JsonResponse();

        $cart = new Cart();
        $this->cartService->saveCart($cart);
        $response->setData($cart);

        return $response;
    }

    /**
     * @Route("/cart/{cartId}", name="mz_shop_cart_delete", requirements={"cartId": "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($cartId)
    {
        $response = new JsonResponse();

        $cart = $this->cartService->getCartById($cartId);
        if (!$cart instanceof Cart) {
            return \ApiControllerHelper::createErrorResponse(404, "Cart not found");
        }
        $this->cartService->removeCart($cart);
        return $response;
    }


    /**
     * @Route("/cart/{cartId}/product/{productId}", name="mz_shop_cart_product_remove", requirements={"cartId": "\d+", "productId": "\d+"})
     * @Method({"DELETE"})
     */
    public function removeProductAction($cartId, $productId)
    {
        $response = new JsonResponse();

        $product = $this->productService->getProductById($productId);
        $cart = $this->cartService->getCartById($cartId);

        if (!$cart instanceof Cart) {
            return \ApiControllerHelper::createErrorResponse(500, "Cart not found.");
        }
        if (!$product instanceof Product) {
            return \ApiControllerHelper::createErrorResponse(500, "Product not found.");
        }

        $cart = $this->cartService->removeProductFromCart($cart, $product);
        $response->setData($cart);
        return $response;
    }


    /**
     * @Route("/cart/{cartId}/product/{productId}", name="mz_shop_cart_product_add", requirements={"cartId": "\d+", "productId": "\d+"})
     * @Method({"PUT"})
     */
    public function addProductAction($cartId, $productId)
    {
        $response = new JsonResponse();

        $product = $this->productService->getProductById($productId);
        $cart = $this->cartService->getCartById($cartId);

        if (!$cart instanceof Cart) {
            return \ApiControllerHelper::createErrorResponse(500, "Cart not found.");
        }
        if (!$product instanceof Product) {
            return \ApiControllerHelper::createErrorResponse(500, "Product not found.");
        }

        if ($this->cartService->checkProductInCart($cart, $product)) {
            return \ApiControllerHelper::createErrorResponse(500, "Product already exists in cart.");
        }

        $cart = $this->cartService->addProductToCart($cart, $product);
        $response->setData($cart);

        return $response;
    }
}
