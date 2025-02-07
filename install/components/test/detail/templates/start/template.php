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

use Bitrix\Main\Localization\Loc;

\Bitrix\Main\UI\Extension::load("ui.bootstrap4");

$this->setFrameMode(true);
?>
<div class="notebook-catalog--detail">
    <? if ($arResult['INFO']) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="title"><?= Loc::getMessage('DETAIL_TITLE');?></div>
                <h1><?= $arResult['INFO']['MODEL'] .' '. $arResult['INFO']['ARTICLE'];?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h5><?= Loc::getMessage('DETAIL_CHARS');?></h5>
                <div class="row">
                    <div class="col-md-4"><?= Loc::getMessage('DETAIL_BRAND');?>:</div>
                    <div class="col-md-8"><b><?= $arResult['INFO']['BRAND'];?></b></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><?= Loc::getMessage('DETAIL_MODEL');?>:</div>
                    <div class="col-md-8"><b><?= $arResult['INFO']['MODEL'];?></b></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><?= Loc::getMessage('DETAIL_ARTICLE');?>:</div>
                    <div class="col-md-8"><b><?= $arResult['INFO']['ARTICLE'];?></b></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><?= Loc::getMessage('DETAIL_YEAR');?>:</div>
                    <div class="col-md-8"><b><?= $arResult['INFO']['YEAR'];?></b></div>
                </div>
                <div class="row">
                    <div class="col-md-4"><?= Loc::getMessage('DETAIL_OPTIONS');?>:</div>
                    <div class="col-md-8"><b><?= implode(', ', $arResult['INFO']['OPTIONS']);?></b></div>
                </div>
            </div>
            <div class="col-md-4">
                <h2>$<?= $arResult['INFO']['PRICE'];?></h2>
            </div>
        </div>
    <?} else { ?>
        <h3><?= Loc::getMessage('DETAIL_NOT_FOUND');?></h3>
    <?} ?>
</div>
