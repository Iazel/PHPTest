<?php
namespace AppBundle\DataFixtures\ORM;

class LoadMostRecentProductData extends Base
{
    use CoreProductTrait;

    public function genData()
    {
        $this
            ->new_prod('LeastRecent', '2015-01-01')
            ->new_prod('MostRecent 2', '2016-01-01 00:14:00')
            ->new_prod('MostRecent', '2016-01-01 00:14:30')
            ;
    }

    private function new_prod($name, $date)
    {
        $this->core_prod($name, $date);
        return $this;
    }
}
