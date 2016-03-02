<?php
namespace AppBundle\Repository;

class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByNameMulti(array $tag_names)
    {
        $qb = $this->createQueryBuilder('t');
        return $qb
            ->where($qb->expr()->in('t.name', ':names'))
            ->setParameter('names', $tag_names)
            ->getQuery()->getResult();
    }

    public function findAllLike($tag)
    {
        $qb = $this->createQueryBuilder('t');
        return $qb
            ->where($qb->expr()->like('t.name', ':name'))
            ->setParameter('name', "$tag%")
            ->getQuery()->getResult()
            ;
    }
}
