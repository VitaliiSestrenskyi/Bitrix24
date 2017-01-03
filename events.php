<?php

//события cделки CRM
//https://bxapi.ru/src/?module_id=crm&name=CCrmDeal%3A%3AAdd
AddEventHandler("crm", "OnBeforeCrmDealAdd", "test");
AddEventHandler("crm", "OnAfterExternalCrmDealAdd", "test");
AddEventHandler("crm", "OnAfterCrmDealAdd", "test");



