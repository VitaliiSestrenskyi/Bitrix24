<?php
/** 
 * Standard bitrix variables
 * 
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 */
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

Loc::loadMessages(__FILE__);

/**
 * Accounting Entries Grid Add
 *
 * @package Components\CAccountingEntriesAddComponent
 */
class CAccountingEntriesAddComponent extends \CBitrixComponent
{
    /** @var CPHPCache $obCache */
    protected $obCache;
    protected $cache_id;
    protected $cache_path;
    protected $templateCachedData;
    protected $arVars;

   protected function setData()
    {
        try
        {
            $this->setRows();
        }
        catch (Exception $exception)
        {
            echo 'Error Afa - CAccountingEntriesAddComponent' . "<br/><br/>";
            echo 'Caught exception: '   .  $exception->getMessage() . "<br/>";
            echo 'Exception file: '     .  $exception->getFile() . "<br/>";
            echo 'Exception number: '   .  $exception->getLine() . "<br/>";
        }
    }


    public function onPrepareComponentParams($params)
    {
        $arPreparedParams = [];
        $request = Application::getInstance()->getContext()->getRequest();

        if(!empty($params['TABLE_NAME']))
            $arPreparedParams['TABLE_NAME'] = $params['TABLE_NAME'];
        else
            throw new SystemException('Empty table name');

        if(!empty($params['LIST_SHOW_FIELDS']))
            $arPreparedParams['LIST_SHOW_FIELDS'] = $params['LIST_SHOW_FIELDS'];
        else
            throw new SystemException('Empty show fields');

        if( !empty($request->getPost('AJAX_CALL')) )
            $arPreparedParams['AJAX_CALL'] = $request->getPost('AJAX_CALL');

        if(!empty($params['CACHE_TIME']))
            $arPreparedParams['CACHE_TIME'] = $params['CACHE_TIME'];

        if(!empty($params['CACHE_TYPE']))
            $arPreparedParams['CACHE_TYPE'] = $params['CACHE_TYPE'];

        if(!empty($params['CACHE_TIME']))
            $arPreparedParams['CACHE_TIME'] = $params['CACHE_TIME'];


        $this->params   = $arPreparedParams;
    }

    protected function setCache()
    {
        global $USER;

        $this->obCache = new CPHPCache;
        $this->cache_id = SITE_ID.'|'.$this->__name.'|'.serialize($this->arParams).'|'.$USER->GetGroups();
        $this->cache_path = '/'.SITE_ID.$this->getRelativePath();

        return $this->obCache->StartDataCache($this->params['CACHE_TIME'], $this->cache_id, $this->cache_path);
    }

    /**
     * Выполнение кода компонента и подключение шаблона
     */
    public function executeComponent()
    {
        $this->requestHandler();
        if ($this->setCache())
        {
            $this->setData();
            $this->SetResultCacheKeys(array(
                //"ROWS",
            ));
            $this->arResult = [
                'ROWS' => $this->getRows(),
            ];

            $this->includeComponentTemplate();

            $this->templateCachedData = $this->getTemplateCachedData();

            $this->obCache->EndDataCache(
                array(
                    'arResult' => $this->arResult,
                    'templateCachedData' => $this->templateCachedData
                )
            );
        }
        else
        {
            $this->arVars = $this->obCache->GetVars();
            $this->arResult = $this->arVars['arResult'];
            $this->initComponentTemplate();
            $this->setTemplateCachedData($this->arVars['templateCachedData']);
        }
    }
}
