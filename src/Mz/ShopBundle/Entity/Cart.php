<?php

namespace Mz\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity
 */
class Cart implements \JsonSerializable
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart", cascade="persist", orphanRemoval=true)
     */
    private $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


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
     * Add item
     *
     * @param \Mz\ShopBundle\Entity\CartItem $item
     *
     * @return Cart
     */
    public function addItem(\Mz\ShopBundle\Entity\CartItem $item)
    {
        $item->setCart($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Mz\ShopBundle\Entity\CartItem $item
     */
    public function removeItem(\Mz\ShopBundle\Entity\CartItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getItemsCount()
    {
        return count($this->items);
    }

    /**
     * @return float|int
     */
    public function getItemsSum()
    {
        $sum = 0.0;
        /** @var CartItem $item */
        foreach ($this->items as $item) {
            $sum += $item->getProduct()->getPrice();
        }
        return $sum;
    }


    /**
     * @return array
     */
    function jsonSerialize()
    {
        $items = array();
        foreach ($this->items as $item) {
            $items[] = $item;
        }

        return array(
            'id' => $this->getId(),
            'items' => $items,
            'itemsSum' => $this->getItemsSum(),
            'itemsCount' => $this->getItemsCount()
        );
    }
}
