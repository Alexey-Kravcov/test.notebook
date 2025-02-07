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
CJSCore::Init(array("jquery"));

$this->setFrameMode(true);
?>
<div class="notebook-catalog--list">
    <div class="row">
        <div class="col-md-12">
            <h3 class="title"><?= Loc::getMessage('LIST_TITLE');?></h3>
        </div>
    </div>
    <?if ($arResult['ENTITY_TYPE'] == 'notebook') { ?>
        <form action="" method="get" id="notebook_header_form">
        <div class="row">
            <div class="col-md-8 form-group row">
                <div class="col-md-4">
                    <?= Loc::getMessage('SORT_TITLE');?>
                </div>
                <div class="col-md-8">
                    <select name="sort" class="form-control form-control-sm">
                        <option value="">...</option>
                        <option value="year_asc" <?= ($arParams['SORT_STRING'] == 'year_asc') ? 'selected' : '';?>><?= Loc::getMessage('SORT_YEAR_ASC');?></option>
                        <option value="year_desc" <?= ($arParams['SORT_STRING'] == 'year_desc') ? 'selected' : '';?>><?= Loc::getMessage('SORT_YEAR_DESC');?></option>
                        <option value="price_asc" <?= ($arParams['SORT_STRING'] == 'price_asc') ? 'selected' : '';?>><?= Loc::getMessage('SORT_PRICE_ASC');?></option>
                        <option value="price_desc" <?= ($arParams['SORT_STRING'] == 'price_desc') ? 'selected' : '';?>><?= Loc::getMessage('SORT_PRICE_DESC');?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 form-group row">
                <div class="col-md-7">
                    <?= Loc::getMessage('LIMIT_TITLE');?>
                </div>
                <div class="col-md-5">
                    <input type="text" name="limit" value="<?= $arParams['LIMIT'] ;?>" size="3" class="form-control form-control-sm">
                </div>
            </div>
        </div>
        </form>
    <?} ?>

    <?$APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        '',
        [
            'GRID_ID' => 'NOTEBOOK_GRID_ID',
            'COLUMNS' => $arResult['GRID_DATA']['COLUMNS'],
            'ROWS' => $arResult['GRID_DATA']['ROWS'],
            'TOTAL_ROWS_COUNT' => $arResult['ITEM_COUNT'],
            'AJAX_MODE' => 'Y',
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_HISTORY' => 'N',
        ]
    );?>
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:main.pagenavigation",
        "",
        array(
            "NAV_OBJECT" => $arResult['NAV'],
            "SEF_MODE" => "N",
        ),
        false
    );
    ?>
</div>
