<?php
namespace AppBundle\DataFixtures\ORM;

class LoadSingleProductData extends Base
{
    use CoreTagTrait;
    use CoreProductTrait;

    protected function genData()
    {
        $this->new_prod('Test1', '2015-01-01', array('test1', 'test2'));
    }

    protected function new_prod($name, $date, $tags)
    {
        $p = $this->core_prod($name, $date);

        foreach($tags as $tag)
            $p->addTag( $this->core_tag($tag) );

        $this->em->persist($p);
        return $this;
    }
}
