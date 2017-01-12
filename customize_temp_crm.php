if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
 
//Определяем папочку дефолтного шаблона
$defTemplateFolder = $_SERVER["DOCUMENT_ROOT"] . $componentPath . "/templates/.default/"; 
 
//Подключаем из ядра ланг файлы, потому что удалили в нашем шаблоне
Loc::loadMessages( $defTemplateFolder . "/template.php" );
 
//CSS + JS тоже из ядра, т.е. дефолтного шаблона, в который Битриксы фигачат обновления. Эта конструкция работает и с кешем тоже
$this->addExternalCss($defTemplateFolder."/style.css");
$this->addExternalJS($defTemplateFolder."/script.js");
 
//Подключаем сам шаблон.
require $defTemplateFolder.'/template.php';   
