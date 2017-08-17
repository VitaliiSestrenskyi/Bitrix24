<?php
function getFinalPriceInCurrency($item_id, $cnt = 1, $getName="N", $sale_currency = 'UAH')
    {
        Loader::includeModule("iblock");
        Loader::includeModule("catalog");
        Loader::includeModule("sale");

        global $USER;

        // Проверяем, имеет ли товар торговые предложения?
        if(CCatalogSku::IsExistOffers($item_id))
        {
            // Пытаемся найти цену среди торговых предложений
            $res = CIBlockElement::GetByID($item_id);

            if($ar_res = $res->GetNext())
            {
                $productName = $ar_res["NAME"];
                if(isset($ar_res['IBLOCK_ID']) && $ar_res['IBLOCK_ID'])
                {
                    // Ищем все тогровые предложения
                    $offers = CIBlockPriceTools::GetOffersArray(array(
                        'IBLOCK_ID' => $ar_res['IBLOCK_ID'],
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'CHECK_PERMISSIONS' => 'Y'
                    ), array($item_id), null, null, null, null, null, null, array('CURRENCY_ID' => $sale_currency), $USER->getId(), null);

                    foreach($offers as $offer)
                    {
                        $price = CCatalogProduct::GetOptimalPrice($offer['ID'], $cnt, $USER->GetUserGroupArray(), 'N');
                        if(isset($price['PRICE']))
                        {
                            $final_price = $price['PRICE']['PRICE'];
                            $currency_code = $price['PRICE']['CURRENCY'];

                            // Ищем скидки и высчитываем стоимость с учетом найденных
                            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N");
                            if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
                                $final_price = CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
                            }

                            // Конец цикла, используем найденные значения
                            break;
                        }
                    }
                }
            }
        }
        else
        {
            // Простой товар, без торговых предложений (для количества равному $cnt)
            $price = CCatalogProduct::GetOptimalPrice($item_id, $cnt, $USER->GetUserGroupArray(), 'N');

            // Получили цену?
            if(!$price || !isset($price['PRICE'])) {
                return false;
            }

            // Меняем код валюты, если нашли
            if(isset($price['CURRENCY']))
            {
                $currency_code = $price['CURRENCY'];
            }
            if(isset($price['PRICE']['CURRENCY']))
            {
                $currency_code = $price['PRICE']['CURRENCY'];
            }

            // Получаем итоговую цену
            $final_price = $price['PRICE']['PRICE'];

            // Ищем скидки и пересчитываем цену товара с их учетом
            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", 2);
            if(is_array($arDiscounts) && sizeof($arDiscounts) > 0)
            {
                $final_price = CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
            }

            if($getName=="Y")
            {
                $res = CIBlockElement::GetByID($item_id);
                $ar_res = $res->GetNext();
                $productName = $ar_res["NAME"];
            }
        }

        // Если необходимо, конвертируем в нужную валюту
        if($currency_code != $sale_currency)
        {
            $final_price = CCurrencyRates::ConvertCurrency($final_price, $currency_code, $sale_currency);
        }

        $arRes = array(
            "PRICE"=>$price['PRICE']['PRICE'],
            "FINAL_PRICE"=>$final_price,
            "CURRENCY"=>$sale_currency,
            "DISCOUNT"=>$arDiscounts,
        );

        if($productName!="")
            $arRes['NAME']= $productName;

        return $arRes;
    }
