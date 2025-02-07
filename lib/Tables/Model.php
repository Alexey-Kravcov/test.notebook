<?php

namespace Test\Notebook\Tables;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class ModelTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'test_notebook_model';
    }

    public static function getMap(): array
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('NAME', array(
                'required' => true,
            )),
            new Entity\StringField('CODE', array(
                'required' => true,
            )),
            new Entity\IntegerField('MANUFACTURER_ID', array(
                'required' => true,
            )),
            (new Reference(
                'MANUFACTURER',
                ManufacturerTable::class,
                Join::on('this.MANUFACTURER_ID', 'ref.ID')
            ))
                ->configureJoinType('inner'),
        );
    }
}