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
        $p = new Product;
        $p->setCreatedNow();

        return $this->manageProduct($request, $p, 'created', function($vm, $form){
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

        return $this->manageProduct($request, $p, 'updated', function($vm, $form) use($p){
            return $vm->renderEdit($form, $p->getCreatedAt());
        });
    }

    private function manageProduct($request, $product, $msg, $render)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if( $form->isValid() ) {
            try {
                $this->persist($product);
                $this->addFlash('success', $this->trans("products.{$msg}_successfully"));
                return $this->redirectToRoute('product_list');
            }
            catch(Exception $ex) {
                $this->addFlash('danger', $this->trans('products.error'));
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

    private function persist($p)
    {
        $p->setUpdatedNow();

        $em = $this->getDoctrine()->getManager();
        $em->persist($p);
        $em->flush();
    }

    private function trans($str)
    {
        return $this->get('translator')->trans($str);
    }
}
