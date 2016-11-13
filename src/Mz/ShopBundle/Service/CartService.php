<?php

namespace Mz\ShopBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mz\ShopBundle\Entity\Cart;
use Mz\ShopBundle\Entity\CartItem;
use Mz\ShopBundle\Entity\Product;
use Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\AclBundle\Entity\Car;


/**
 * @Service("mz.shop.cart")
 */
class CartService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @InjectParams({
     *     "em"                     = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $cartId
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCartById($cartId)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('c, ci, p')
            ->from('MzShopBundle:Cart', 'c')
            ->leftJoin('c.items', 'ci')
            ->leftJoin('ci.product', 'p')
            ->where('c.id = :cartId')
            ->setParameter('cartId', $cartId);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Cart $cart
     * @param Product $product
     * @return Cart
     */
    public function addProductToCart(Cart $cart, Product $product)
    {
        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cart->addItem($cartItem);

        $this->em->persist($cart);
        $this->em->flush();

        return $cart;
    }

    /**
     * @param Cart $cart
     * @param Product $product
     * @return Cart
     */
    public function removeProductFromCart(Cart $cart, Product $product)
    {
        /** @var CartItem $cartItem */
        foreach ($cart->getItems() as $cartItem) {
            if ($cartItem->getProduct()->getId() == $product->getId()) {
                $cart->removeItem($cartItem);
                $this->em->persist($cart);
                $this->em->flush();
                return $cart;
            }
        }
        return $cart;
    }

    /**
     * @param Cart $cart
     * @param Product $product
     * @return bool
     */
    public function checkProductInCart(Cart $cart, Product $product)
    {
        /** @var CartItem $cartItem */
        foreach ($cart->getItems() as $cartItem) {
            if ($cartItem->getProduct()->getId() == $product->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Cart $cart
     * @return Cart
     */
    public function saveCart(Cart $cart)
    {
        $this->em->persist($cart);
        $this->em->flush();

        return $cart;
    }

    /**
     * @param Cart $cart
     */
    public function removeCart(Cart $cart)
    {
        $this->em->remove($cart);
        $this->em->flush();
    }

}