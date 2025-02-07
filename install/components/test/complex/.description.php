<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
    "NAME" => Loc::getMessage("COMPONENT_NAME"),
    "DESCRIPTION" => Loc::getMessage("COMPONENT_DESCRIPTION"),
    "SORT" => 10,
    "PATH" => array(
        "ID" => "dv_components",
        'NAME' => Loc::getMessage('COMPONENT_PARENT_PATH'),
        'SORT' => 10,
        "CHILD" => array(
            "ID" => "notebook_complex",
            "NAME" => Loc::getMessage("COMPONENT_PATH_NAME"),
        )
    ),
    "ICON" => "/images/icon.gif",
);
