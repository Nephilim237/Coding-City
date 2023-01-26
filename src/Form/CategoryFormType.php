<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Categorie',
                'label_attr' => [
                    'class' => 'form-label',
                ] ,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'title'
                ],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'minMessage' => '{{ limit }} charactères minimum',
                        // max length allowed by Symfony for security reasons
                        'max' => 100,
                    ]),
                ]
            ])
//            ->add('parent')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
