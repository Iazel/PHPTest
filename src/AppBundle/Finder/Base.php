<?php
namespace AppBundle\Finder;

abstract class Base
{
    protected $qb;
    const COUNT_FIELD = 1;

    abstract protected function initQB();

    /**
     * @param EntityManager $em;
     */
    public function __construct($em) {
        $this->qb = $em->createQueryBuilder();
        $this->initQB();
    }

    public function getResult()
    {
        return $this->qb->getQuery()->getResult();
    }

    public function getOneOrNullResult()
    {
        return $this->qb->getQuery()->getOneOrNullResult();
    }

    public function count()
    {
        return $this->doCount(static::COUNT_FIELD);
    }

    protected function doCount($field)
    {
        $qb = clone $this->qb;
        $count = "count($field)";
        return (int) $qb->select($count)->getQuery()->getSingleScalarResult();
    }

    /**
     * Helper method to instantiate the class from a doctrine instance
     * @param Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public static function create($doctrine)
    {
        return new static($doctrine->getManager()->createQueryBuilder());
    }
}
