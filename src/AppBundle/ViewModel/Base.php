<?php
namespace AppBundle\ViewModel;

abstract class Base
{
    protected $temp;

    public function __construct($templating) {
        $this->temp = $templating;
    }

    protected function doRender($tpl, $data)
    {
        return $this->temp->renderResponse($tpl, $data);
    }
}
