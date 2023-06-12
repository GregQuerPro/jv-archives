<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Console;
use App\Entity\Serie;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'error_bubbling' => false,
            ])
            ->add('slug',TextType::class, [
                'label' => 'Slug',
                'error_bubbling' => false,
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'Meta Title',
                'error_bubbling' => false,
            ])
            ->add('metaDescription',TextType::class, [
                'label' => 'Meta Description',
                'error_bubbling' => false,
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'label' => "Image mise en avant",
                "download_label" => "Télécharger cette image",
                'error_bubbling' => false,
            ])
            ->add('content', CKEditorType::class, [
                'label' => "Contenu",
                'error_bubbling' => false,
            ])
            ->add('serie', EntityType::class, [
                'class' => Serie::class,
                'label' => "Sélectionnez la série de JV à laquelle est associé l'article",
                'choice_attr' => function ($choice, $key, $value) {
                    $attr = [];
                    if ($choice->getName() === 'Autres') {
                        $attr['selected'] = 'selected';
                    }
                    return $attr;
                }
            ])
            ->add('consoles', EntityType::class, [
                'class' => Console::class,
                'label' => 'Sélectionnez les consoles',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'checkbox_ctn',
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'attr' => ['class' => 'form']
        ]);
    }
}
