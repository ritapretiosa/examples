<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Iblock;

Loc::loadMessages(__FILE__);

/* ... Класс добавляет свойство "чекбокс" в админку ... */
class CIBlockPropertyCheckbox {

    public static function GetUserTypeDescription(){

    return array(
        'PROPERTY_TYPE'        => 'S',
        'USER_TYPE'            => 'Checkbox',
        'DESCRIPTION'          => GetMessage( 'IBLOCK_PROP_CHECKBOX_DESC' ),
        'GetAdminListViewHTML' => array( 'CIBlockPropertyCheckbox', 'GetTextVal' ),
        'GetPublicViewHTML'    => array( 'CIBlockPropertyCheckbox', 'GetTextVal' ),
        'GetPropertyFieldHtml' => array( 'CIBlockPropertyCheckbox', 'GetPropertyFieldHtml' ),
        'AddFilterFields'      => array( 'CIBlockPropertyCheckbox', 'AddFilterFields' ),
        'GetPublicFilterHTML'  => array( 'CIBlockPropertyCheckbox', 'GetFilterHTML' ), 
        'GetAdminFilterHTML'   => array( 'CIBlockPropertyCheckbox', 'GetFilterHTML' ),
        'ConvertToDB'          => array( 'CIBlockPropertyCheckbox', 'ConvertToFromDB' ),
        'ConvertFromDB'        => array( 'CIBlockPropertyCheckbox', 'ConvertToFromDB' ),
        'GetSearchContent'     => array( 'CIBlockPropertyCheckbox', 'GetSearchContent' ),
    );
    
    }

    public static function GetTextVal( $arProperty, $value, $strHTMLControlName ) {
        return $value['VALUE'] == 'Y' ? GetMessage( 'IBLOCK_PROP_CHECKBOX_YES' ) : GetMessage( 'IBLOCK_PROP_CHECKBOX_NO' );
    }

    public static function GetPropertyFieldHtml( $arProperty, $value, $strHTMLControlName ) {
        return '<input type="checkbox" name="'.$strHTMLControlName['VALUE'].'" value="Y" '.( $value['VALUE'] == 'Y' ? 'checked="checked"' : '' ).'/>';
    }

    public static function AddFilterFields( $arProperty, $strHTMLControlName, &$arFilter, &$filtered ) {
        if( isset( $_REQUEST[$strHTMLControlName['VALUE']] ) ) { 
            $prefix = $_REQUEST[$strHTMLControlName['VALUE']] == 'Y' ? '=' : '!=';
            $arFilter[$prefix.'PROPERTY_'.$arProperty['ID']] = 'Y';
            $filtered = TRUE;
        }
    }

    public static function GetFilterHTML( $arProperty, $strHTMLControlName ) {
        $select = '<select name="'.$strHTMLControlName['VALUE'].'">
            <option value="" >'.GetMessage( 'IBLOCK_PROP_CHECKBOX_NA' ).'</option>
            <option value="Y" '.( $_REQUEST[$strHTMLControlName['VALUE']] == 'Y' ? 'selected="selected"' : '' ).'>'.GetMessage( 'IBLOCK_PROP_CHECKBOX_YES' ).'</option>
            <option value="N" '.( $_REQUEST[$strHTMLControlName['VALUE']] == 'N' ? 'selected="selected"' : '' ).'>'.GetMessage( 'IBLOCK_PROP_CHECKBOX_NO' ).'</option>
        </select>';
        return $select;
    }

    public static function GetSearchContent( $arProperty, $value, $strHTMLControlName ) {

        $propId = $arProperty;

        $propParams = CIBlockProperty::GetByID( $propId )->Fetch();

        return $value['VALUE'] == 'Y' ? $propParams['NAME'] : '';

    }

    public static function ConvertToFromDB( $arProperty, $value ) {
        $value['VALUE'] = $value['VALUE'] == 'Y' ? 'Y' : 'N';
        return $value;
    
    }

    public static function GetLength( $arProperty, $value ) {
        return 1;
    }
}