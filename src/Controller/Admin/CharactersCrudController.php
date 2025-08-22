<?php

namespace App\Controller\Admin;

use App\Entity\Characters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CharactersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Characters::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Personnage')
            ->setEntityLabelInPlural('Personnages')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            IntegerField::new('HP', 'Points de Vie'),
            IntegerField::new('power', 'Puissance'),
            IntegerField::new('defense', 'Défense'),
            TextareaField::new('description', 'Description')->setNumOfRows(4),
            AssociationField::new('type', 'Type'),
            AssociationField::new('role', 'Rôle'),
            
            // Champ pour l'upload d'image
            TextField::new('imageFile', 'Image')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(),
            
            // Affichage de l'image existante
            ImageField::new('imageName', 'Image actuelle')
                ->setBasePath('/uploads/images')
                ->hideOnForm(),
                
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Modifié le')->hideOnForm(),
        ];
    }
}
