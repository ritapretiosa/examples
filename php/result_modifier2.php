<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
CModule::IncludeModule("iblock");

/* ... Сбока массива меню, в зависимости от структуры разделов инфоблока
			и расположенных в нем элементов (элемент = страница)... */

foreach($arResult as $key => $menuItem) {
	
	$arr[] = $menuItem;
	
	$section_code = str_replace("/", "", $menuItem["LINK"]);
	
	if(!empty($section_code)) {
		
		$arSelect = Array();
        $arFilter = Array("IBLOCK_ID"=>"21", "ACTIVE"=>"Y", "SECTION_CODE"=>$section_code);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);

		$count = count($arr);
		$section = false;
        while($ob = $res->GetNextElement())
        {
            $secItem = $ob->GetFields();
			
			if($secItem["CODE"] != $section_code) {
				
				$arr[$count-1]["IS_PARENT"] = 1;
				$arr[$count-1]["DEPTH_LEVEL"] = 1;
				$arr[] = array(
					"TEXT" => $secItem["NAME"],
					"LINK" => $secItem["DETAIL_PAGE_URL"],
					"DEPTH_LEVEL" => 2,
					"PERMISSION" => $arResult[$key]["PERMISSION"],
					"ITEM_TYPE" => $arResult[$key]["ITEM_TYPE"],
					"CHAIN" => array (
						0 => $arResult[$key]["NAME"],
						1 => $secItem["NAME"]
					)
				);
				
				if(!$section) $arr[$count-1]["LINK"] = $secItem["DETAIL_PAGE_URL"];
				else $arr[$count-1]["LINK"] = $arResult[$key]["LINK"];
			} else $section = true;
		}
	}
}

$arResult = $arr;
