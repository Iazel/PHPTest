<?php
namespace AppBundle\ViewModel;

use AppBundle\Entity\Product;
use AppBundle\Form\Type\ProductType;
use AppBundle\TestHelper\ServiceTestCase;

class ManageProductTest extends ServiceTestCase
{
    /**
     * @group fast
     */
    public function testRenderCreate()
    {
        $p = new Product;
        $resp = $this->subj()->renderCreate( $this->getForm($p) );
        $c = $this->crawler($resp);
        $this->assertFieldsExistance($c);
    }

    public function testRenderEdit()
    {
        $p = new Product;
        $p->setName('Test1')
            ->setCreatedAt(new \DateTime('2015-01-01'))
            ;

        $resp = $this->subj()->renderEdit(
            $this->getForm($p), $p->getCreatedAt()
        );
        $c = $this->crawler($resp);
        $this->assertFieldsExistance($c);

        $this->assertSame('2015-01-01, 00:00:00',
            $c->filter('.created-at')->text(),
            'It should render the create at value');
    }

    private function assertFieldsExistance($c)
    {
        $this->assertCount(1, $c->filter('[name="product[name]"]'),
            'It should render a product name');
        $this->assertCount(1, $c->filter('[name="product[desc]"]'),
            'It should render a product desc');
        $this->assertCount(1, $c->filter('[name="product[tags]"]'),
            'It should render product tags');
        $this->assertCount(1, $c->filter('[name="product[image_file][file]"]'),
            'It should render an image file');
    }
    private function getForm()
    {
        return $this->createForm(ProductType::class, new Product);
    }

    private function subj()
    {
        return $this->getContainer()->get('vm.manage_product');
    }
}
