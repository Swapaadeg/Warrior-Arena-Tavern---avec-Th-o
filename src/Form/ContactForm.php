<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{
    TextType,
    EmailType,
    TextareaType,
    SubmitType
};

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet',
                'required' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
                'attr' => ['class' => 'form-control', 'rows' => 6]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'contact_item',
        ]);
    }
}

