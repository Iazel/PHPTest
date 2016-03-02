<?php
namespace AppBundle\Repository;

class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByNameMulti(array $tag_names)
    {
        $qb = $this->createQueryBuilder('t');
        return $qb
            ->select('t')
            ->where($qb->expr()->in('t.name', ':names'))
            ->setParameter('names', $tag_names)
            ->getQuery()->getResult();
    }
}
