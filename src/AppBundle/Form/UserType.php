<?php 

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('f_name', TextType::class, array('label' => false ))
            ->add('l_name', TextType::class, array('label' => false ))
            ->add('email', EmailType::class, array('label' => false ))
            ->add('username', TextType::class, array('label' => false ))
            ->add('phone', TextType::class, array('label' => false ))
            ->add('residence', TextType::class, array('label' => false ))
            ->add('category', TextType::class, array('label' => false ))
            ->add('category', ChoiceType::class, array(
                    'label' => false,
                    'choices'  => array(
                        'ABC' => 'abc',
                        'Literature Evangelist' => 'le',
                        'Official' => 'office',
                    ),
                ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => false),
                'second_options' => array('label' => false),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}