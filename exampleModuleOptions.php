<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)  die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Itua\ApiExchange\Exceptions\SystemException;

global $APPLICATION;

Loc::loadMessages(__FILE__);

$moduleID="itua.test";
Loader::includeModule($moduleID);


//Handle requests
$request = Application::getInstance()->getContext()->getRequest();
if($request->getPost('submit') == Loc::getMessage($moduleID."SAVE_BUTTON") && check_bitrix_sessid() )
{
    //add new API key
    if( !empty($request->getPost('OPTIONS_USER_NAME'))
        && !empty($request->getPost('OPTIONS_USER_EMAIL'))
        && $request->getPost('OPTIONS_RIGHTS') != '-')
    {
        $apiKey = crypt($request->getPost('OPTIONS_USER_NAME').$request->getPost('OPTIONS_USER_EMAIL').$request->getPost('OPTIONS_RIGHTS'),$moduleID);
        $resAddApi= CApiMetaDataTable::add([
            'USER_NAME'=>$request->getPost('OPTIONS_USER_NAME'),
            'USER_EMAIL'=>$request->getPost('OPTIONS_USER_EMAIL'),
            'API_KEY'=>$apiKey,
            'CREATED_AT'=>new \Bitrix\Main\Type\DateTime(date('d.m.Y H:i:s')),
            'UPDATED_AT'=>new \Bitrix\Main\Type\DateTime(date('d.m.Y H:i:s')),
        ]);
        if($resAddApi->isSuccess())
        {
            $resAddRight= CApiUserRightsTable::add([
                'USER_ID'=>$resAddApi->getId(),
                'RIGHTS'=>$request->getPost('OPTIONS_RIGHTS'),
                'CREATED_AT'=>new \Bitrix\Main\Type\DateTime(date('d.m.Y H:i:s')),
                'UPDATED_AT'=>new \Bitrix\Main\Type\DateTime(date('d.m.Y H:i:s')),
            ]);
            if($resAddRight->isSuccess())
            {
                CAdminMessage::showMessage(array(
                    "MESSAGE" => Loc::getMessage($moduleID."MESSAGE_SUCCESS_ADD_NEW_KEY"),
                    "TYPE" => "OK",
                ));
            }
        }
        else
        {
            throw new SystemException("Error adding new key");
        }
    }

    //deleting existing key
    if($request->getPost('OPTIONS_DEL_KEY') != '-')
    {
        $resDelKey = CApiMetaDataTable::delete(trim($request->getPost('OPTIONS_DEL_KEY')));
        $idRight   = CApiUserRightsTable::getList([
            'filter'=>['USER_ID'=>trim($request->getPost('OPTIONS_DEL_KEY'))],
            'select'=>['ID']
        ])->fetch()['ID'];
        $resDelRight = CApiUserRightsTable::delete($idRight);


        if($resDelKey->isSuccess() && $resDelRight->isSuccess())
        {
            CAdminMessage::showMessage(array(
                "MESSAGE" => Loc::getMessage($moduleID."MESSAGE_SUCCESS_DELETE_KEY"),
                "TYPE" => "OK",
            ));
        }
        else
        {
            throw new SystemException("Error deleting key");
        }
    }

    //empty parameters
    if( $request->getPost('OPTIONS_RIGHTS') == '-'
        && empty($request->getPost('OPTIONS_USER_EMAIL'))
        && empty($request->getPost('OPTIONS_USER_NAME'))
        && $request->getPost('OPTIONS_DEL_KEY') == '-'
    )
    {
        CAdminMessage::showMessage(array(
            "MESSAGE" => Loc::getMessage($moduleID."MESSAGE_ERROR"),
            "TYPE" => "ERROR",
        ));
    }
}


//Tabs
$aTabs = array();
$aTabs[] = array('DIV' => 'set', 'TAB' => Loc::getMessage($moduleID.'TITLE'), 'TITLE' => Loc::getMessage($moduleID.'TAB_TITLE'));
$tabControl = new CAdminTabControl('tabControl', $aTabs);


//Options
$arRights = [
    '-'=>'-',
    'READ'=>Loc::getMessage($moduleID.'READ'),
    'WRITE'=>Loc::getMessage($moduleID.'WRITE'),
    'DENY'=>Loc::getMessage($moduleID.'DENY'),
];
$arKeys['-'] = '-';
$rsKeys = CApiMetaDataTable::getList([]);
while($res = $rsKeys->fetch())
    $arKeys[$res['ID']] = '['.$res['USER_EMAIL'].'] '.$res['USER_NAME'];

$arOptionsBase = array(
    array( "note" => $resStrTypes ),
    Loc::getMessage($moduleID.'FIRST_HEADER'),
    array(
        "OPTIONS_USER_NAME",
        Loc::getMessage($moduleID."OPTIONS_USER_NAME"),
        "",
        array(
            "text",
            "50",
        )
    ),
    array(
        "OPTIONS_USER_EMAIL",
        Loc::getMessage($moduleID."OPTIONS_USER_EMAIL"),
        "",
        array(
            "text",
            "50",
        )
    ),
    array(
        "OPTIONS_RIGHTS",
        Loc::getMessage($moduleID.'OPTIONS_RIGHTS'),
        "",
        array(
            "selectbox",
            $arRights
        )
    ),
    Loc::getMessage($moduleID.'SECOND_HEADER'),
    array(
        "OPTIONS_DEL_KEY",
        Loc::getMessage($moduleID.'OPTIONS_DEL_KEY'),
        "",
        array(
            "selectbox",
            $arKeys
        )
    ),
);
?>


<?php $tabControl->Begin(); ?>
<form method="POST" action="<?=$APPLICATION->GetCurPage()?>?lang=<?=LANGUAGE_ID?>&mid=<?=$moduleID?>">
    <?$tabControl->BeginNextTab();?>
    <?=bitrix_sessid_post();?>
    <?__AdmSettingsDrawList($moduleID, $arOptionsBase);?>
    <?//$tabControl->BeginNextTab();?>
    <?$tabControl->Buttons();?>
    <input type="submit" class="adm-btn-save" name="submit" value="<?=Loc::getMessage($moduleID."SAVE_BUTTON")?>">
    <?$tabControl->End();?>
</form>

