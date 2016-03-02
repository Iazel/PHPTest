<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadMostRecentProductData implements FixtureInterface
{
    private $em;

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;
        
        $this
            ->new_prod('LeastRecent', '2015-01-01')
            ->new_prod('MostRecent 2', '2016-01-01 00:14:00')
            ->new_prod('MostRecent', '2016-01-01 00:14:30')
            ;

        $manager->flush();
    }

    private function new_prod($name, $date)
    {
        $p = new Product();
        $p->setName($name)->setCreatedAt( new \DateTime($date) );
        $this->em->persist($p);
        
        return $this;
    }
}
