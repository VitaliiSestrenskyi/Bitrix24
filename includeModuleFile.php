<?php

function setAutoloadList ()
{
    $moduleAlias    = "\\Itua\\XXX";
    $fullDirModule  = $_SERVER['DOCUMENT_ROOT'].'/local/modules/itua.afa/lib';
    $directory      = new RecursiveDirectoryIterator($fullDirModule);
    $iterator       = new RecursiveIteratorIterator($directory);
    $autoloadList   = [];
    foreach($iterator as $entry)
    {
        if($entry->isFile())
        {
            $dirModule = str_replace($fullDirModule,"",$entry->getRealpath());
            $dirModule = str_replace("/", "\\", $dirModule);
            $dirModule = str_replace(".php", "", $dirModule);
            $expArray = explode("\\", $dirModule);
            $ucExpArray = [];
            foreach ($expArray as $k=>$item)
                $ucExpArray[] = ucfirst($item);

            $namespace = $moduleAlias.implode("\\", $ucExpArray);
            if(strstr($namespace, "Model"))
                $namespace = $namespace."Table";

            $pathFile = str_replace($fullDirModule,"","lib".$entry->getRealpath());
            $autoloadList[$namespace] = $pathFile;
        }
    }

    return $autoloadList;
}


\Bitrix\Main\Loader::registerAutoLoadClasses("itua.xxx",setAutoloadList());
