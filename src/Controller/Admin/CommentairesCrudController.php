<?php

namespace App\Controller\Admin;

use App\Entity\Commentaires;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class CommentairesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaires::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('Content'),
            Field::new('pseudo'),
            Field::new('email'),
            DateTimeField::new('created_at'),
        ];
    }

public function configureActions(Actions $actions): Actions
{
    return $actions

        ->disable(Action::NEW, Action::EDIT)
    ;
}

}
