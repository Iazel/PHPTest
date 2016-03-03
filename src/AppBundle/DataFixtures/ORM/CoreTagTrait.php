<?php
namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Tag;

trait CoreTagTrait {
    private function core_tag($name)
    {
        $t = new Tag($name);
        $this->em->persist($t);
        return $t;
    }
}
