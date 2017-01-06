<?
$events = scandir(__DIR__.'/events');
foreach ($events as $key => $event_file) 
{
    if( strpos( $event_file, '.php' ) )
        require_once __DIR__."/events/".$event_file;
}
