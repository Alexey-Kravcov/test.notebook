<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "CACHE_TIME" =>  [
            "NAME" => GetMessage("NOTEBOOK_CACHE_TIME"),
            "TYPE" => "INTEGER",
            "DEFAULT" => "3600",
        ],
        "SEF_MODE" => [
            "list" => [
                "NAME" => GetMessage("PARAMS_SEF_LIST"),
                "DEFAULT" => "",
                "VARIABLES" => [],
            ],
            "brand" => [
                "NAME" => GetMessage("PARAMS_SEF_BRAND"),
                "DEFAULT" => "#BRAND#/",
                "VARIABLES" => ["BRAND"],
            ],
            "detail" => [
                "NAME" => GetMessage("PARAMS_SEF_DETAIL"),
                "DEFAULT" => "detail/#NOTEBOOK#/",
                "VARIABLES" => ["NOTEBOOK"],
            ],
            "model" => [
                "NAME" => GetMessage("PARAMS_SEF_MODEL"),
                "DEFAULT" => "#BRAND#/#MODEL#/",
                "VARIABLES" => ['BRAND', 'NOTEBOOK'],
            ],
        ],
    ],
];