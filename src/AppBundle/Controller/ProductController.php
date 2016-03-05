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
        return $this->get('crud.product')->create($request, $p);
    }

    /**
    * @Route("/product/{pid}/edit", name="product_edit", requirements={
    *   "pid": "^\d+$"
    * })
     */
    public function editAction(Request $request, $pid)
    {
        $p = $this->get('finder.product')->findOrNull($pid);
        if($p === null)
            throw $this->createNotFoundException();

        return $this->get('crud.product')->update($request, $p);
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
}
