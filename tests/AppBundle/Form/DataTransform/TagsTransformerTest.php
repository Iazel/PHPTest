<?php
namespace AppBundle\Form\DataTransform;

use AppBundle\Entity\Tag;
use AppBundle\TestHelper\DbTrait;
use AppBundle\TestHelper\ServiceTestCase;
use AppBundle\DataFixtures\ORM\LoadTagsData;

class TagsTransformerTest extends ServiceTestCase
{
    use DbTrait;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::setupDatabase();
    }

    /**
     * @group DB
     */
    public function testReverseTransform()
    {
        $this->ensureCleanDB();
        $this->populateDatabaseWith(new LoadTagsData);

        $t = $this->subj();
        $tags = $t->reverseTransform('test1,test2,test3');
        $this->assertSame('test1,test2,test3', $this->joinNames($tags));

        $tags = $t->reverseTransform('test3,test1,test2');
        $this->assertSame('test1,test2,test3', $this->joinNames($tags));

        $tags = $t->reverseTransform('test3,no1,test1,no2,test2,no3');
        $this->assertSame('test1,test2,test3,no1,no2,no3', $this->joinNames($tags));

        $tags = $t->reverseTransform('test1,nox');
        $this->assertSame('test1,nox', $this->joinNames($tags));

        $tags = $t->reverseTransform('xnox');
        $this->assertSame('xnox', $this->joinNames($tags));
    }

    private function subj()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        return new TagsTransformer($em);
    }

    private function joinNames($tags)
    {
        return
        join(',',
            array_map(function($t){ return $t->getName(); }, $tags)
        );
    }
}
