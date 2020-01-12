<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeSharewareModule(COMPANY_SYSTEM);
use System\Core\Config\Setting,
    System\Core\Page\Url;

/* ... загружает конфигурационный файл настроек, сравнивает с постом, переданным скриптом save.js
			и перезаписывает настройки ... */

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".COMPANY_SYSTEM."/admin/config/__site.php"))
    $config = require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".COMPANY_SYSTEM."/admin/config/__site.php");

if(file_exists($_SERVER["DOCUMENT_ROOT"]."/.__site.php")) $old_arr = require_once($_SERVER["DOCUMENT_ROOT"]."/.__site.php"); 
else $old_arr = require_once($_SERVER["DOCUMENT_ROOT"]."/system/_setting/.__site.php"); 

foreach($_POST as $module => $item) {
    if($config[$module]) {
        foreach($item as $key => $value) {
            if(is_array($value)) {
                $old_arr[$module][$key] = array();
                foreach($value as $ind => $valItem) {
                    if($valItem) $old_arr[$module][$key][$ind] = $valItem;
                    elseif($old_arr[$module][$key][$ind]) $old_arr[$module][$key][$ind] = "";
                }
            } else {
                if($value) $old_arr[$module][$key] = $value; elseif($old_arr[$module][$key]) $old_arr[$module][$key] = "";
            }
            Setting::setValue($module, $old_arr[$module]);
        }
    }
}

foreach($old_arr as $module => $item) {
    foreach($item as $setting_name => $setting_value) {
        if($config[$module]["setting"][$setting_name]["TEMPLATE"] == "checkbox") {
            if(!$_POST[$module][$setting_name]) $old_arr[$module][$setting_name] = "N"; else $old_arr[$module][$setting_name] = "Y";
        } elseif($config[$module]["setting"][$setting_name]["TEMPLATE"] == "file") {

            if($config[$module]["setting"][$setting_name]["OPTION"]["MULTIPLE"] != "Y") {
                //echo $_SERVER["DOCUMENT_ROOT"]."/upload/tmp".$old_arr[$module][$setting_name][0]["tmp_name"];
                if($old_arr[$module][$setting_name][0]["tmp_name"]) {
                    if(CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/upload/tmp".$old_arr[$module][$setting_name][0]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/system/media/".$setting_name."/".$old_arr[$module][$setting_name][0]["name"])) {
                        $old_arr[$module][$setting_name] = "/system/media/".$setting_name."/".$old_arr[$module][$setting_name][0]["name"];
                    }
                } else {
                    if($_POST["0_del"] == "Y") $old_arr[$module][$setting_name];
                    else $old_arr[$module][$setting_name] = $old_arr[$module][$setting_name];
                }
            } else {
                foreach($setting_value as $key => $val) {
                    if(CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/system/media/".$setting_name."/".$old_arr[$module][$setting_name][$key]["tmp_name"], $_SERVER["DOCUMENT_ROOT"]."/system/media/".$setting_name."/".$old_arr[$module][$setting_name][$key]["name"]))
                        $old_arr[$module][$setting_name][$key] = "/system/media/".$setting_name."/".$old_arr[$module][$setting_name][$key]["name"];
                }
            }
        } else {
            if(!$_POST[$module][$setting_name]) $old_arr[$module][$setting_name] = "";
        }

        Setting::setValue($module, $old_arr[$module]);
    }
}