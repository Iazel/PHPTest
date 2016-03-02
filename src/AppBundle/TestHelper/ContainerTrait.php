<?php
namespace AppBundle\TestHelper;

trait ContainerTrait
{
    protected function getContainer()
    {
        return static::$kernel->getContainer();
    }
}
