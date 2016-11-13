<?php

namespace Mz\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartItem
 *
 * @ORM\Table(name="cart_item", indexes={@ORM\Index(name="fk_cart_item_product_idx", columns={"product"}), @ORM\Index(name="fk_cart_item_cart1_idx", columns={"cart"})})
 * @ORM\Entity
 */
class CartItem implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Cart
     *
     * @ORM\ManyToOne(targetEntity="Cart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cart", referencedColumnName="id")
     * })
     */
    private $cart;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    private $product;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cart
     *
     * @param \Mz\ShopBundle\Entity\Cart $cart
     *
     * @return CartItem
     */
    public function setCart(\Mz\ShopBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \Mz\ShopBundle\Entity\Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set product
     *
     * @param \Mz\ShopBundle\Entity\Product $product
     *
     * @return CartItem
     */
    public function setProduct(\Mz\ShopBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Mz\ShopBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {

        return array(
            'id' => $this->getId(),
            'productId' => $this->getProduct()->getId(),
            'productTitle' => $this->getProduct()->getTitle(),
            'productPrice' => $this->getProduct()->getPrice()
        );
    }
}
