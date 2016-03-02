<?php
namespace AppBundle\ViewModel;

use AppBundle\Entity\Product;
use AppBundle\TestHelper\ServiceTestCase;
use Symfony\Component\HttpFoundation\File\File;

class ProductListTest extends ServiceTestCase
{
    public function testRender()
    {
        $products = $this->getProducts(10);
        $crawler = $this->crawler( $this->subj()->render($products) );

        $this->assertCount(1, $crawler->filter('.products-list'),
            'It should render a products list');

        $prods = $crawler->filter('.product');
        $this->assertCount(10, $prods, 'It should render all products');

        $first = $prods->eq(0);
        $this->assertSame('Prod0',
            $first->filter('.product-name')->text(),
            'It should order by creation date desc');

        $this->assertStringEndsWith('/thumbs/image.jpg',
            $first->filter('.product-image')->attr('src'),
            'It should render a thumbnail');

        $this->assertSame('2015-01-01, 00:00:00',
            trim($first->filter('.product-created-at')->text()),
            'It should render the created date')
            ;
    }


    private function subj()
    {
        return $this->getContainer()->get('vm.product_list');
    }

    public function getProducts($n)
    {
        $prods = array();
        for($i = 0; $i < $n; ++$i) {
            $p = new Product;
            $p->setName('Prod' . $i)
                ->setImageName('image.jpg')
                ->setCreatedAt(new \DateTime('2015-01-01'))
                ;
            $prods[] = $p;
        }
        return $prods;
    }
}
