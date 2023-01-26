<?php

namespace App\Controller\Admin;

use App\Entity\UserNaming;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserNamingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserNaming::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('firsname'),
            TextField::new('lastname'),
            TextField::new('username'),
        ];
    }
}
