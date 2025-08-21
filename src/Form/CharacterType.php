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
                'attr' => ['class' => 'form-control']
            ])
            ->add('HP', IntegerType::class, [
                'label' => 'Points de vie',
                'attr' => ['class' => 'form-control']
            ])
            ->add('power', IntegerType::class, [
                'label' => 'Puissance',
                'attr' => ['class' => 'form-control']
            ])
            ->add('defense', IntegerType::class, [
                'label' => 'Défense',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 4]
            ])
            ->add('roleName', TextType::class, [
                'label' => 'Rôle du personnage',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('typeName', TextType::class, [
                'label' => 'Type du personnage',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Illustration (JPG, PNG)',
                'required' => false,
                'mapped' => true,
                'attr' => ['class' => 'form-control']
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
