<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
?><?$APPLICATION->IncludeComponent(
    "bitrix:main.map", 
    ".default", 
    array(
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "COL_NUM" => "1",
        "LEVEL" => "3",
        "SET_TITLE" => "Y",
        "SHOW_DESCRIPTION" => "N",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>