<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class Base extends EntityRepository
{
    /**
     * Return a Query Builder
     */
    protected function qb($t)
    {
        return $this->getEntityManager()->createQueryBuilder($t);
    }
}
