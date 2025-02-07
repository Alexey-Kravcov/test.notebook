<?php

namespace Test\Notebook\Tables;

use Bitrix\Main\Entity;

class OptionTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'test_notebook_option';
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
        );
    }
}