<?php
namespace AppBundle\ViewModel;

class ManageProduct extends Base
{
    public function renderCreate($form)
    {
        return $this->doRender(
            'products/create.html.twig',
            $this->getData($form)
        );
    }

    public function renderEdit($form, $created_at)
    {
        return $this->doRender(
            'products/edit.html.twig',
            $this->getData($form, $created_at)
        );
    }

    public function getData($form, $created_at = null)
    {
        $data = array( 'form' => $form->createView() );
        if($created_at !== null)
            $data['created_at'] = $created_at;

        return $data;
    }
}
