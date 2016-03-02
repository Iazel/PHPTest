<?php
namespace AppBundle\Finder;

use AppBundle\Entity\Product;


class ProductFinder extends Base
{
    protected function initQB()
    {
        $this->qb->from(Product::class, 'p')->select('p');
        return $this;
    }

    public function mostRecent()
    {
        $this->qb->orderBy('p.created_at', 'DESC');
        return $this;
    }

    public function havingTagsLike($tags)
    {
        $qb = $this->qb;
        $or = $qb->expr()->orX();
        $qb->join('p.tags', 't')->where($or)->groupBy('p.id');

        foreach($tags as $i => $tag) {
            $or->add( $qb->expr()->like('t.name', ":tagname$i") );
            $qb->setParameter("tagname$i", $tag.'%');
        }
        return $this;
    }
}
