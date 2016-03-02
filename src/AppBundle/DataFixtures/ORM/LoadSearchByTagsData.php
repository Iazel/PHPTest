<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadSearchByTagsData extends LoadSingleProductData
{
    protected function genData()
    {
        $this
            ->new_prod('Test1', '2015-01-01', array('test1'))
            ->new_prod('Test2', '2015-01-02', array('test1', 'test2', 'zyx'))
            ->new_prod('Test3', '2015-01-03', array('test3', 'xyz'))
            ;
    }
}
