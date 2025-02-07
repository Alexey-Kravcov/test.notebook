<?php

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Main\Application,
    Bitrix\Main\Entity\Base;

class test_notebook extends CModule
{
    var $MODULE_ID = "test.notebook";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    private $pathInstall;

    function __construct()
    {
        $PathInstall = str_replace("\\", "/", __FILE__);
        $this->pathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
        IncludeModuleLangFile($this->pathInstall ."/install.php");

        $arModuleVersion = [];
        include($this->pathInstall ."/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("TEST_NOTEBOOK_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("TEST_NOTEBOOK_DESCRIPTION");
    }

    function InstallDB($arParams = array())
    {
        global $DB, $APPLICATION;

        ModuleManager::registerModule('test.notebook');
        Loader::includeModule('test.notebook');

        if ($arParams['dropTables'] == 'Y' && $DB->TableExists('test_notebook_notebook')) {
            $this->dropTables();
        }

        if (!$DB->TableExists('test_notebook_notebook')) {
            Base::getInstance('Test\Notebook\Tables\ManufacturerTable')->createDBTable();
            Base::getInstance('Test\Notebook\Tables\ModelTable')->createDBTable();
            Base::getInstance('Test\Notebook\Tables\NotebookTable')->createDBTable();
            Base::getInstance('Test\Notebook\Tables\OptionTable')->createDBTable();
            Base::getInstance('Test\Notebook\Tables\RelationTable')->createDBTable();

            if (!$this->fillTables()) {
                return false;
            }
        }

        return true;
    }

    function UnInstallDB($arParams = array())
    {
        $this->dropTables();

        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles($arParams = array())
    {
        if (!is_dir($_SERVER["DOCUMENT_ROOT"] . "/local/components/test/")) {
            if (!CopyDirFiles($this->pathInstall . "/components/", $_SERVER["DOCUMENT_ROOT"] . "/local/components/", true, true)) {
                return false;
            }
        }

        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION, $step, $obModule;
        $step = intval($step);
        if ($step<2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("TEST_NOTEBOOK_INSTALL_TITLE"), $this->pathInstall . "/step1.php");
        } elseif ($step==2)
        {
            if ($this->InstallDB([
                    "dropTables" => $_REQUEST["drop_tables"]
                ])
            ) {
                $this->InstallEvents();
                $this->InstallFiles();
            }
            $obModule = $this;
        }

        return true;
    }

    function DoUninstall()
    {
        global $APPLICATION, $step, $obModule;
        $step = intval($step);
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("TEST_NOTEBOOK_UNINSTALL_TITLE"), $this->pathInstall . "/unstep1.php");
        } elseif ($step==2)
        {
            if ($_REQUEST["drop_tables"] == 'Y') {
                Loader::includeModule("test.notebook");
                $this->UnInstallDB();
            }
            ModuleManager::unRegisterModule("test.notebook");
        }

        return true;
    }

    private function dropTables()
    {
        $connection = Application::getConnection();
        $connection->dropTable(Test\Notebook\Tables\RelationTable::getTableName());
        $connection->dropTable(Test\Notebook\Tables\OptionTable::getTableName());
        $connection->dropTable(Test\Notebook\Tables\NotebookTable::getTableName());
        $connection->dropTable(Test\Notebook\Tables\ModelTable::getTableName());
        $connection->dropTable(Test\Notebook\Tables\ManufacturerTable::getTableName());

        return true;
    }

    private function fillTables()
    {
        global $APPLICATION;

        $arData = $this->getTableForData();
        $this->errors = [];

        foreach ($arData['manufacturer'] as $manufacturer) {
            $result = Test\Notebook\Tables\ManufacturerTable::add([
                'NAME' => $manufacturer[0],
                'CODE' => $manufacturer[1],
            ]);
            if (!$result->isSuccess()) {
                $this->errors[] = $result->getErrorMessages();
            }
        }

        if (!$this->errors) {
            foreach ($arData['model'] as $arModel) {
                $result = Test\Notebook\Tables\ModelTable::add([
                    'NAME' => $arModel[0],
                    'CODE' => $arModel[1],
                    'MANUFACTURER_ID' => $arModel[2],
                ]);
                if (!$result->isSuccess()) {
                    $this->errors[] = $result->getErrorMessages();
                }
            }
        }

        if (!$this->errors) {
            foreach ($arData['notebook'] as $arNote) {
                $result = Test\Notebook\Tables\NotebookTable::add([
                    'NAME' => $arNote[0],
                    'CODE' => $arNote[1],
                    'YEAR' => $arNote[3],
                    'PRICE' => $arNote[4],
                    'MODEL_ID' => $arNote[2],
                ]);
                if (!$result->isSuccess()) {
                    $this->errors[] = $result->getErrorMessages();
                }
            }
        }

        if (!$this->errors) {
            foreach ($arData['option'] as $option) {
                $result = Test\Notebook\Tables\OptionTable::add([
                    'NAME' => $option,
                ]);
                if (!$result->isSuccess()) {
                    $this->errors[] = $result->getErrorMessages();
                }
            }
        }

        if (!$this->errors) {
            foreach ($arData['relation'] as $arRelation) {
                $result = Test\Notebook\Tables\RelationTable::add([
                    'NOTEBOOK_ID' => $arRelation[0],
                    'OPTION_ID' => $arRelation[1],
                ]);
                if (!$result->isSuccess()) {
                    $this->errors[] = $result->getErrorMessages();
                }
            }
        }



        if(!empty($this->errors))
        {
            $APPLICATION->ThrowException(implode("", $this->errors));
            return false;
        }

        return true;
    }

    private function getTableForData()
    {
        return [
            'manufacturer' => [['Acer', 'acer'], ['Asus', 'asus'], ['Lenovo', 'lenovo']],
            'model' => [['Aspire', 'aspire', 1], ['Enduro', 'enduro', 1], ['Vivobook', 'vivobook', 2], ['Zephyrus', 'zephyrus', 2], ['IdeaPad', 'ideapad', 3], ['Thinkbook', 'thinkbook', 3], ['Yoga', 'yoga', 3]],
            'notebook' => [
                ['1203XV', '1203xv', 1, 2021, 300.2], ['1300X', '1300x', 1, 2024, 525],
                ['1300XC', '1300xc', 2, 2019, 152.45],
                ['100-15', '100-15', 4, 2022, 248.45], ['13', '13', 4, 2023, 347.42],
                ['300-15', '300-15', 6, 2020, 212.15],
                ['16F452X', '16f452x', 7, 2022, 451.24],
                ['320', '320', 5, 2023, 425.15], ['320S-15', '320s-15', 5, 2024, 678.45],
                ['X1504ZA', 'x1504za', 3, 2018, 198.2], ['F1504VA', 'f1504va', 3, 2024, 326], ['X1517VA', 'x1517va', 3, 2021, 465.45]
            ],
            'option' => ['Легкий', 'Алюминиевый', 'Быстрая зарядка', 'Игровой', 'Трансформер', 'Подсветка клавиатуры'],
            'relation' => [
                [1, 1], [1, 2], [1, 6],
                [2, 4], [2, 6],
                [3, 3],
                [4, 6], [4, 1],
                [5, 6],
                [6, 2], [6, 4], [6, 6],
                [7, 3], [7, 4], [7, 6],
                [8, 5], [8, 1],
                [9, 3], [9, 4], [9, 6],
                [10, 1], [10, 2], [10, 4],
                [11, 1], [11, 2], [11, 5],
                [12, 4], [12, 5], [12, 1],
            ]

        ];
    }
}