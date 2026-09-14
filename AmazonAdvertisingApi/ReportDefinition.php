<?php

namespace AmazonAdvertisingApi;

/**
 * Field definitions for the Amazon Ads Reporting API v1 (referred to internally as "101"
 * to keep it distinct from Version 3 reporting).
 *
 * A reportType says what the report is about. A metricGroup is one concrete version of the
 * field list for that reportType; adding fields later means adding a new metricGroup rather
 * than editing an existing one, so already-parsed data stays attributable.
 */
class ReportDefinition
{
    const REPORT_TYPE_SEARCH_TERM_IMPRESSION_SHARE = 'SearchTermImpressionShare';
    const REPORT_TYPE_CAMPAIGN_TOP_OF_SEARCH_IMPRESSION_SHARE = 'CampaignTopOfSearchImpressionShare';

    const MG_ST_IS_V1 = 'st_is_v1';
    const MG_CAM_TOSIS_V1 = 'cam_tosis_v1';

    const FIELDS = [
        self::MG_ST_IS_V1 => [
            'adProduct.value',
            'advertiserAccount.id',
            'searchTerm.value',
            'budgetCurrency.value',
            'metric.impressionShare',
            'metric.impressionShareRank',
        ],
        self::MG_CAM_TOSIS_V1 => [
            'adProduct.value',
            'advertiserAccount.id',
            'campaign.id',
            'campaign.name',
            'budgetCurrency.value',
            'metric.topOfSearchImpressionShare',
        ],
    ];

    /** Ordered oldest first; the last entry is the default for that reportType. */
    const METRIC_GROUPS_BY_REPORT_TYPE = [
        self::REPORT_TYPE_SEARCH_TERM_IMPRESSION_SHARE => [self::MG_ST_IS_V1],
        self::REPORT_TYPE_CAMPAIGN_TOP_OF_SEARCH_IMPRESSION_SHARE => [self::MG_CAM_TOSIS_V1],
    ];

    const MAX_DATE_RANGE_DAYS_BY_REPORT_TYPE = [
        self::REPORT_TYPE_SEARCH_TERM_IMPRESSION_SHARE => 31,
        self::REPORT_TYPE_CAMPAIGN_TOP_OF_SEARCH_IMPRESSION_SHARE => 31,
    ];

    public static function getReportTypes(): array
    {
        return array_keys(self::METRIC_GROUPS_BY_REPORT_TYPE);
    }

    public static function getFieldsForMetricGroup(string $metricGroup): array
    {
        if (!isset(self::FIELDS[$metricGroup])) {
            throw new \InvalidArgumentException("Unknown metricGroup: {$metricGroup}");
        }
        return self::FIELDS[$metricGroup];
    }

    public static function getDefaultMetricGroupForReportType(string $reportType): string
    {
        if (!isset(self::METRIC_GROUPS_BY_REPORT_TYPE[$reportType])) {
            throw new \InvalidArgumentException("Unknown reportType: {$reportType}");
        }
        $metricGroups = self::METRIC_GROUPS_BY_REPORT_TYPE[$reportType];
        return end($metricGroups);
    }

    public static function getReportTypeForMetricGroup(string $metricGroup): string
    {
        foreach (self::METRIC_GROUPS_BY_REPORT_TYPE as $reportType => $metricGroups) {
            if (in_array($metricGroup, $metricGroups)) {
                return $reportType;
            }
        }
        throw new \InvalidArgumentException("Unknown metricGroup: {$metricGroup}");
    }

    public static function getMaxDateRangeDaysForReportType(string $reportType): int
    {
        if (!isset(self::MAX_DATE_RANGE_DAYS_BY_REPORT_TYPE[$reportType])) {
            throw new \InvalidArgumentException("Unknown reportType: {$reportType}");
        }
        return self::MAX_DATE_RANGE_DAYS_BY_REPORT_TYPE[$reportType];
    }
}
