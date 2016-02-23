<?php
namespace AppBundle\ViewModel;

class CreateProduct
{
    private $temp;

    public function __construct($templating) {
        $this->temp = $templating;
    }

    public function render($form)
    {
        return $this->temp->renderResponse('products/create.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
