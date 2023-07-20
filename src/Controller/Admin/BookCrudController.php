<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use Faker\Core\Number;
use Symfony\Component\Validator\Constraints\Date;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex()->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('resume', 'Résumé'),
            MoneyField::new('price')->setCurrency('EUR'),
            TextField::new('isbn'),
            TextField::new('language', 'Langue'),
            IntegerField::new('publicationDate', 'Année'),
            AssociationField::new('author', 'Auteur'),
            AssociationField::new('editor', 'Editeur'),
            AssociationField::new('genre', 'Genre'),
            
        ];
    }
    
}
