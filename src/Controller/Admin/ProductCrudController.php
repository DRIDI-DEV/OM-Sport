<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Stripe\Price;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('Name'),
            SlugField::new('slug')->setTargetFieldName('Name'),
            ImageField::new('Illustration')->setBasePath('Images/')->setUploadDir('public/Images'),
            TextareaField::new('subtitle'),
            BooleanField::new('isBest'),
            TextareaField::new('description')->hideOnIndex(),
            MoneyField::new('price')->setCurrency('TND')->setNumDecimals(3),
            AssociationField::new('category')

        ];
    }


    public function configureCrud(Crud $crud): Crud
    {



        return $crud
            // ...
            ->showEntityActionsInlined()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add('index', 'detail')
            //->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
    }

}
