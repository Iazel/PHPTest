<?php
namespace AppBundle\Controller;

use AppBundle\TestHelper\ProductTestHelperTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductControllerCreateTest extends WebTestCase
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
        $this->ensureCleanDB();
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('product[save]')->form(array(
            'product[name]' => 'Test',
            'product[tags]' => 'a,b,c',
        ));
        $crawler = $client->submit($form);
        $this->assertTrue(
            $client->getResponse()->isRedirect('/product/list'),
            'It should redirect to products list'
        );

        $c = $client->getContainer();
        $this->assertSame(1,
            $c->get('finder.product')->count(),
            'It should persist the product created');

        $this->assertSame(3,
            $c->get('finder.tag')->count(),
            'It should persist the tag inserted');
    }

    /**
     * @group fast
     */
    public function testInvalidFlow()
    {
        $client = static::createClient();
        // Empty request
        $crawler = $client->submit( $this->getCreateForm($client, array()) );
        $this->assertTrue(
            $crawler->filter('.has-error')->count() > 0,
            'It should render some errors'
        );
    }

    /**
     * @group fast
     */
    public function testImageUpload()
    {
        $client = static::createClient();
        $dest = $this->doImageUpload($client, 'image.jpg');

        $this->assertTrue( $client->getResponse()->isRedirect() );

        $uploads = glob("$dest/*.jpg");
        $this->assertCount(1, $uploads,
            'It should upload the image');
        $this->assertCount(1, glob("$dest/thumbs/*.jpg"),
            'It should generate the thumbanil');

        $this->assertNotSame('image.jpg', basename($uploads[0]));
    }

    private function doImageUpload($client, $file)
    {
        $cont = $client->getContainer();
        $dest = $this->getImageDestination();
        $image = $this->getTestImage($file);
        $this->ensureCleanDir($dest);

        $form = $this->getValidCreateForm($client);
        $this->uploadAndSubmit($client, $form, $image);

        return $dest;
    }

    private function getValidCreateForm($client)
    {
        return $this->getCreateForm($client, array(
            'product[name]' => 'Test',
            'product[tags]' => 'a,b,c',
        ));
    }

    private function getCreateForm($client, $data = array())
    {
        return $this->getForm($client, '/product/create', $data);
    }
}
