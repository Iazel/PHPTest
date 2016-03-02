<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\Type\ProductType;

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
            try {
                $this->get('product_persister')->persist($product, $form);
                $this->addFlash('success', $this->t('products.created_successfully'));
                return $this->redirectToRoute('product_list');
            }
            catch(Exception $ex) {
                $this->addFlash('error', $this->t('products.error'));
            }
        }

        return $this->get('vm.create_product')->render($form);
    }

    /**
     * @Route("/product/list", name="product_list")
     */
    public function listAction()
    {
        $products = $this->getRepo()->findAllMostRecent();
        return $this->get('vm.product_list')->render($products);
    }

    private function t($str)
    {
        return $this->get('translator')->trans($str);
    }

    private function getRepo()
    {
        $this->getDoctrine()->getManager()->getRepository('AppBundle:Product');
        return $this;
    }
}
