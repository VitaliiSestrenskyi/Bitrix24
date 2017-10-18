<?php 
//получения значения UF-свойства определенной сущности

global $USER_FIELD_MANAGER;
$ufDataProject = $USER_FIELD_MANAGER->GetUserFields(
            "CRM_DEAL",
            $item['ID'],
            LANGUAGE_ID
        );
        
        

//получения значений enum UF-свойства определенной сущности
$crmDealUserFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields('CRM_DEAL');
$crmTypeDeals = array();
$crmTypeDealsDb = CUserFieldEnum::GetList(array(),array('USER_FIELD_ID'=>$crmDealUserFields['UF_TYPE_DEAL']['ID']));
while($res = $crmTypeDealsDb->fetch())
            $crmTypeDeals[$res['XML_ID']] = $res;
            
            
//отключения проверки пользовательских полей             
$ID = $CCrmContact->Add(
		$arFields,
                        true,
                        array('REGISTER_SONET_EVENT' => true, "DISABLE_REQUIRED_USER_FIELD_CHECK"=>true));            
//DISABLE_REQUIRED_USER_FIELD_CHECK
//DISABLE_USER_FIELD_CHECK      
