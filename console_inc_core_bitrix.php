#!/usr/bin/php
<?php
set_time_limit(0);
ini_set("mbstring.func_overload", "2");
ini_set("memory_limit","1024M");
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www/";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("LANG", "s1");
define("BX_UTF", true);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_BUFFER_USED", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
while (ob_get_level())
ob_end_flush();
$startExecTime = getmicrotime();
// Блок действий //



// \Конец Блока действий //
echo "\nScript works " . (getmicrotime() — $startExecTime) . " sec\n";
require($_SERVER["DOCUMENT_ROOT"]. "/bitrix/modules/main/include/epilog_after.php");
?>
