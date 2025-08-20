<?php

namespace App\Form;

use App\Entity\Characters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du personnage',
            ])
            ->add('HP', IntegerType::class, [
                'label' => 'Points de vie',
            ])
            ->add('power', IntegerType::class, [
                'label' => 'Puissance',
            ])
            ->add('defense', IntegerType::class, [
                'label' => 'Défense',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('roleName', TextType::class, [
                'label' => 'Rôle du personnage',
                'mapped' => false,
                'required' => false,
            ])
            ->add('typeName', TextType::class, [
                'label' => 'Type du personnage',
                'mapped' => false,
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Illustration (JPG, PNG)',
                'required' => false,
                'mapped' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Characters::class,
        ]);
    }
}
