<?php

namespace App\Controller\Admin;

use App\Entity\TypesArmes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class TypesarmesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypesArmes::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Type d\'arme')
            ->setEntityLabelInPlural('Types d\'armes')
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du type'),
            AssociationField::new('weapons', 'Armes')
                ->hideOnForm()
                ->setHelp('Armes utilisant ce type')
        ];
    }
}
