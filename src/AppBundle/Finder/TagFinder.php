<?php
namespace AppBundle\Finder;

use AppBundle\Entity\Tag;

class TagFinder extends Base
{
    const COUNT_FIELD = 't.id';

    protected function initQB()
    {
        $this->qb->from(Tag::class, 't')->select('t');
    }
}
