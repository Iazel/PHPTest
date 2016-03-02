<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadProductData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;
        $this->tag_manager = $this->container->get('fpn_tag.tag_manager');
        $image = $this->container->getParameter('product_image_source')
            . DIRECTORY_SEPARATOR . 'image.jpg';

        $this
            ->new_prod('MostRecent', $image, '2015-01-10 23:59', array())
            ->new_prod('Test2', $image, '2015-01-04', array('tag1', 'tag2'))
            ->new_prod('Test3', $image, '2015-01-08', array('tag3', 'tag1'))
            ->new_prod('Test4', $image, '2015-01-01 00:12', array('tag2'))
            ->new_prod('Test5', $image, '2015-01-02', array('tag2'))
            ->new_prod('Test6', $image, '2015-01-01 00:14', array('tag3'))
            ;

        $manager->flush();
    }

    protected function new_prod($name, $image, $created_at, $tags)
    {
        foreach($tags as &$t)
            $t = $this->new_tag($t);

        $p = new Product();
        $p->setName($name)
            ->setDesc('Lorem ipsum dolor sit amet')
            ->setImageFile(new File($image, false))
            ->setCreatedAt(new \DateTime($created_at))
            ->setTags($tags)
            ;

        $this->em->persist($p);
        return $this;
    }

    protected function new_tag($name)
    {
        return $this->tag_manager->loadOrCreateTag($name);
    }
}
