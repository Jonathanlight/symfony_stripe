<?php

namespace App\Form\Security;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => null,
            'is_new' => false,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'label' => '',
                'required'=>true,
                'expanded' => false,
                'placeholder' => false,
                'choices'  => [
                    'form.user.genre.title' => '',
                    'form.user.genre.man' => User::STATUS_MAN,
                    'form.user.genre.woman' => User::STATUS_WOMAN,
                ],
                'attr' => [
                    'class' => 'mdb-select md-form',
                    'placeholder' => 'Types',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'form.user.last_name',
                'attr' => [
                    'placeholder' => 'form.user.last_name',
                    'class' => 'form-control',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'form.user.first_name',
                'attr' => [
                    'placeholder' => 'form.user.first_name',
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.action.email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.action.email',
                ]
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'help' => 'form.action.password.help',
                'first_options'  => array(
                    'label' => 'form.action.password'
                ),
                'second_options' => array(
                    'label' => 'form.action.newPassword'
                ),
                'attr' => array(
                    'min' => 6,
                    'max' => 20
                )
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'form.action.submit',
                'attr' => [
                    'class' => 'btn btn-primary btn-block mt-4',
                ]
            ])
        ;
    }
}
