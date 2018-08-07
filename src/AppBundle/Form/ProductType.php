<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('label' => false ))
            ->add('author', TextType::class, array('label' => false ))
            ->add('category', ChoiceType::class, array(
                    'label' => false,
                    'choices'  => array(
                        'Computers' => 'computers',
                        'Projectors' => 'projectors',
                        'Servers' => 'servers',
                        'CPU' => 'cpu',
                        'Monitors' => 'monitors',
                        'Accessories' => 'accessories',
                    ),
                ))
            ->add('cost', TextType::class, array('label' => false ))
            ->add('description', CKEditorType::class, array(
                'label' => false,
                'config' => array(
                    'uiColor' => '#ffffff',
                    'enterMode' => 'CKEDITOR.ENTER_BR',
                    //...
                ),
            ))
            ->add('image', FileType::class, array('label' => 'Image (PNG/JPG file)', 'data_class' => null))
            ->add('availability', ChoiceType::class, array(
                    'label' => false,
                    'choices'  => array(
                        'Available' => 'available',
                        'Not Available' => 'not_available',
                    ),
                ))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
