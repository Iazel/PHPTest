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

        return $this->manageProduct($request, $product, function($vm, $form){
            return $vm->renderCreate($form);
        });
    }

    /**
    * @Route("/product/{pid}/edit", name="product_edit", requirements={
    *   "pid": "^\d+$"
    * })
     */
    public function editAction(Request $request, $pid)
    {
        $p = $this->get('finder.product')->find($pid);

        return $this->manageProduct($request, $p, function($vm, $form) use($p){
            return $vm->renderEdit($form, $p->getCreatedAt());
        });
    }

    private function manageProduct($request, $product, $render)
    {
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

        return $render($this->get('vm.manage_product'), $form);
    }

    /**
     * @Route("/product/list", name="product_list")
     */
    public function listAction(Request $request)
    {
        $finder = $this->get('finder.product');
        $finder->mostRecent();

        $q = $request->query->get('q', '');
        if($q !== '') {
            $tags = array_filter(array_map('trim', explode(',', $q)));
            $finder->havingTagsLike($tags);
        }

        return $this->get('vm.product_list')->render($finder->getResult(), $q);
    }

    private function t($str)
    {
        return $this->get('translator')->trans($str);
    }
}
