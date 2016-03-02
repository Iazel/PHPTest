<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadTagsData implements FixtureInterface
{
    private $em;

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;
        
        $this
            ->new_tag('test1')
            ->new_tag('test2')
            ->new_tag('test3')
            ;

        $manager->flush();
    }

    private function new_tag($name)
    {
        $tag = new Tag($name);

        $this->em->persist($tag);
        return $this;
    }
}
