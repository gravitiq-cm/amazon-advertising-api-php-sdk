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

    const REPORT_PERIOD_DATE_RANGE = 'X';
    const REPORT_PERIOD_DAILY = 'D';
    const REPORT_PERIOD_WEEKLY = 'W';
    const REPORT_PERIOD_MONTHLY = 'M';
    const REPORT_PERIOD_YEARLY = 'Y';
    const REPORT_PERIOD_HOURLY = 'H';

    const MAX_RANGE_DAYS = 120;

    const REPORT_PERIOD_FIELDS = [
        self::REPORT_PERIOD_DATE_RANGE => ['dateRange.value'],
        self::REPORT_PERIOD_DAILY => ['date.value'],
        self::REPORT_PERIOD_WEEKLY => ['year.value', 'week.value'],
        self::REPORT_PERIOD_MONTHLY => ['year.value', 'month.value'],
        self::REPORT_PERIOD_YEARLY => ['year.value'],
        self::REPORT_PERIOD_HOURLY => ['hour.value'],
    ];

    const DISALLOWED_REPORT_PERIODS_BY_REPORT_TYPE = [
        self::REPORT_TYPE_SEARCH_TERM_IMPRESSION_SHARE => [self::REPORT_PERIOD_HOURLY],
        self::REPORT_TYPE_CAMPAIGN_TOP_OF_SEARCH_IMPRESSION_SHARE => [self::REPORT_PERIOD_HOURLY],
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

    public static function getFieldsForReportPeriod(string $reportPeriod): array
    {
        if (!isset(self::REPORT_PERIOD_FIELDS[$reportPeriod])) {
            throw new \InvalidArgumentException("Unknown reportPeriod: {$reportPeriod}");
        }
        return self::REPORT_PERIOD_FIELDS[$reportPeriod];
    }

    public static function getDefaultReportPeriod(): string
    {
        return self::REPORT_PERIOD_DATE_RANGE;
    }

    public static function isReportPeriodAllowedForReportType(string $reportPeriod, string $reportType): bool
    {
        if (!isset(self::REPORT_PERIOD_FIELDS[$reportPeriod])) {
            throw new \InvalidArgumentException("Unknown reportPeriod: {$reportPeriod}");
        }
        if (!isset(self::DISALLOWED_REPORT_PERIODS_BY_REPORT_TYPE[$reportType])) {
            throw new \InvalidArgumentException("Unknown reportType: {$reportType}");
        }
        return !in_array($reportPeriod, self::DISALLOWED_REPORT_PERIODS_BY_REPORT_TYPE[$reportType], true);
    }

    public static function getMaxDateRangeDays(string $reportType, string $reportPeriod): int
    {
        if (!self::isReportPeriodAllowedForReportType($reportPeriod, $reportType)) {
            throw new \InvalidArgumentException("reportPeriod {$reportPeriod} is not allowed for reportType {$reportType}");
        }

        return self::MAX_RANGE_DAYS;
    }
}
