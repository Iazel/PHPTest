<?php
namespace AppBundle\Form\DataTransform;

use AppBundle\Entity\Tag;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsTransformer implements DataTransformerInterface
{
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct($em) {
        $this->em = $em;
    }

    public function transform($tags)
    {
        if($tags === null || count($tags) === 0)
            return '';

        return join(',', array_map(function($t){ return $t->getName(); },
            $tags->toArray()));
    }

    public function reverseTransform($tags)
    {
        if( empty($tags) )
            return array();

        $tags = array_filter(array_map('trim', explode(',', $tags)));
        $tag_objs = $this->em->getRepository('AppBundle:Tag')->findByNameMulti($tags);

        $map = array_flip($tags);
        foreach($tag_objs as $t)
            $map[$t->getName()] = true;

        foreach($map as $t => $ok) {
            if($ok === true)
                continue;

            $tag = new Tag($t);
            $tag_objs[] = $tag;
            $this->em->persist($tag);
        }

        return $tag_objs;
    }
}
