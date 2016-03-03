<?php
namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Product;

trait CoreProductTrait {
    private function core_prod($name, $created_at, $updated_at = null, $persist = true)
    {
        $cad = new \DateTime($created_at);
        $uad = ($updated_at === null) ? $cad : new \DateTime($updated_at);

        $p = new Product;
        $p->setName($name)
            ->setCreatedAt($cad)
            ->setUpdatedAt($uad)
            ;

        if($persist)
            $this->em->persist($p);
        return $p;
    }
}
