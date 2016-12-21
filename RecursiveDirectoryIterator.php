<?php
//http://php.net/manual/ru/class.splfileinfo.php
//http://php.net/manual/en/class.recursivedirectoryiterator.php

$it 		= new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'].'/test');
$iterator 	= new RecursiveIteratorIterator($it);
$fileArray = iterator_to_array($iterator, true);

foreach( $fileArray as $dir=>$values )
{ 

}
