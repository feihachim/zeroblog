<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $post = new Post();
        $post->setUser($this->getUser());

        return $post;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            TextEditorField::new('content'),
            AssociationField::new('category'),
            ImageField::new('image')->setBasePath('images/posts')
                ->setUploadDir('public/images/posts')
                ->setUploadedFileNamePattern('[name]-[randomhash].[extension]'),
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }
}
