<?php

namespace App\Controller\Admin;

use App\Entity\Weapons;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;

class WeaponsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Weapons::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Arme')
            ->setEntityLabelInPlural('Armes')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            TextField::new('description', 'Description'),
            IntegerField::new('power', 'Puissance'),
            IntegerField::new('defense', 'Défense'),
            AssociationField::new('types', 'Type d\'arme'),
            
            // Champ pour l'upload d'image
            TextField::new('imageFile', 'Image')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(),
            
            // Affichage de l'image existante
            ImageField::new('imageName', 'Image actuelle')
                ->setBasePath('/uploads/weapons')
                ->hideOnForm(),
        ];
    }
}
