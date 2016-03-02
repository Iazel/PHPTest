<?php
namespace AppBundle\Controller;

use AppBundle\TestHelper\DbTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductControllerTest extends WebTestCase
{
    use DbTrait;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::setupDatabase();
    }

    public function testCreateAction_OkFlow()
    {
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
    }

    public function testCreateAction_InvalidFlow()
    {
        $client = static::createClient();
        // Empty request
        $crawler = $client->submit( $this->getCreateForm($client, array()) );
        $this->assertTrue(
            $crawler->filter('.has-error')->count() > 0,
            'It should render some errors'
        );
    }

    public function testCreateAction_ImageUpload()
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
