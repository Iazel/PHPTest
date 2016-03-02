<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Tag;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadSearchByTagsData implements FixtureInterface
{
    private $em;

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;

        $this
            ->new_prod('Test1', '2015-01-01', array('test1'))
            ->new_prod('Test2', '2015-01-02', array('test1', 'test2', 'zyx'))
            ->new_prod('Test3', '2015-01-03', array('test3', 'xyz'))
            ;

        $manager->flush();
    }

    private function new_prod($name, $date, $tags)
    {
        $p = new Product;
        $p->setName($name)->setCreatedAt( new \DateTime($date) );

        foreach($tags as $tag)
            $p->addTag( $this->new_tag($tag) );

        $this->em->persist($p);
        return $this;
    }

    private function new_tag($name)
    {
        $t = new Tag($name);
        $this->em->persist($t);

        return $t;
    }
}
