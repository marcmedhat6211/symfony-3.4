<?php

namespace ProductBundle\Service;

use ProductBundle\Entity\Category;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class CategoryCreator {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createCategory($title) {
        $category = new Category();
        $category->setTitle($title);
        $this->em->persist($category);
        $this->em->flush();
    }

}