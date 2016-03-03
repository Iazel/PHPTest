<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

abstract class Base implements FixtureInterface
{
    protected $em;

    abstract protected function genData();

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;
        $this->genData();
        $manager->flush();
    }

    protected function persist($obj)
    {
        $this->em->persist($obj);
        return $this;
    }
}
