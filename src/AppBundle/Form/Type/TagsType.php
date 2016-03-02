<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Form\DataTransform\TagsTransformer;

class TagsType extends AbstractType
{
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct($em) {
        $this->em = $em;
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer( new TagsTransformer($this->em) );
    }
}
