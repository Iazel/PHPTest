<?php
namespace AppBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as F;

class ProductType extends AbstractType {
    public function getName() {
        return 'product';
    }
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', F\TextType::class)
            ->add('desc', F\TextareaType::class)
            ->add('save', F\SubmitType::class)
            ;
    }
}
