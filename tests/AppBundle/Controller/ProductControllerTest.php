<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testCreateAction_OkFlow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('product[save]')->form(array(
            'product[name]' => 'Test',
            'product[desc]' => 'Lorem ipsum dolor sit amet',
        ));
        $client->submit($form);
        $this->assertTrue( $client->getResponse()->isRedirect() );
    }
}
