<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'title'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le titre est Obligatoire.'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Au moins {{ limit }} charactères pour le titre',
                        // max length allowed by Symfony for security reasons
                        'max' => 250,
                    ]),
                ],
                'required' => true,
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'autocomplete' => 'content'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est Obligatoire.'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Au moins {{ limit }} charactères pour le contenu de cet article',
                        // max length allowed by Symfony for security reasons
                        'max' => 10000,
                    ]),
                ],
                'required' => true,
            ])
            ->add('postBanner', VichFileType::class, [
                'label' => 'Image illsutrative',
                'required' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
            ->add('video', TextareaType::class, [
                'label' => 'Lien vers une vidéo',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'video'
                ],
                'help' => 'Vous pouvez ajouter une vidéo pour cet article si vous le souhaitez.',
                'help_attr' => ['class' => 'form-help-class'],
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('c')->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-select',
                ],
                'expanded' => false,
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
