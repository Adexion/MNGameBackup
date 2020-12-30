<?php

namespace ModernGame\Controller\Panel\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ModernGame\Database\Entity\Item;
use ModernGame\Database\Entity\ItemList;
use ModernGame\Field\EntityField;

class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Przedmiot')
            ->setEntityLabelInPlural('Przedmioty');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nazwa'),
            AvatarField::new('icon', 'Ikona'),
            TextField::new('command', 'Komenda'),
            EntityField::new('itemList', 'Lista')
                ->setClass(ItemList::class, 'name'),
            ChoiceField::new('serverid', 'Serwer ID')
                ->setChoices($this->getServerList())
        ];
    }

    public function getServerList(): array
    {
        $list = [];
        foreach ($this->getParameter('minecraft') as $key => $value) {
            $list[$value['host']] = $key;
        }

        return $list;
    }
}
