<?php
namespace AppBundle\Controller;

use AppBundle\TestHelper\DbTrait;
use AppBundle\TestHelper\ContainerTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductControllerCreateTest extends WebTestCase
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
        $uploads = glob("$dest/*");
        $this->assertCount(1, $uploads,
            'It should upload the image');
        $this->assertStringEndsWith('.jpg', $uploads[0]);
        $this->assertNotSame('image.jpg', basename($uploads[0]));
    }

    private function doImageUpload($client, $file)
    {
        $cont = $client->getContainer();
        $dest = $cont->getParameter('product_image_destination');
        $image = $cont->getParameter('product_image_source')
            . DIRECTORY_SEPARATOR . $file
            ;
        $this->ensureCleanDir($dest);

        $form = $this->getValidCreateForm($client);
        $form['product[image_file]']['file']->upload($image);
        $client->submit($form);

        return $dest;
    }

    private function getCreateForm($client, $data = array())
    {
        return $client
            ->request('GET', '/product/create')
            ->selectButton('product[save]')
            ->form($data);
    }

    private function getValidCreateForm($client)
    {
        return $this->getCreateForm($client, array(
            'product[name]' => 'Test',
            'product[tags]' => 'a,b,c',
        ));
    }

    private function ensureCleanDir($dir)
    {
        foreach(glob($dir . '/*') as $file)
            unlink($file);
    }
}
