<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class EditorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Editor::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex()->hideOnForm(),
            TextField::new('name','Maisons d\'édition'),
            AssociationField::new('books', 'Livres'),
        ];
    }
    
}
