<?
http://dev.1c-bitrix.ru/community/blogs/hazz/im-post-one.php
//отправка уведомления пользователю
$arFields = array(

"MESSAGE_TYPE" => "S", # P - private chat, G - group chat, S - notification
"TO_USER_ID" => 8,
"FROM_USER_ID" => 5,
"MESSAGE" => "test",
"AUTHOR_ID" => 5,
"EMAIL_TEMPLATE" => "some",

"NOTIFY_TYPE" => 2,  # 1 - confirm, 2 - notify single from, 4 - notify single
"NOTIFY_MODULE" => "main", # module id sender (ex: xmpp, main, etc)
"NOTIFY_EVENT" => "IM_GROUP_INVITE", # module event id for search (ex, IM_GROUP_INVITE)
"NOTIFY_TITLE" => "title to send email", # notify title to send email
);
CModule::IncludeModule('im');
if(CIMMessenger::Add($arFields))
    echo "success";
else
    echo "error";
