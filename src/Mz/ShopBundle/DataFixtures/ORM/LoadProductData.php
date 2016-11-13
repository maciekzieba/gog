<?php

namespace Mz\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mz\ShopBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductData implements FixtureInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $row) {
            $product = new Product();
            $product
                ->setId($row['id'])
                ->setTitle($row['title'])
                ->setPrice($row['price']);
            $manager->persist($product);
            $metadata = $manager->getClassMetaData(get_class($product));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        }

        $manager->flush();
    }

    private function getData()
    {
        return
            [
                [
                    'id' => 1,
                    'title' => 'Fallout',
                    'price' => 1.99,
                ],
                [
                    'id' => 2,
                    'title' => 'Don’t Starve',
                    'price' => 2.99,
                ],
                [
                    'id' => 3,
                    'title' => 'Baldur’s Gate',
                    'price' => 3.99,
                ],
                [
                    'id' => 4,
                    'title' => 'Icewind Dale',
                    'price' => 4.99,
                ],
                [
                    'id' => 5,
                    'title' => 'Bloodborne',
                    'price' => 5.99,
                ],
            ];
    }


    public function getOrder()
    {
        return 1;
    }
}