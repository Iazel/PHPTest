<?php
namespace AppBundle\DataFixtures\ORM;

class LoadSingleProductWithImageData extends LoadProductData
{
    protected function genData()
    {
        $image = $this->getTestImage();
        $this->new_prod('Test1', $image, '2015-01-01', array('test1'));
    }
}
