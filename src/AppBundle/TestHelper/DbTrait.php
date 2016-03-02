<?php
namespace AppBundle\TestHelper;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;

trait DbTrait
{
    public static function setupDatabase()
    {
        static::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    private function ensureCleanDB()
    {
        $this->getOrmPurger()->purge();
    }

    private function populateDatabase()
    {
        $c = $this->getContainer();
        $em = $c->get('doctrine')->getManager();
        $ep = $this->getOrmPurger();
        $fixtures_path = $c->getParameter('kernel.root_dir').'/../src/AppBundle/DataFixtures';

        $ld = new DataFixturesLoader($c);
        $ld->loadFromDirectory($fixtures_path);

        (new ORMExecutor($em, $ep))->execute( $ld->getFixtures() );
    }

    private function populateDatabaseWith($data)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        foreach($data as $m)
            $em->persist($m);

        $em->flush();
    }

    private function getOrmPurger()
    {
        $purger = new ORMPurger($this->getContainer()->get('doctrine')->getManager());
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        return $purger;
    }
}
