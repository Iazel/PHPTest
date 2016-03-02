<?php
namespace AppBundle\ViewModel;

class ProductList
{
    private $temp;

    public function __construct($templating) {
        $this->temp = $templating;
    }
    
    /**
     * @param array $products
     */
    public function render($products)
    {
        return $this->temp->renderResponse('products/list.html.twig', array(
            'products' => $products
        ));
    }
}
