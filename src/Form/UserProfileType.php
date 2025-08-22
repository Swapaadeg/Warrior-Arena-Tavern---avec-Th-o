<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('profileImageFile', VichImageType::class, [
                'label' => 'Nouvelle image de profil',
                'required' => false,
                'allow_delete' => false,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => false,
                'asset_helper' => true,
                'attr' => [
                    'class' => 'form-control-file'
                ]
            ])
            ->add('deleteImage', HiddenType::class, [
                'mapped' => false,
                'data' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
