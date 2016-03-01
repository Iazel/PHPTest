<?php
namespace AppBundle\Service;

class ProductPersister
{
    private $orm;

    public function __construct($doctrine) {
        $this->orm = $doctrine;
    }
    /**
     * @param Product $prd
     * @param Form $form
     *
     * @throw PDOException
     * @throw FileException
     */
    public function persist($prd, $form)
    {
        $em = $this->orm->getManager();
        $prd->setCreatedNow();
        $em->persist($prd);
        $em->flush();
    }
}
