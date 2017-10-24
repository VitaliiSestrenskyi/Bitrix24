<?php
//lang uf fields
$rsEntity = CUserTypeEntity::GetList(
    [],
    [
        'FIELD_NAME'=> ['UF_CENTER', 'UF_LANG_COMMUNIC'],
        'LANG'=>LANGUAGE_ID
    ]
);
$arEntitiesEditFormLabels = [];
$test = [];
while ($res = $rsEntity->fetch())
{
    $test[] = $res;

    $arEntitiesEditFormLabels[] = $res['EDIT_FORM_LABEL'];
}
