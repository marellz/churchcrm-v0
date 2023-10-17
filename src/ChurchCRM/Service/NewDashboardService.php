<?php

namespace ChurchCRM\Service;
class NewDashboardService
{

    public static function getDashboardItems($PageName)
    {
        $DashboardItems = [
            "ChurchCRM\Dashboard\EventsMenuItems",
            "ChurchCRM\Dashboard\ClassificationDashboardItem",
            "ChurchCRM\Dashboard\CurrentLocaleMetadata"
        ];
        $ReturnValues = [];
        foreach ($DashboardItems as $DashboardItem) {
            if ($DashboardItem::shouldInclude($PageName)) {
                array_push($ReturnValues, $DashboardItem);
            }
        }
        return $ReturnValues;
    }

    public static function getValues($PageName)
    {
        $ReturnValues = [];
        foreach (self::getDashboardItems($PageName) as $DashboardItem) {
            $ReturnValues[$DashboardItem::getDashboardItemName()] = $DashboardItem::getDashboardItemValue();
        }
        return $ReturnValues;
    }

}
