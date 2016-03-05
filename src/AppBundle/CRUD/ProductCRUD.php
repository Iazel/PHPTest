<?php
namespace AppBundle\CRUD;

use AppBundle\Form\Type\ProductType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductCRUD
{
    private
        /**
         * doctrine.orm.entity_manager
         */
        $em,
        /**
         * vm.manage_product
         */
        $vm,
        /**
         * form.factory
         */
        $form,
        /**
         * translator
         */
        $trans,
        /**
         * router
         */
        $router,
        /**
         * session
         */
        $session;

    public function __construct($em, $vm, $form, $trans, $router, $session) {
        $this->em = $em;
        $this->vm = $vm;
        $this->form = $form;
        $this->trans = $trans;
        $this->router = $router;
        $this->session = $session;
    }

    public function create($request, $product)
    {
        $form = $this->handleForm($request, $product);
        if( $form->isValid() ) {
            try {
                $product->setCreatedNow();
                $this->em->persist($product);
                return $this->validResponse($product, 'products.created_successfully');
            }
            catch(Exception $ex) {
                $this->addFlashError();
            }
        }
        return $this->vm->renderCreate($form);
    }

    public function update($request, $product)
    {
        $form = $this->handleForm($request, $product);
        if( $form->isValid() ) {
            try {
                return $this->validResponse($product, 'products.updated_successfully');
            }
            catch(Exception $ex) {
                $this->addFlashError();
            }
        }

        return $this->vm->renderEdit($form, $product->getCreatedAt());
    }

    private function handleForm($request, $product)
    {
        $form = $this->createForm($product);
        $form->handleRequest($request);
        return $form;
    }

    private function createForm($prod)
    {
        return $this->form->create(ProductType::class, $prod);
    }

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function validResponse($prod, $msg)
    {
        $prod->setUpdatedNow();
        $this->em->flush();
        $this->addFlashSuccess($msg);
        return $this->redirectTo('product_list');
    }

    private function redirectTo($route, $params = array())
    {
        $url = $this->router->generate($route, $params,
                                        UrlGeneratorInterface::ABSOLUTE_PATH);
        return new RedirectResponse($url);
    }

    private function addFlashSuccess($msg)
    {
        return $this->addFlash('success', $msg);
    }

    private function addFlasError($msg = 'products.error')
    {
        return $this->addFlash('danger', $msg);
    }

    private function addFlash($type, $msg)
    {
        return $this->session->getFlashBag($type, $this->trans->trans($msg));
    }
}
