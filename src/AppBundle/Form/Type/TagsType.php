<?php
namespace AppBundle\Form\Type;
use Fogs\TaggingBundle\Form\TagsType as Base;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TagsType extends Base
{
    public function getParent()
    {
        return TextType::class;
    }
}
