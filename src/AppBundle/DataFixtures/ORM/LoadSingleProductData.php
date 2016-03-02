<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Tag;
use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadSingleProductData implements FixtureInterface
{
    private $em;

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;
        $this->genData();
        $manager->flush();
    }

    protected function genData()
    {
        $this->new_prod('Test1', '2015-01-01', array('test1', 'test2'));
    }

    protected function new_prod($name, $date, $tags)
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
