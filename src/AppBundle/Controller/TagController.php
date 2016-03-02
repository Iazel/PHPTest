<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TagController extends Controller
{
    /**
     * @Route("/tags/search/{tag}", name="tags_search", defaults={"tag": ""})
     */
    public function searchAction($tag)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Tag');
        $tags = array_map(
            function($m){ return $m->getName(); },
            $repo->findAllLike($tag)
        );
        return new JsonResponse($tags);
    }
}
