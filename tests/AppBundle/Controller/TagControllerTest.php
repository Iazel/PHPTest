<?php
namespace AppBundle\Controller;

use AppBundle\TestHelper\DbTrait;
use AppBundle\TestHelper\ContainerTrait;
use AppBundle\DataFixtures\ORM\LoadTagsData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagControllerTest extends WebTestCase
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
    public function testOk()
    {
        $client = static::createClient();
        $this->populateDatabaseWith(new LoadTagsData);

        $client->request('GET', '/tags/search/tes');
        $resp = $client->getResponse();

        $this->assertTrue($resp->isSuccessful(), 'It should be successful');
        $this->assertSame('application/json', $resp->headers->get('Content-Type'));

        $data = json_decode($resp->getContent());
        $exp = array('test1','test2','test3');
        $this->assertSame($exp, $data);
    }
}
