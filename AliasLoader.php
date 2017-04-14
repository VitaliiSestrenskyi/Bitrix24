<?php
/**
 * Created by Vitalii Sestrenskyi.
 * User: ITUA
 * Date: 24.02.2017
 * Time: 11:12
 */

namespace Itua\Afa;

/**
 * Class AliasLoader - автозагрузчик namespace
 * @package Itua\Afa
 */
class AliasLoader
{
    private $aliases = [];

    private static $instance = null;
    private function __construct(){}
    protected function __clone(){}

    public static function getInstance()
    {
        if(is_null(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    private function setAliasLoader ()
    {
        $moduleAlias    = "\\Itua\\Afa";
        $fullDirModule  = $_SERVER['DOCUMENT_ROOT'].'/local/modules/itua.afa/lib';
        $directory      = new \RecursiveDirectoryIterator($fullDirModule);
        $iterator       = new \RecursiveIteratorIterator($directory);
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
                {
                    $namespace = $namespace."Table";
                    $alias = str_replace(".php", "", $entry->getFilename())."Table";
                }
                else
                {
                    $alias = str_replace(".php", "", $entry->getFilename());
                }

                $autoloadList[$alias] = $namespace;
            }
        }

        return $autoloadList;
    }

    /**
     * getAliases - массив namespaces и aliases
     *
     * @return array
     */
    private function getAliases()
    {
        return $this->aliases = array_merge([
            'Entity' => '\Bitrix\Main\Entity',
            'ExpressionField' => '\Bitrix\Main\Entity\ExpressionField',
            'DateTime' => 'Bitrix\Main\Type\DateTime',
            'Date' => 'Bitrix\Main\Type\Date',
        ], $this->setAliasLoader());
    }

    /**
     * register - регисрация массива namespaces и aliases
     */
    public function register()
    {
        foreach( $this->getAliases() as  $alias=> $full )
        {
            class_alias ( $full , $alias , true );
        }
    }
}
