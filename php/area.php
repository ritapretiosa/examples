<?
namespace System\Main\Area;

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/* .. Класс подключает на страницу отдельный html-блок по переданному пути .. */
class Block {

    private function __construct() {
        
    }

    static public function addBlock ($path) {
        if(preg_match_all("/[A-Za-z._]/", $path) == strlen($path)) {
            $section = $_SERVER["DOCUMENT_ROOT"]."/ss/block/";
            foreach(explode(".", $path) as $item)
                $section .= $item ."/";
            @include($section . "template.php");
        }
        else
            echo Loc::getMessage("MISSING_BLOCK");
    }
}