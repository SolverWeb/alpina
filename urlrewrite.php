<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/catalog/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/authors/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/authors/index.php",
	),
	array(
		"CONDITION" => "#^/events/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/events/index.php",
	),
	array(
		"CONDITION" => "#^/series/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/series/index.php",
	),
	array(
		"CONDITION" => "#^\\??(.*)#",
		"RULE" => "&\$1",
		"ID" => "bitrix:catalog.section",
		"PATH" => "/catalog/coming-soon/index.php",
	),
	array(
		"CONDITION" => "#^\\??(.*)#",
		"RULE" => "&\$1",
		"ID" => "bitrix:catalog.section",
		"PATH" => "/catalog/editors-choice/index.php",
	),
	array(
		"CONDITION" => "#^\\??(.*)#",
		"RULE" => "&\$1",
		"ID" => "bitrix:catalog.section",
		"PATH" => "/catalog/mustread/index.php",
	),
	array(
		"CONDITION" => "#^\\??(.*)#",
		"RULE" => "&\$1",
		"ID" => "bitrix:catalog.section",
		"PATH" => "/catalog/new/index.php",
	),
	array(
		"CONDITION" => "#^/store/#",
		"RULE" => "",
		"ID" => "bitrix:catalog.store",
		"PATH" => "/store/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),
	array(
		"CONDITION" => "#^/#",
		"RULE" => "",
		"ID" => "bitrix:iblock.element.add.form",
		"PATH" => "/about/contacts/index.php",
	),
	array(
		"CONDITION" => "#^/#",
		"RULE" => "",
		"ID" => "bitrix:form.result.new",
		"PATH" => "/content/partnersProgram/index.php",
	),
);

?>