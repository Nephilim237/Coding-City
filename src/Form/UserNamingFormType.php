<?php

namespace App\Form;

use App\Entity\UserNaming;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserNamingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label',
                ] ,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'firstname'
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => '{{ limit }} charactères minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label',
                ] ,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'lastname'
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => '{{ limit }} charactères minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                ]
            ])
            ->add('username', TextType::class, [
                'required' => false,
                'label' => 'Pseudo',
                'label_attr' => [
                    'class' => 'form-label',
                ] ,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'username'
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => '{{ limit }} charactères minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                ]
            ])
            ->add('occupation', TextType::class, [
                'required' => false,
                'label' => 'Occupation',
                'label_attr' => [
                    'class' => 'form-label',
                ] ,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'occupation'
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => '{{ limit }} charactères minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                ]
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label' => 'Pays',
                'label_attr' => [
                    'class' => 'form-label',
                ] ,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'country'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserNaming::class,
        ]);
    }
}
