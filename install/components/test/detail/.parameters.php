<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "ELEMENT_CODE" =>  [
            "NAME" => GetMessage("NOTEBOOK_ELEMENT_CODE"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "CACHE_TIME" =>  [
            "NAME" => GetMessage("NOTEBOOK_CACHE_TIME"),
            "TYPE" => "INTEGER",
            "DEFAULT" => "3600",
        ],
    ],
];