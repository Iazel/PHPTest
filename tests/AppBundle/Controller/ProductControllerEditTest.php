<?php
namespace AppBundle\Controller;

use AppBundle\TestHelper\DbTrait;
use AppBundle\TestHelper\ContainerTrait;
use AppBundle\DataFixtures\ORM\LoadSingleProductData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerEditTest extends WebTestCase
{
    use DbTrait;
    use ContainerTrait;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::setupDatabase();
    }

    /**
     * @group DB
     */
    public function testOkFlow()
    {
        $client = static::createClient();
        $this->populateDatabaseWith(new LoadSingleProductData);

        $crawler = $client->request('GET', '/product/1/edit');
        $inp_name = $crawler->filter('[name="product[name]"]');

        $this->assertCount(1, $inp_name,
            'It should render an input for name');

        $this->assertSame('Test1', $inp_name->attr('value'),
            'It should render the correct product values');

        $form = $crawler->selectButton('product[save]')->form();
        $form['product[name]'] = 'Edited';

        $crawler = $client->submit($form);
        $this->assertTrue( $client->getResponse()->isRedirect('/product/list'),
            'It should redirect to the product list');

        $prod = $this->getContainer()->get('finder.product')->find(1);
        $this->assertSame('Edited', $prod->getName(),
            'It should have persisted the changes');
    }
}
