<?
$preparedArFields = array();
$preparedArFields['arFields']['ID'] = 0;
$preparedArFields['arFields']['OWNER_ID'] = $arFields['ASSIGNED_BY_ID']; //Ответственный менеджер - может быть только один
$preparedArFields['arFields']['NAME'] = $arFields['MESSAGE_NAME'];

$calendarSectionData = SectionTable::getList([
    'filter'=>array('OWNER_ID'=>(int)$arFields['ASSIGNED_BY_ID']),
    'select'=>array('*')
])->fetchAll();

$preparedArFields['arFields']['CAL_TYPE'] = $calendarSectionData['CAL_TYPE'];
$preparedArFields['arFields']['SECTIONS'] = array($calendarSectionData['ID']);
$preparedArFields['arFields']['COLOR'] = $calendarSectionData['COLOR'];
$preparedArFields['arFields']['TEXT_COLOR'] = $calendarSectionData['TEXT_COLOR'];
$preparedArFields['arFields']['ACCESSIBILITY'] = "busy";
$preparedArFields['arFields']['TZ_FROM'] = "";
$preparedArFields['arFields']['TZ_TO']   = "";
$preparedArFields['arFields']['DESCRIPTION'] = "";
$preparedArFields['arFields']['IMPORTANCE'] = null;
$preparedArFields['arFields']['PRIVATE_EVENT'] = false;
$preparedArFields['arFields']['RRULE'] = false;
$preparedArFields['arFields']['LOCATION'] =  [
    "OLD" => "",
    "NEW" => "",
    "CHANGED" => "",
];
$preparedArFields['arFields']['REMIND'] = "";
$preparedArFields['arFields']['IS_MEETING'] = false;
$preparedArFields['arFields']['SKIP_TIME'] = true;
$preparedArFields['arFields']['DATE_FROM'] = $arFields['DATE_FROM'];
$preparedArFields['arFields']['DATE_TO'] = $arFields['DATE_TO'];

$preparedArFields['UF'] = [];
$preparedArFields['silentErrorMode'] = false;



 $objCalendar  = new \CCalendar();
 $newId = $objCalendar->SaveEvent($preparedArFields);
