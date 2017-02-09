<?php
//написать в чат
if(Bitrix\Main\Loader::includeModule('im'))
{
    \CIMChat::AddMessage([
        'FROM_USER_ID'=>1,//id автора
        'TO_CHAT_ID'=>19, //id чата
        'MESSAGE'=>'пишу в чат №19'
    ]);
}
if(Bitrix\Main\Loader::includeModule('im'))
{
    \CIMChat::AddSystemMessage([
        'FROM_USER_ID'=>1,//id автора
        'TO_CHAT_ID'=>19, //id чата
        'MESSAGE'=>'системное уведомления'
    ]);
}
//написать в чат лично
if(Bitrix\Main\Loader::includeModule('im'))
{
    \CIMMessage::Add([
        'FROM_USER_ID'=>1,//id автора
        'TO_USER_ID'=>19, //id чата
        'MESSAGE'=>'Пишу в чат тебе'
    ]);
}
//disk
//получить хранилище
if(Bitrix\Main\Loader::includeModule('disk'))
{
    //https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=48&LESSON_ID=2746
    $driver = Bitrix\Disk\Driver::getInstance();
    $storage = $driver->getStorageByUserId(1); //пользователя
    $storage = $driver->getStorageByGroupId(33); //группы
    $storage = $driver->getStorageByCommonId('shared_files_s1'); //идентификатор
    $storage = \Bitrix\Disk\Storage::loadById($storageId); //знаем  идентификатор хранилища
    if($storage)
    {
        //работаем с хранилищем
    }
}
//создаем папку в хранилище
if(Bitrix\Main\Loader::includeModule('disk'))
{
    $storage = Bitrix\Disk\Driver::getInstance()->getStorageByUserId(1);
    if($storage)
    {
        $folder = $storage->addFolder([
            'NAME'=>'New Folder',
            'CREATED_BY'=>1
        ]);
    }
}
//ищем подпапку и работаем с ней
if(Bitrix\Main\Loader::includeModule('disk'))
{
    $storage = Bitrix\Disk\Driver::getInstance()->getStorageByUserId(1);
    if($storage)
    {
        //как то получаем папку
        $folder = $folder->getChild([
            '=NAME'=>'New Folder',
            'TYPE'=>\Bitrix\Disk\Internals\FolderTable::TYPE_FOLDER //17.0.2
        ]);
    }
}
//загружаем файл в папку
if(Bitrix\Main\Loader::includeModule('disk'))
{
    $storage = Bitrix\Disk\Driver::getInstance()->getStorageByUserId(1);
    if($storage)
    {
        //как то получаем папку
        $folder = $folder->getChild([
            '=NAME'=>'New Folder',
            'TYPE'=>\Bitrix\Disk\Internals\FolderTable::TYPE_FOLDER //17.0.2
        ]);
        if($folder)
        {
            $fileArray = \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].'/test.png');
            $file = $folder->uploadFile($fileArray, ['CREATED_BY'=>1]);
        }
    }
}
//поиск файла для работы с ним
if(Bitrix\Main\Loader::includeModule('disk'))
{
    $storage = Bitrix\Disk\Driver::getInstance()->getStorageByUserId(1);
    if($storage)
    {
        $folder = $storage->getRootObject();
        $file = $folder->getChild([
            '=NAME'=>'test.png',
            'TYPE'=>\Bitrix\Disk\Internals\FileTable::TYPE_FILE
        ]);
        if($file)
        {
            //
        }
    }
}
//получение ссылки на файл на портале
if($file)
{
    $urlManager = \Bitrix\Disk\Driver::getInstance()->getUrlManager();
    echo $urlManager->getPathFileDetail($file);
}
//получение публичной ссылки
if($file)
{
    $urlManager = \Bitrix\Disk\Driver::getInstance()->getUrlManager();
    $extLink = $file->addExternalLink([
        'CREATED_BY'=>1,
        'TYPE'=>\Bitrix\Disk\Internals\ExternalLinkTable::TYPE_MANUAL,
    ]);
    $exLinkUrl = $urlManager->getShortExternalLink([
        'hash'=>$extLink->getHash(),
        'action'=>'default'
    ], true);
    echo $exLink->getHash().'<br>';
    echo $exLinkUrl;
}
//еще работа с файлами
//загрузка новой версии файла
if($file)
{
    $fileArray = \CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].'/test.png');
    $newVersion = $file->uploadVersion($fileArray, 1); //файл, ID пользователя
}
//перемещение файла в другую папку
if($file)
{
    $file->moveTo($folder, $movedBy); //обьект папки,  пользователь
}
//проверка обьекта и вывод ошибок
if($folder)
{
    $folder->rename('Reports.backup');
}
else
{
    var_dump($folder->getErrors());
}

//плохо
FolderTable::update();
//хорошо
$folder->rename('New Folder2');


//===============CRM===================
//CCrmLead
//CCrmDeal
//CCrmCompany
//CCrmContact
//CCrmQuote - предложения
//CCrmInvoice - счета

//Помним
//По умолчанию права проверяються. Чтобы отменить проверку, в фильтре надо передать CHECK_PERMISSIONS=>'N'

//Добавления простого товара
if(Bitrix\Main\Loader::includeModule('crm'))
{
    $rows = [];
    $rows[] = [
        'PRODUCT_NAME'=>'TEST_NAME',
        'QUANTITY'=>2,
        'PRICE'=>300,
        'MEASURE_CODE'=>796
    ];
    $rows[] = [
        'PRODUCT_NAME'=>'TEST_NAME2',
        'QUANTITY'=>2,
        'PRICE'=>600,
        'MEASURE_CODE'=>796
    ];
    CCrmProductRow::SaveRows('D', 10, $rows);//сделка
    CCrmProductRow::SaveRows('L', 8, $rows);//лид
    CCrmProductRow::SaveRows('Q', 1, $rows);//предложение
    //счета
    CCrmInvoice::add([
        'ORDER_TOPIC'=>'Новый счет',
        'PRODUCT_ROWS'=>$rows
    ]);
}
//Измерения
if(Bitrix\Main\Loader::includeModule('crm'))
{
    //получение списка измерений
    \Bitrix\Crm\Measure::getMeasures();
    //получение одного измерения по умолчанию
    \Bitrix\Crm\Measure::getDefaultMeasure();
}
//Права
if(Bitrix\Main\Loader::includeModule('crm'))
{
    CCrmProductRow::SaveRows('D', 10, $rows, null, false);
}
//Добавление товара в каталог
if(Bitrix\Main\Loader::includeModule('crm'))
{
    $pid = CCrmProduct::Add([
        'NAME'=>'Товар в базе',
        'QUANTITY'=>1,
        'PRICE'=>100,
        'MEASURE_CODE'=>796,
        'CURRENCY_ID'=>'UAH'
    ]);
    if($pid)
    {
        $rows=[];
        $rows[]=[
            'PRODUCT_ID'=>$pid,
            'QUANTITY'=>1
        ];
        CCrmProductRow::SaveRows('D', 10, $rows);
    }
}
//Справочники
if(Bitrix\Main\Loader::includeModule('crm'))
{
    CCrmStatus::GetStatus($code);
    //получаем все коды
    //select entity_id form b_crm_status group by entity_id
}
//Добавление дел
if(Bitrix\Main\Loader::includeModule('crm'))
{
    $fields = [
        'TYPE_ID'=>CCrmActivityType::Call, //тип дела Meeting, Call, Task, Email
        'OWNER_TYPE_ID'=>CCrmOwnerType::Deal, //тип основной сущности,  к которой привязывается дело,  может быть Deal, Lead, Contact, Company
        'OWNER_ID'=>10,//ID основной сущности
        'SUBJECT'=>'Телефонный разговор исходящий',
        'START_TIME'=>$date,
        'END_TIME'=>$date,
        'COMPLETED'=>'Y',//флаг завершенности дела
        'RESPONSIBLE_ID'=>1,//ответственный
        'PRIORITY'=>CCrmActivityPriority::Medium,//приоритет,  может быть None, Low, Medium, High
        'DIRECTION'=>CCrmActivityDirection::Outgoing,//фктуально для типов дел,  где может быть направление (входящий или исходящий - телефон или email) может быть Outgoing, Incoming
        'BINDINGS'=>[//возможность сделать множественную привязку - звонок привязан к сделке(основная привязка), но еще относится к контакту с КЕМ звонок
            [
                'OWNER_TYPE_ID'=>CCrmOwnerType::Deal,
                'OWNER_ID'=>666
            ]
        ],
        'ORIGIN_ID'=>'XYZ_1'//некий внешний индефикатор ,  опционально
    ];
    CCrmActivity::Add($fields, true);
}
//Подключение JS и CSS
AddEventHandler('main', 'OnEpilog', function (){
    $arJsConfig = [
        'custom_main'=>[
            'js'=>'/bitrix/js/custom/main.js',
            'css'=>'/bitrix/js/custom/main.css',
            'rel'=>[]
        ]
    ];
    foreach ($arJsConfig as $ext=>$arExt)
    {
        \CJSCore::RegisterExt($ext, $arExt);
    }

    CUtil::InitJSCore(['custom_main']);
});

/*
//код исполняем когда Дом загружен
BX.ready(function(){
    var editButton = BX.findChild(
        BX('task-view-buttons'),//для родителя
        {
            tag: 'a',
            className: 'task-view-button edit'
        },
        true //поиск рекурсивно от родителя
    );

    if(editButton)
    {
        var href = window.location.href, matches, taskId;

        //узнаем id задачи из url
        if(matches == href.match(/\/task\/view\/([\d]+)\//i))
        {
            taskId = matches[1];
        }

        //создаем кнопку
        var newButton = BX.create('a',{
            attrs:{
                href: href+(href.indexOf('?') === -1 ? '?' : '&') + 'task=' + taskId + '&' + 'pdf=1&sessid=' + BX.bitrix_sessid(),
                className: 'task-view-button edit xxxxx'
            },
            text: 'Скачать как PDF'
        });

        //вставляем кнопку
        BX.insertAfter(newButton, editButton);

    }
    
});


*/
