<?php

namespace App\Form;

use App\Entity\Console;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ConsoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'error_bubbling' => false,
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'error_bubbling' => false,
            ])
            ->add('metaTitle', TextType::class, [
                'label' => 'Meta Title',
                'error_bubbling' => false,
            ])
            ->add('metaDescription', TextType::class, [
                'label' => 'Meta Description',
                'error_bubbling' => false,
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'label' => "Image mise en avant",
                "download_label" => "Télécharger cette image",
                'error_bubbling' => false
            ])
            ->add('display', CheckboxType::class, [
                'label' => 'Afficher cette console dans les menus',
                'error_bubbling' => false,
                'required' => false
            ])
            ->add('color', ColorType::class, [
                'label' => 'Sélectionner la couleur associée à cette console',
                'error_bubbling' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Console::class,
        ]);
    }
}
