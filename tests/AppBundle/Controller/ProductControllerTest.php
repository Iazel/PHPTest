<?php
namespace AppBundle\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductControllerTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testCreateAction_OkFlow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/product/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('product[save]')->form(array(
            'product[name]' => 'Test',
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
        // hide the do logic, so that we can focus on tests
        $dest = $this->doImageUpload($client, 'image.jpg');

        $this->assertTrue( $client->getResponse()->isRedirect() );
        $uploads = glob("$dest/*");
        $this->assertCount(1, $uploads);
        $this->assertStringEndsWith('.jpg', $uploads[0]);
        $this->assertNotSame('image.jpg', basename($uploads[0]));
    }

    private function doImageUpload($client, $file)
    {
        $cont = $client->getContainer();
        $dest = $cont->getParameter('product_image_destination');
        $image = $cont->getParameter('kernel.root_dir') .'/../tests/data/' . $file;
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
            'product[name]' => 'Test'
        ));
    }

    private function ensureCleanDB()
    {
        $purger = new ORMPurger($this->getContainer()->get('doctrine')->getManager());
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
    }

    private function ensureCleanDir($dir)
    {
        foreach(glob($dir . '/*') as $file)
            unlink($file);
    }
}
