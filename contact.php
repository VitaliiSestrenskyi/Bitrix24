<?
//Получить номер телефона контакта в коробочной версии битрикс24
CModule::IncludeModule('crm');
 
 
$dbResult = CCrmFieldMulti::GetList(
    array('ID' => 'asc'),
    array(
        'ENTITY_ID' => 'CONTACT',
        'ELEMENT_ID' => 2
    )
);
$fields = $dbResult->Fetch();
echo $fields['VALUE'];


