<?php
namespace AppBundle\ViewModel;

use AppBundle\Entity\Product;
use AppBundle\Form\Type\ProductType;
use AppBundle\TestHelper\ServiceTestCase;

class CreateProductTest extends ServiceTestCase
{
    /**
     * @group fast
     */
    public function testRender()
    {
        $resp = $this->subj()->render( $this->getForm() );
        $c = $this->crawler($resp);

        $this->assertCount(1, $c->filter('[name="product[name]"]'), 'Need a product name');
        $this->assertCount(1, $c->filter('[name="product[desc]"]'), 'Need a product desc');
        $this->assertCount(1, $c->filter('[name="product[tags]"]'), 'Need product tags');
        $this->assertCount(1, $c->filter('[name="product[image_file][file]"]'), 'Need an image file');
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
