<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Test\Notebook\Tables\ManufacturerTable;
use Test\Notebook\Tables\ModelTable;
use Test\Notebook\Tables\NotebookTable;
use Bitrix\Main\Loader;

class NotebookList extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['BRAND'] = (string) ($arParams['BRAND'] ?? '');
        $arParams['MODEL'] = (string) ($arParams['MODEL'] ?? '');
        $arParams['SORT'] = (string) ($arParams['SORT'] ?? '');
        $arParams['CACHE_TIME'] = (int) ($arParams['CACHE_TIME'] ?? 3600);
        $arParams['PAGE'] = (int) ($arParams['PAGEN'] ?? 1);
        $arParams['LIMIT'] = (int) ($arParams['LIMIT'] ?? 10);

        if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != '') {
            $sort = explode('_', $_REQUEST['sort']);
            $arParams['SORT_BY'] = $sort[0];
            $arParams['SORT_ORDER'] = $sort[1];
            $arParams['SORT_STRING'] = $_REQUEST['sort'];
        } else {
            $arParams['SORT_STRING'] = '';
        }
        if(isset($_REQUEST['limit']) && $_REQUEST['limit'] != '') {
            $arParams['LIMIT'] = (int) $_REQUEST['limit'];
        } else {
            $arParams['LIMIT']  = 5;
        }

        return $arParams;
    }

    public function executeComponent()
    {
        $cache = new CPHPCache();
        $cache_id = 'notebook_'. md5(json_encode($this->arParams) . $_SERVER['QUERY_STRING']);
        $cache_path = 'notebook_module';
        if ($this->arParams['CACHE_TIME'] > 0 && $cache->InitCache($this->arParams['CACHE_TIME'], $cache_id, $cache_path)) {
            $data = $cache->GetVars();
            $this->arResult['ITEMS'] = $data['ITEMS'];
            $this->arResult['NAV'] = $data['NAV'];
            $this->arResult['ENTITY_TYPE'] = $data['ENTITY_TYPE'];
            $this->arResult['ITEM_COUNT'] = $data['ITEM_COUNT'];
        } else {
            Loader::includeModule('test.notebook');

            $this->arResult['NAV'] = new \Bitrix\Main\UI\PageNavigation("nav-notebook");
            $this->arResult['NAV']->allowAllRecords(true)
                ->setPageSize($this->arParams['LIMIT'])
                ->initFromUri();
            if ($this->arParams['MODEL'] != '') {
                $this->arResult['ITEMS'] = $this->getModelData();
                $this->arResult['ENTITY_TYPE'] = 'notebook';
            } elseif ($this->arParams['BRAND'] != '') {
                $this->arResult['ITEMS'] = $this->getBrandData();
                $this->arResult['ENTITY_TYPE'] = 'model';
            } else {
                $this->arResult['ITEMS'] = $this->getListData();
                $this->arResult['ENTITY_TYPE'] = 'brand';
            }

            $cacheData = [
                'ITEMS' => $this->arResult['ITEMS'],
                'NAV' => $this->arResult['NAV'],
                'ENTITY_TYPE' => $this->arResult['ENTITY_TYPE'],
                'ITEM_COUNT' => $this->arResult['ITEM_COUNT'],
            ];

            if ($this->arParams['CACHE_TIME'] > 0) {
                $cache->StartDataCache($this->arParams['CACHE_TIME'], $cache_id, $cache_path);
                $cache->EndDataCache($cacheData);
            }
        }

        $this->prepareGridData();

        $this->IncludeComponentTemplate();
    }

    protected function getModelData()
    {
        $params = array_merge([
            'select' => ['*'],
            'filter' => ['MODEL.CODE' => $this->arParams['MODEL']],
        ], $this->getAdvParams());
        $result = NotebookTable::getList($params);
        $this->arResult['ITEM_COUNT'] = $result->getCount();
        $this->arResult['NAV']->setRecordCount($this->arResult['ITEM_COUNT']);

        $arData = [];
        while ($element = $result->fetch()) {
            $elementData = [
                'ID' => $element['ID'],
                'NAME' => $element['NAME'],
                'YEAR' => $element['YEAR'],
                'PRICE' => $element['PRICE'],
            ];
            $elementData['URL'] = $this->getUrl('detail', ['notebook' => $elementData['NAME']]);
            $arData[] = $elementData;
        }

        return $arData;
    }

    protected function getBrandData()
    {
        $params = array_merge([
            'select' => ['*', 'MAN_' => 'MANUFACTURER'],
            'filter' => ['MANUFACTURER.CODE' => $this->arParams['BRAND']],
        ], $this->getAdvParams());
        $result = ModelTable::getList($params);
        $this->arResult['ITEM_COUNT'] = $result->getCount();
        $this->arResult['NAV']->setRecordCount($this->arResult['ITEM_COUNT']);

        $arData = [];
        while ($element = $result->fetchObject()) {
            $elementData = [
                'ID' => $element->getId(),
                'NAME' => $element->getName(),
                'BRAND' => $element->getManufacturer()->getName(),
            ];
            $elementData['URL'] = $this->getUrl('model', ['model' => $elementData['NAME'], 'brand'=>$elementData['BRAND']]);
            $arData[] = $elementData;
        }

        return $arData;
    }

    protected function getListData()
    {
        $params = array_merge([
            'select' => ['*'],
        ], $this->getAdvParams());
        $result = ManufacturerTable::getList($params);
        $this->arResult['ITEM_COUNT'] = $result->getCount();
        $this->arResult['NAV']->setRecordCount($this->arResult['ITEM_COUNT']);

        $arData = [];
        while ($brand = $result->fetch()) {
            $arData[] = [
                'ID' => $brand['ID'],
                'NAME' => $brand['NAME'],
                'URL' => $this->getUrl('brand', ['brand'=>$brand['NAME']]),
            ];
        }

        return $arData;
    }

    protected function getUrl($type, $data)
    {
        $template = $this->arParams['URL_TEMPLATES'][$type];
        if (!$template) {
            die('Некорректный формат URL');
        }

        foreach($data as $var => $val) {
            $template = str_replace('#'. strtoupper($var) ."#", strtolower($val), $template);
        }
        $url = $this->arParams['SEF_FOLDER'] . $template;

        return $url;
    }

    protected function getAdvParams()
    {
        $params = [
            'count_total' => true,
            "offset" => $this->arResult['NAV']->getOffset(),
            "limit" => $this->arResult['NAV']->getLimit(),
        ];

        if (isset($this->arParams['SORT_BY']) && $this->arParams['SORT_BY'] != '') {
            $params['order'] = [strtoupper($this->arParams['SORT_BY']) => strtoupper($this->arParams['SORT_ORDER'])];
        }

        return $params;
    }

    protected function prepareGridData()
    {
        $arColumn = [
            ['id' => 'id', 'name' => 'ID', 'default' => true],
            ['id' => 'name', 'name' => Loc::getMessage('COLUMN_NAME'), 'default' => true],
            ['id' => 'year', 'name' => Loc::getMessage('COLUMN_YEAR'), 'default' => true],
            ['id' => 'price', 'name' => Loc::getMessage('COLUMN_PRICE'), 'default' => true],
        ];
        $data = ['COLUMNS' => $arColumn, 'ROWS' => []];
        foreach ($this->arResult['ITEMS'] as $k => $arItem) {
            $name = '<a href="'. $arItem['URL'] .'" class="nav-link">'. $arItem['NAME'] .'</a>';
            $data['ROWS'][] = [
                'id' => $k,
                'columns' => [
                    'id' => $arItem['ID'],
                    'name' => $name,
                    'year' => ($arItem['YEAR']) ?? '',
                    'price' => $arItem['PRICE'] ?? '',
                ]
            ];
        }

        $this->arResult['GRID_DATA'] = $data;
    }
}