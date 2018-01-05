<?php
\Bitrix\Main\Config\COption::GetOptionString('crm', 'default_product_catalog_id', '0');  --- ID инфоблока из которого будут подтягиватся товары для компонента bitrix:catalog.product.search
\Bitrix\Main\Config\Option::get('catalog', 'product_form_show_offers_iblock');  ---- Настройка каталога нужно установить Y,  чтобы можно было выбирать инфоблок торг. предложений  

---регистрирую события 
BX.addCustomEvent("onChangePaySystem", function( obSelect ){
    Collector.Init();
});
--- в нужном месте 
BX.onCustomEvent("onChangePaySystem", [ this, "DKLFHJG" ]);

CCrmFieldMulti::GetList(array(),array('ENTITY_ID' => 'CONTACT', 'ELEMENT_ID' => $arEntity['ID'])); --- свойства сущности Контакт


 $arEntityTypes = CCrmFieldMulti::GetEntityTypes(); ---- получения типов


//получения дынных UF свойств контакта CRM
global $USER_FIELD_MANAGER;
$idContact   = 1;
$contactData = $USER_FIELD_MANAGER -> GetUserFields( "CRM_CONTACT", $idContact, LANGUAGE_ID );


//фильтр по дате 
$date  = new DateTime();
$start = $date->format('d.m.Y').' 00:00:00';
$end = $date->format('d.m.Y').' 23:59:59';


$test = \Bitrix\Crm\DealTable::getList([
    'filter'=>array('<=DATE_CREATE'=>\Bitrix\Main\Type\DateTime::createFromUserTime($end), '>=DATE_CREATE'=>\Bitrix\Main\Type\DateTime::createFromUserTime($start)),
    'select'=>array('*')
])->fetchAll();


//обновления UF_*
global $USER_FIELD_MANAGER;
$checkUpdate = $USER_FIELD_MANAGER->Update( 'CRM_DEAL', 290, array(
    'UF_SELECT_CONTACT_IN'  => $tttttesttt
) );
