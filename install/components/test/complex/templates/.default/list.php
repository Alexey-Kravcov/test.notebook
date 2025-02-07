<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?$APPLICATION->IncludeComponent(
    "test:list",
    "",
    Array(
        'BRAND' => isset($arResult['VARIABLES']['BRAND']) ? $arResult['VARIABLES']['BRAND'] : false,
        'MODEL' => isset($arResult['VARIABLES']['MODEL']) ? $arResult['VARIABLES']['MODEL'] : false,
        'URL_TEMPLATES' => $arResult['URL_TEMPLATES'],
        'SEF_FOLDER' => $arResult['FOLDER'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
    ),
    $component
);?>
