<?php

namespace Test\Notebook\Tables;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class RelationTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'test_notebook_relation';
    }

    public static function getMap(): array
    {

        return [
            (new Entity\IntegerField('NOTEBOOK_ID'))
                ->configurePrimary(true),
            (new Reference('NOTEBOOK', NotebookTable::class,
                Join::on('this.NOTEBOOK_ID', 'ref.ID')))
                ->configureJoinType('inner'),
            (new Entity\IntegerField('OPTION_ID'))
                ->configurePrimary(true),
            (new Reference('OPTION', OptionTable::class,
                Join::on('this.OPTION_ID', 'ref.ID')))
                ->configureJoinType('inner'),
        ];
    }
}