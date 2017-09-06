<?

class CAdminFormSettings
{
	public static function getTabsArray($formId)
	{
		$arCustomTabs = array();
		$customTabs = CUserOptions::GetOption("form", $formId);
		if($customTabs && $customTabs["tabs"])
		{
			$arTabs = explode("--;--", $customTabs["tabs"]);
			foreach($arTabs as $customFields)
			{
				if ($customFields == "")
					continue;

				$arCustomFields = explode("--,--", $customFields);
				$arCustomTabID = "";
				foreach($arCustomFields as $customField)
				{
					if($arCustomTabID == "")
					{
						list($arCustomTabID, $arCustomTabName) = explode("--#--", $customField);
						$arCustomTabs[$arCustomTabID] = array(
							"TAB" => $arCustomTabName,
							"FIELDS" => array(),
						);
					}
					else
					{
						list($arCustomFieldID, $arCustomFieldName) = explode("--#--", $customField);
						$arCustomFieldName = ltrim($arCustomFieldName, defined("BX_UTF")? "* -\xa0\xc2": "* -\xa0");
						$arCustomTabs[$arCustomTabID]["FIELDS"][$arCustomFieldID] = $arCustomFieldName;
					}
				}
			}
		}
		return $arCustomTabs;
	}

	public static function setTabsArray($formId, $arCustomTabs, $common = false, $userID = false)
	{
		$option = "";
		if (is_array($arCustomTabs))
		{
			foreach($arCustomTabs as $arCustomTabID => $arTab)
			{
				if (is_array($arTab) && isset($arTab["TAB"]))
				{
					$option .= $arCustomTabID.'--#--'.$arTab["TAB"];
					if (isset($arTab["FIELDS"]) && is_array($arTab["FIELDS"]))
					{
						foreach ($arTab["FIELDS"] as $arCustomFieldID => $arCustomFieldName)
						{
							$option .= '--,--'.$arCustomFieldID.'--#--'.$arCustomFieldName;
						}
					}
				}
				$option .= '--;--';
			}
		}
		CUserOptions::SetOption("form", $formId, array("tabs" => $option), $common, $userID);
	}
}
