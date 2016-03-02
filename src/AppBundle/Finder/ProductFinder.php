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
        $this->qb->orderBy('created_at', 'DESC');
        return $this;
    }

    public function havingTagLike($tag)
    {
        $qb = $this->qb;
        $qb->join('p.tags', 't')
            ->where($qb->expr()->like('t.name', ':tagname'))
            ->setParameter('tagname', $tag.'%')
            ;
        return $this;
    }
}
