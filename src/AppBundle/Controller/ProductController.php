<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\FormType\ProductType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function createAction(Request $request)
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if( $form->isValid() ) {
            $product->setCreatedNow();
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_list');
        }

        return $this->get('vm.create_product')->render($form);
    }

    /**
     * @Route("/product/list", name="product_list")
     */
    public function listAction()
    {
    }
}
