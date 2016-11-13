<?php

namespace Mz\ShopBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mz\ShopBundle\Entity\Product;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * @Service("mz.shop.product")
 */
class ProductService
{
    /**
     * @var EntityManager
     */
    private $em;

    /** @var int  */
    private $perPage = 50;


    /**
     * @InjectParams({
     *     "em"                     = @Inject("doctrine.orm.entity_manager"),
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $page
     * @return array
     */
    public function getProducts($page = 1)
    {
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->em->createQueryBuilder();
        $builder->select('COUNT(p) AS pcount')
            ->from('MzShopBundle:Product', 'p');
        $count = $builder->getQuery()->getSingleScalarResult();

        $builder = $this->em->createQueryBuilder();
        $builder->select('p')
            ->from('MzShopBundle:Product', 'p')
            ->setFirstResult($this->perPage * ($page -1))
            ->setMaxResults($this->perPage);

        $result = array(
            'products' =>  $builder->getQuery()->getResult(),
            'pager' => array(
                'count' => $count,
                'perPage' => $this->perPage
            )
        );
        return $result;
    }

    public function getProductById($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('p')
            ->from('MzShopBundle:Product', 'p')
            ->where('p.id = :id')->setParameter('id', $id);
        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function saveProduct(Product $product)
    {

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }

}