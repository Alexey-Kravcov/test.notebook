<?php

namespace Test\Notebook\Tables;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;

class NotebookTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'test_notebook_notebook';
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
            new Entity\IntegerField('YEAR', array(
                'required' => true,
            )),
            new Entity\FloatField('PRICE', array(
                'required' => true,
            )),
            new Entity\IntegerField('MODEL_ID', array(
                'required' => true,
            )),
            (new Reference(
                'MODEL',
                ModelTable::class,
                Join::on('this.MODEL_ID', 'ref.ID')
            ))
                ->configureJoinType('inner'),
            (new ManyToMany('OPTIONS', OptionTable::class))
                ->configureTableName('test_notebook_relation')
        );
    }
}