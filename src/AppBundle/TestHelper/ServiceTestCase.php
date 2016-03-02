<?php
namespace AppBundle\TestHelper;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceTestCase extends KernelTestCase
{
    use ContainerTrait;

    public function setUp()
    {
        parent::setUp();
        static::bootKernel();
    }
    
    protected function crawler(Response $resp, $type = 'text/html')
    {
        $c = new Crawler;
        $c->addContent($resp->getContent(), $type);
        return $c;
    }

    protected function createForm($type, $data = null, $options = array())
    {
        return $this->getContainer()->get('form.factory')->create($type, $data, $options);
    }
}
