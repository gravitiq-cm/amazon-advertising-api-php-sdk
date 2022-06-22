<?php

namespace AmazonAdvertisingApi;

class MetricGroup
{
    const SP_ALL = 'sp_all';

    /**
     * @throws \Exception
     */
    public static function getMetricGroup($groupName, $strictMode=false)
    {
        if ($strictMode && strpos($groupName, ',') !== false) {
            return $groupName; // assume it's an arbitrary strings of metrics
        }

        if (self::SP_ALL === $groupName) {
            return 'adGroupId,adGroupName,asin,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,sku';
        }

        throw new \InvalidArgumentException("$groupName is not a recognised Metric Group");
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function buildReportDataArrayFromMetricGroup($groupName, $dataArray=[]) {
        $dataArray['metrics'] = self::getMetricGroup($groupName);
        return $dataArray;
    }
}
