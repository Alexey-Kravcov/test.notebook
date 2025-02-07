<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Test\Notebook\Tables\NotebookTable;

class NotebookList extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['ELEMENT_CODE'] = (string) ($arParams['ELEMENT_CODE'] ?? '');
        $arParams['CACHE_TIME'] = (int) ($arParams['CACHE_TIME'] ?? 3600);

        return $arParams;
    }

    public function executeComponent()
    {
        $cache = new CPHPCache();
        $cache_id = 'notebook_'. md5(json_encode($this->arParams));
        $cache_path = 'notebook_module';
        if ($this->arParams['CACHE_TIME'] > 0 && $cache->InitCache($this->arParams['CACHE_TIME'], $cache_id, $cache_path)) {
            $this->arResult['ITEMS'] = $cache->GetVars();
        } else {
            Loader::includeModule('test.notebook');

            $result = NotebookTable::getList([
                'select' => ['*', 'MOD_' => 'MODEL', 'MANUF_' => 'MODEL.MANUFACTURER', 'OPT_' => 'OPTIONS'],
                'filter' => ['NAME' => $this->arParams['ELEMENT_CODE']],
            ]);

            $arNotebook = false;
            while ($notebook = $result->fetchObject()) {
                $arNotebook = [
                    'ARTICLE' => $notebook->getName(),
                    'YEAR' => $notebook->getYear(),
                    'PRICE' => $notebook->getPrice(),
                    'MODEL' => $notebook->getModel()->getName(),
                    'BRAND' => $notebook->getModel()->getManufacturer()->getName(),
                    'OPTIONS' => []
                ];
                foreach ($notebook->getOptions() as $arOption) {
                    $arNotebook['OPTIONS'][] = $arOption->getName();
                }
            }
            $this->arResult['INFO'] = $arNotebook;

            if ($this->arParams['CACHE_TIME'] > 0) {
                $cache->StartDataCache($this->arParams['CACHE_TIME'], $cache_id, $cache_path);
                $cache->EndDataCache($this->arResult['ITEMS']);
            }

        }

        $this->IncludeComponentTemplate();
    }
}