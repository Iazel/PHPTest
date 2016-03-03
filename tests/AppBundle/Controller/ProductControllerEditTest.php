<?php
namespace AppBundle\Controller;

use AppBundle\TestHelper\ProductTestHelperTrait;
use AppBundle\DataFixtures\ORM\LoadSingleProductData;
use AppBundle\DataFixtures\ORM\LoadSingleProductWithImageData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerEditTest extends WebTestCase
{
    use ProductTestHelperTrait;

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

    public function testOldImageDeletion()
    {
        list($client, $dest) = $this->prepareForImageEdit();

        $old = glob("$dest/*.jpg")[0];
        $old_thumb = glob("$dest/thumbs/*.jpg")[0];
        $this->doUpload($client);

        $new = glob("$dest/*.jpg");
        $this->assertCount(1, $new,
            'Only one image should be present');

        $new_thumb = glob("$dest/thumbs/*.jpg");
        $this->assertCount(1, $new_thumb,
            'Only one thumbnail should be present');

        $this->assertNotSame($old, $new,
            'The image should be different');
        $this->assertNotSame($old_thumb, $new_thumb,
            'The thumbnail should be different');

    }

    private function prepareForImageEdit()
    {
        $client = static::createClient();
        $dest = $this->getImageDestination();

        $this->ensureCleanDir($dest);
        $this->populateDatabaseWith(new LoadSingleProductWithImageData);
        
        return array($client, $dest);
    }
 
    private function doUpload($client)
    {
        $image = $this->getTestImage();
        $form = $this->getForm($client, '/product/1/edit');
        $form['product[image_file]']['file']->upload($image);
        $client->submit($form);
        
        return $this;
    }
}
