<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use function Sodium\add;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre de header'),
            TextareaField::new('content', 'contenu de notre header')->hideOnIndex(),
            TextField::new('btnTitle', 'le titre de notre bouton'),
            TextField::new('btnUrl', 'Url de destination de notre bouton'),
            ImageField::new('illustration')->setBasePath('/Images/Header')->setUploadDir('public\Images\Header')->setFormTypeOptions(['required'=>false]),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->add('index', 'detail');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }

}
