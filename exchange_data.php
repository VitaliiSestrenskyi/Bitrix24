<?php

$fullDir        = $_SERVER["DOCUMENT_ROOT"]."/exchange_20_07_2017";
$directory      = new RecursiveDirectoryIterator($fullDir);
$iterator       = new RecursiveIteratorIterator($directory);
$count  = 0;
$name = [];
foreach($iterator as $entry)
{
    if($entry->isFile() && $entry->getExtension()=='xml' && strstr($entry->getFilename(), 'import') )
    {
        $count++;
        $xml = simplexml_load_file($entry->getRealpath());
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        if(isset($array['Каталог']['Товары']))
        {
            foreach ($array['Каталог']['Товары'] as $k=>$items)
            {
                $count += count($items);
            }
        }
        $name[$entry->getRealpath()] = $entry->getFilename();
    }
}
