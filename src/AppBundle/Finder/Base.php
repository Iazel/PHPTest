<?php
namespace AppBundle\Finder;

abstract class Base
{
    protected $qb;

    abstract protected function initQB();

    /**
     * @param QueryBuilder $qb;
     */
    public function __construct($qb) {
        $this->qb = $qb;
        $this->initQB();
    }

    public function getResult()
    {
        return $this->qb->getQuery()->getResult();
    }
}
