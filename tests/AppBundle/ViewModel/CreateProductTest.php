<?php
namespace Tests\AppBundle\ViewModel;

use AppBundle\ViewModel\CreateProduct;
use AppBundle\TestHelper\ServiceTestCase;
use AppBundle\FormType\ProductType;
use AppBundle\Entity\Product;

class CreateProductTest extends ServiceTestCase
{
    public function testRender()
    {
        $resp = $this->subj()->render( $this->getForm() );
        $c = $this->crawler($resp);

        $this->assertCount(1, $c->filter('[name="product[name]"]'));
        $this->assertCount(1, $c->filter('[name="product[desc]"]'));
    }

    private function getForm()
    {
        return $this->createForm(ProductType::class, new Product);
    }

    private function subj()
    {
        return $this->getContainer()->get('vm.create_product');
    }
}
