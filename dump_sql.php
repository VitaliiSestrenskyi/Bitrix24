<?php
$q = new \Bitrix\Main\Entity\Query(CAcounttingEntriesTable::getEntity());
$q->setSelect(['*']);
$q->setFilter(
    [
        'LOGIC' => 'AND',
        [
        'ENTITY_ID'=>$res['ID'],
        'DATE_TIME'=>new \Bitrix\Main\Type\DateTime(date('d.m.Y'))
        ],
        'LOGIC' => 'OR',
        [
        '=OPERATION_VALUE_CODE'=>'2208_OTHER_1001',
        '=OPERATION_VALUE_CODE'=>'2208_OTHER_1101'
        ]
    ]
);
$sql = $q->getQuery();
