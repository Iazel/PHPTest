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
            'It should render the created date');

        $this->assertCount(1,
            $crawler->filter('form.search'),
            'It should render a form search');
        
        $this->assertSame('',
            $crawler->filter('form.search [name="q"]')->attr('value'),
            'Default search term value should be empty');

        $crawler = $this->crawler( $this->subj()->render([], 'test') );
        $this->assertSame('test',
            $crawler->filter('form.search [name="q"]')->attr('value'),
            'It should set the right term');

        $nf = $crawler->filter('td.nothing-found');
        $this->assertCount(1,
            $nf,
            'It should render a message when there\'s no products');

        $span = $crawler->filter('.products-list thead > th')->count();
        $this->assertSame($span,
            (int) $nf->eq(0)->attr('colspan'),
            'Nothing found should have a colspan equal to the headers');
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
