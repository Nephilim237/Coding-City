<?php

namespace App\Form;

use App\Entity\AboutUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AboutUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bio',  TextareaType::class, [
                'label' => 'A propos de vous',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'bio'
                ],
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Bio trop courte. Minimum {{ limit }} charactères',
                        // max length allowed by Symfony for security reasons
                        'max' => 500,
                        'maxMessage' => 'Bio trop longue. Maximum {{ limit }} charactères '
                    ])
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AboutUser::class,
        ]);
    }
}
