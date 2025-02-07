<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

class NotebookCatalog extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['CACHE_TIME'] = (int) ($arParams['CACHE_TIME'] ?? 3600);

        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->arParams['SEF_MODE'] == 'Y') {
            $arDefaultUrlTemplates404 = $this->getDefaultUrlTemplate();
            $arVariables = [];
            $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $this->arParams['SEF_URL_TEMPLATES']);
            $componentPage = CComponentEngine::ParseComponentPath($this->arParams['SEF_FOLDER'], $arUrlTemplates, $arVariables);

            if ($componentPage != 'detail') {
                $componentPage = 'list';
            }

            $this->arResult = [
                "FOLDER" => $this->arParams["SEF_FOLDER"],
                "URL_TEMPLATES" => $arUrlTemplates,
                "VARIABLES" => $arVariables,
            ];

            $this->IncludeComponentTemplate($componentPage);
        } else {
            die(Loc::getMessage('NOT_SEF_MODE_WARNING'));
        }
    }

    protected function getDefaultUrlTemplate()
    {
        return [
            "list" => "",
            "brand" => "#BRAND#/",
            "detail" => "detail/#NOTEBOOK#/",
            "model" => "#BRAND#/#MODEL#/"
        ];
    }
}