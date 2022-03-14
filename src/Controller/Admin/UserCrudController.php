<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('pseudo')->onlyOnIndex(),
            TextField::new('email')->onlyOnIndex(),
            ImageField::new('image')->setBasePath('images/users')
                ->setUploadDir('public/images/users')
                ->setUploadedFileNamePattern('[name]-[randomhash].[extension]'),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
