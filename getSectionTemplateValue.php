<?php

if(!function_exists('getSectionTemplateValue'))
{
    function getSectionTemplateValue( $iblockId, $sectionId, $seoCode )
    {
        $obIpropTemlates = new \Bitrix\Iblock\InheritedProperty\SectionTemplates($iblockId, $sectionId);
        $arrIpropertyTemplates = $obIpropTemlates->findTemplates();
        $resultSection =  \Bitrix\Main\Text\String::htmlEncode(
            \Bitrix\Iblock\Template\Engine::process(
                new \Bitrix\Iblock\Template\Entity\Section($sectionId),
                $arrIpropertyTemplates[$seoCode]['TEMPLATE']
            )
        );
        return $resultSection;
    }
}


