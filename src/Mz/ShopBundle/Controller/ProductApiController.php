<?php

namespace Mz\ShopBundle\Controller;

use Mz\ShopBundle\Entity\Product;
use Mz\ShopBundle\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;

class ProductApiController extends Controller
{
    /**
     * @DI\Inject("mz.shop.product")
     * @var ProductService
     */
    protected $productService;

    /**
     * @Route("/products/{page}", name="mz_shop_products", requirements={"page": "\d+"})
     * @Method({"GET","HEAD"})
     */
    public function listAction($page = 1)
    {
        $response = new JsonResponse();
        $response->setData($this->productService->getProducts($page));
        return $response;
    }

    /**
     * @Route("/products/{id}", name="mz_shop_product_delete", requirements={"id": "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $response = new JsonResponse();
        $product = $this->productService->getProductById($id);
        if (!$product instanceof Product) {
            return \ApiControllerHelper::createErrorResponse(404, "Product not found");
        }

        $this->productService->removeProduct($product);

        return $response;
    }

    /**
     * @Route("/products/{id}", name="mz_shop_product_update", requirements={"id": "\d+"})
     * @Method({"PUT"})
     */
    public function updateAction(Request $request, $id)
    {
        $response = new JsonResponse();
        $product = $this->productService->getProductById($id);
        if (!$product instanceof Product) {
            return \ApiControllerHelper::createErrorResponse(404, "Product not found");
        }

        if ($request->request->has('title')) {
            $product->setTitle($request->request->get('title'));
        }
        if ($request->request->has('price')) {
            $product->setPrice($request->request->getInt('price'));
        }

        $validator = $this->get('validator');
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return \ApiControllerHelper::createErrorResponse(500, "Product validation error. ".(string)$errors);
        }

        $this->productService->saveProduct($product);
        $response->setData(array('id' => $product->getId()));
        $this->productService->saveProduct($product);
        $response->setData($product);

        return $response;
    }

    /**
     * @Route("/products", name="mz_shop_product_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $response = new JsonResponse();
        $validator = $this->get('validator');
        $product = new Product();
        $product->setTitle($request->request->get('title'));
        $product->setPrice($request->request->getInt('price'));

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return \ApiControllerHelper::createErrorResponse(500, "Product validation error. ".(string)$errors);

        }

        $this->productService->saveProduct($product);
        $response->setData($product);

        return $response;
    }

}
