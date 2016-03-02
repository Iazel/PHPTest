<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\TestHelper\DbTrait;
use AppBundle\TestHelper\ContainerTrait;
use AppBundle\DataFixtures\ORM\LoadSearchByTagsData;
use AppBundle\DataFixtures\ORM\LoadMostRecentProductData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerListTest extends WebTestCase
{
    use DbTrait;
    use ContainerTrait;

    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testOrderByMostRecent()
    {
        $this->populateDatabaseWith(new LoadMostRecentProductData);

        $crawler = $this->make_request();
        $prods = $crawler->filter('.product-name');

        $this->assertCount(3, $prods,
            'It should render all products');

        $order = ['MostRecent', 'MostRecent 2', 'LeastRecent'];
        foreach($order as $i => $name)
        {
            $this->assertProductName($prods, $i, $name,
                'It should order the list by created date desc');
        }
    }

    public function testSearchByTags()
    {
        $this->populateDatabaseWith(new LoadSearchByTagsData);

        $this->assertSearchResults('test', ['Test3', 'Test2', 'Test1']);
        $this->assertSearchResults('test1', ['Test2', 'Test1']);
        $this->assertSearchResults('xyz', ['Test3']);
        $this->assertSearchResults('zyx, xyz, xxx', ['Test3', 'Test2']);
        $this->assertSearchResults('xxx', []);

        return $this;
    }

    private function make_request($q = '')
    {
        $url = '/product/list';
        if($q)
            $url .= '?q=' . $q;

        return $this->client->request('GET', $url);
    }

    private function assertProductName($prods, $i, $name, $msg)
    {
        $this->assertSame($name,
            trim($prods->eq($i)->text()),
            $msg);
    }

    private function assertSearchResults($tags, $expects)
    {
        $crawler = $this->make_request($tags);
        $prods = $crawler->filter('.product-name');

        $this->assertCount(count($expects), $prods,
            'It should render the correct number of products');

        foreach($expects as $i => $name) {
            $this->assertProductName($prods, $i, $name,
                'It should render the correct products');
        }
    }
}
