<?php
namespace AppBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as F;
use Symfony\Component\Validator\Constraints as A;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType {
    public function getName() {
        return 'product';
    }
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', F\TextType::class)
            ->add('image_file', VichImageType::class, array(
                'required' => false,
                'constraints' => array(new A\Image),
            ))
            ->add('desc', F\TextareaType::class, array('required' => false))
            ->add('save', F\SubmitType::class)
            ;
    }
}
