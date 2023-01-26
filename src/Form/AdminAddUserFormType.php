<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminAddUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'email'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Email Obligatoire.'
                    ])
                ],
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'placeholder' => 'Attribuer des rôles',
                'required' => true,
                'multiple' => true,
                'choices' => [
                    'Modérateur' => "ROLE_MODO",
                    'Editeur' => "ROLE_EDITOR",
                    'Administrateur' => "ROLE_ADMIN",
                    'Master class' => "ROLE_SUPER_ADMIN"
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
