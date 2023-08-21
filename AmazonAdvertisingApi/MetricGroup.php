<?php

namespace AmazonAdvertisingApi;

class MetricGroup
{
    const CAMPAIGN_SPRODUCT_ALL                 = 'c_sp_all';
    const CAMPAIGN_SPRODUCT_ALLV3               = 'c_sp_all_v3';
    const CAMPAIGN_SBRAND_ALL                   = 'c_sb_all';
    const CAMPAIGN_SBVIDEO_ALL                  = 'c_sbv_all';
    const CAMPAIGN_SDISPLAY_ALL                 = 'c_sd_all';
    const PLACEMENT_SPRODUCT_ALL                = 'pl_sp_all';
    const PLACEMENT_SPRODUCT_ALLV3              = 'pl_sp_all_v3';
    const PLACEMENT_SBRAND_ALL                  = 'pl_sb_all';
    const PLACEMENT_SBVIDEO_ALL                 = 'pl_sbv_all';
    const AD_GROUP_SPRODUCT_ALL                 = 'ag_sp_all';
    const AD_GROUP_SPRODUCT_ALLV3               = 'ag_sp_all_v3';
    const AD_GROUP_SBRAND_ALL                   = 'ag_sb_all';
    const AD_GROUP_SBVIDEO_ALL                  = 'ag_sbv_all';
    const AD_GROUP_SDISPLAY_ALL                 = 'ag_sd_all';
    const PRODUCT_AD_SPRODUCT_ALL               = 'pa_sp_all';
    const PRODUCT_AD_SPRODUCT_ALLV3             = 'pa_sp_all_v3';
    const PRODUCT_AD_SDISPLAY_ALL               = 'pa_sd_all';
    const TARGET_SPRODUCT_ALL                   = 't_sp_all';
    const TARGET_SPRODUCT_ALLV3                 = 't_sp_all_v3';
    const TARGET_SBRAND_ALL                     = 't_sb_all';
    const TARGET_SBVIDEO_ALL                    = 't_sbv_all';
    const TARGET_SDISPLAY_ALL                   = 't_sd_all';
    const KEYWORD_SPRODUCT_ALL                  = 'kw_sp_all';
    const KEYWORD_SPRODUCT_ALLV3                = 'kw_sp_all_v3';
    const KEYWORD_SBRAND_ALL                    = 'kw_sb_all';
    const KEYWORD_SBVIDEO_ALL                   = 'kw_sbv_all';
    const SEARCH_TERM_KEYWORD_SBRAND_ALL        = 'st_kw_sb_all';
    const SEARCH_TERM_KEYWORD_SBVIDEO_ALL       = 'st_kw_sbv_all';
    const SEARCH_TERM_KEYWORD_SPRODUCT_ALL      = 'st_kw_sp_all';
    const SEARCH_TERM_KEYWORD_SPRODUCT_ALLV3    = 'st_kw_sp_all_v3';
    const SEARCH_TERM_TARGET_SPRODUCT_ALL       = 'st_tar_sp_all';
    const SEARCH_TERM_TARGET_SPRODUCT_ALLV3     = 'st_tar_sp_all_v3';
    const ASIN_KEYWORD_SPRODUCT_ALL             = 'asin_kw_sp_all';
    const ASIN_KEYWORD_SPRODUCT_ALLV3           = 'asin_kw_sp_all_v3';
    const ASIN_TARGET_SPRODUCT_ALL              = 'asin_tar_sp_all';
    const ASIN_TARGET_SPRODUCT_ALLV3            = 'asin_tar_sp_all_v3';
    const ASIN_SPRODUCT_ALLV3                   = 'asin_sp_all_v3';
    const ASIN_SDISPLAY_ALL                     = 'asin_sd_all';
    const AD_SBRAND_ALL                         = 'ad_sb_all';  // includes video
    const MATCHED_TARGET_CAMPAIGNS_SDISPLAY_ALL = 'mt_cam_sd_all';
    const MATCHED_TARGET_AD_GROUPS_SDISPLAY_ALL = 'mt_adg_sd_all';
    const MATCHED_TARGET_TARGETS_SDISPLAY_ALL   = 'mt_tar_sd_all';
    const PURCHASED_PRODUCT_SPRODUCT_ALLV3      = 'pp_sp_all_v3';
    const PURCHASED_PRODUCT_SBRAND_ALLV3        = 'pp_sb_all_v3';


    const PRODUCT_AD_SP_ALL                     = 'pa_sp_all';

    // Attribution Reports
    const AD_GROUP_ATTR_PRODUCT_ALLV3           = 'ag_attrp_all_v3';
    const AD_GROUP_ATTR_PERFORMANCE_ALLV3       = 'ag_attrf_all_v3';
    const CAMPAIGN_ATTR_PERFORMANCE_ALLV3       = 'c_attrf_all_v3';
    const CREATIVE_ATTR_PERFORMANCE_ALLV3       = 'cr_attrf_all_v3';

    /**
     * @throws \Exception
     */
    public static function getMetricGroup($groupName, $strictMode=false)
    {
        if ($strictMode && strpos($groupName, ',') !== false) {
            return $groupName; // assume it's an arbitrary strings of metrics
        }
        if (self::CAMPAIGN_SPRODUCT_ALL === $groupName) {
            return 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,currency,impressions';
        } elseif (self::CAMPAIGN_SPRODUCT_ALLV3 === $groupName) {
            return 'attributedSalesSameSku1d,attributedSalesSameSku7d,attributedSalesSameSku14d,attributedSalesSameSku30d,campaignApplicableBudgetRuleId,campaignApplicableBudgetRuleName,campaignBiddingStrategy,campaignBudgetAmount,campaignBudgetCurrencyCode,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudgetAmount,campaignStatus,clicks,cost,impressions,purchases1d,purchases7d,purchases14d,purchases30d,purchasesSameSku1d,purchasesSameSku7d,purchasesSameSku14d,purchasesSameSku30d,sales1d,sales7d,sales14d,sales30d,date,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,unitsSoldSameSku1d,unitsSoldSameSku7d,unitsSoldSameSku14d,unitsSoldSameSku30d';
        } elseif (self::CAMPAIGN_SBRAND_ALL === $groupName) {
            return 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d';
        } elseif (self::CAMPAIGN_SBVIDEO_ALL === $groupName) {
            return 'attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d';
        } elseif (self::CAMPAIGN_SDISPLAY_ALL === $groupName) {
            return 'attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignBudget,campaignId,campaignName,campaignStatus,clicks,cost,costType,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d';
        } elseif (self::PLACEMENT_SPRODUCT_ALL === $groupName) {
            return 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,bidPlus,campaignBudget,campaignBudgetType,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,currency,impressions';
        } elseif (self::PLACEMENT_SPRODUCT_ALLV3 === $groupName) {
            return 'placementClassification,attributedSalesSameSku1d,attributedSalesSameSku7d,attributedSalesSameSku14d,attributedSalesSameSku30d,campaignApplicableBudgetRuleId,campaignApplicableBudgetRuleName,campaignBiddingStrategy,campaignBudgetAmount,campaignBudgetCurrencyCode,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudgetAmount,campaignStatus,clicks,cost,impressions,purchases1d,purchases7d,purchases14d,purchases30d,purchasesSameSku1d,purchasesSameSku7d,purchasesSameSku14d,purchasesSameSku30d,sales1d,sales7d,sales14d,sales30d,date,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,unitsSoldSameSku1d,unitsSoldSameSku7d,unitsSoldSameSku14d,unitsSoldSameSku30d';
        } elseif (self::PLACEMENT_SBRAND_ALL === $groupName) {
            return 'applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d';
        } elseif (self::PLACEMENT_SBVIDEO_ALL === $groupName) {
            return 'attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignName,campaignStatus,clicks,cost,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d';
        } elseif (self::AD_GROUP_SPRODUCT_ALL === $groupName) {
            return 'adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions';
        } elseif (self::AD_GROUP_SPRODUCT_ALLV3 === $groupName) {
            return 'adGroupId,adGroupName,adStatus,attributedSalesSameSku1d,attributedSalesSameSku7d,attributedSalesSameSku14d,attributedSalesSameSku30d,campaignApplicableBudgetRuleId,campaignApplicableBudgetRuleName,campaignBiddingStrategy,campaignBudgetAmount,campaignBudgetCurrencyCode,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudgetAmount,campaignStatus,clicks,cost,impressions,purchases1d,purchases7d,purchases14d,purchases30d,purchasesSameSku1d,purchasesSameSku7d,purchasesSameSku14d,purchasesSameSku30d,sales1d,sales7d,sales14d,sales30d,date,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,unitsSoldSameSku1d,unitsSoldSameSku7d,unitsSoldSameSku14d,unitsSoldSameSku30d';
        } elseif (self::AD_GROUP_SBRAND_ALL === $groupName) {
            return 'adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d';
        } elseif (self::AD_GROUP_SBVIDEO_ALL === $groupName) {
            return 'adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d';
        } elseif (self::AD_GROUP_SDISPLAY_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,bidOptimization,campaignId,campaignName,clicks,cost,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d';
        } elseif (self::PRODUCT_AD_SPRODUCT_ALL === $groupName) {
            return 'adGroupId,adGroupName,asin,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,sku';
        } elseif (self::PRODUCT_AD_SPRODUCT_ALLV3 === $groupName) {
            return 'date,campaignName,campaignId,adGroupName,adGroupId,adId,portfolioId,impressions,clicks,cost,campaignBudgetCurrencyCode,campaignBudgetAmount,campaignBudgetType,campaignStatus,advertisedAsin,advertisedSku,purchases1d,purchases7d,purchases14d,purchases30d,purchasesSameSku1d,purchasesSameSku7d,purchasesSameSku14d,purchasesSameSku30d,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,sales1d,sales7d,sales14d,sales30d,attributedSalesSameSku1d,attributedSalesSameSku7d,attributedSalesSameSku14d,attributedSalesSameSku30d,salesOtherSku7d,unitsSoldSameSku1d,unitsSoldSameSku7d,unitsSoldSameSku14d,unitsSoldSameSku30d,unitsSoldOtherSku7d,acosClicks7d,acosClicks14d';
        } elseif (self::PRODUCT_AD_SDISPLAY_ALL === $groupName) {
            return 'adGroupId,adGroupName,adId,asin,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,sku,viewAttributedConversions14d,viewImpressions,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d';
        } elseif (self::TARGET_SPRODUCT_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetingExpression,targetingText,targetingType';
        } elseif (self::TARGET_SPRODUCT_ALLV3 === $groupName || self::KEYWORD_SPRODUCT_ALLV3 === $groupName) {
            return 'impressions,clicks,cost,purchases1d,purchases7d,purchases14d,purchases30d,purchasesSameSku1d,purchasesSameSku7d,purchasesSameSku14d,purchasesSameSku30d,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,sales1d,sales7d,sales14d,sales30d,attributedSalesSameSku1d,attributedSalesSameSku7d,attributedSalesSameSku14d,attributedSalesSameSku30d,unitsSoldSameSku1d,unitsSoldSameSku7d,unitsSoldSameSku14d,unitsSoldSameSku30d,salesOtherSku7d,unitsSoldOtherSku7d,acosClicks7d,acosClicks14d,keywordId,campaignBudgetCurrencyCode,date,portfolioId,campaignName,campaignId,campaignBudgetType,campaignBudgetAmount,campaignStatus,keywordBid,adGroupName,adGroupId,adKeywordStatus,matchType,targeting';
        } elseif (self::TARGET_SBRAND_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,dpv14d,impressions,targetingExpression,targetingText,targetingType,unitsSold14d';
        } elseif (self::TARGET_SBVIDEO_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetingExpression,targetingText,targetingType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d';
        } elseif (self::TARGET_SDISPLAY_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,targetId,targetingExpression,targetingText,targetingType,viewImpressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d';
        } elseif (self::KEYWORD_SPRODUCT_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,keywordStatus,keywordText,matchType';
        } elseif (self::KEYWORD_SBRAND_ALL === $groupName) {
            return 'adGroupId,adGroupName,applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,keywordBid,keywordStatus,keywordText,matchType,searchTermImpressionRank,searchTermImpressionShare,unitsSold14d';
        } elseif (self::KEYWORD_SBVIDEO_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedSales14d,attributedSales14dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,keywordBid,keywordStatus,keywordText,matchType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d';
        } elseif (self::SEARCH_TERM_KEYWORD_SBRAND_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedSales14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,keywordBid,keywordStatus,keywordText,matchType,searchTermImpressionRank,searchTermImpressionShare';
        } elseif (self::SEARCH_TERM_KEYWORD_SBVIDEO_ALL === $groupName) { // Note that fields "vtr,dpv14d,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d" are not acepted for keyword reports
            return 'adGroupId,adGroupName,attributedConversions14d,attributedSales14d,campaignBudget,campaignBudgetType,campaignId,campaignStatus,clicks,cost,impressions,keywordBid,keywordStatus,keywordText,matchType,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr';
        } elseif (self::SEARCH_TERM_KEYWORD_SPRODUCT_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,currency,impressions,keywordStatus,keywordText,matchType';
        } elseif (self::SEARCH_TERM_TARGET_SPRODUCT_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dSameSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dSameSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dSameSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dSameSKU,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignStatus,clicks,cost,impressions,targetingExpression,targetingText,targetingType';
        } elseif (self::SEARCH_TERM_KEYWORD_SPRODUCT_ALLV3 === $groupName || self::SEARCH_TERM_TARGET_SPRODUCT_ALLV3 === $groupName) {
            return 'impressions,clicks,cost,purchases1d,purchases7d,purchases14d,purchases30d,purchasesSameSku1d,purchasesSameSku7d,purchasesSameSku14d,purchasesSameSku30d,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,sales1d,sales7d,sales14d,sales30d,attributedSalesSameSku1d,attributedSalesSameSku7d,attributedSalesSameSku14d,attributedSalesSameSku30d,unitsSoldSameSku1d,unitsSoldSameSku7d,unitsSoldSameSku14d,unitsSoldSameSku30d,salesOtherSku7d,unitsSoldOtherSku7d,acosClicks7d,acosClicks14d,keywordId,keyword,campaignBudgetCurrencyCode,date,portfolioId,searchTerm,campaignName,campaignId,campaignBudgetType,campaignBudgetAmount,campaignStatus,keywordBid,adGroupName,adGroupId,keywordType,adKeywordStatus';
        } elseif (self::ASIN_KEYWORD_SPRODUCT_ALL === $groupName) {
            return 'adGroupId,adGroupName,asin,attributedSales14dOtherSKU,attributedSales1dOtherSKU,attributedSales30dOtherSKU,attributedSales7dOtherSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dOtherSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dOtherSKU,campaignId,campaignName,currency,keywordId,keywordText,matchType,otherAsin,sku,targetingExpression,targetingType';
        } elseif (self::ASIN_TARGET_SPRODUCT_ALL === $groupName) {
            return 'adGroupId,adGroupName,asin,attributedSales14dOtherSKU,attributedSales1dOtherSKU,attributedSales30dOtherSKU,attributedSales7dOtherSKU,attributedUnitsOrdered14d,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered1d,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered30d,attributedUnitsOrdered30dOtherSKU,attributedUnitsOrdered7d,attributedUnitsOrdered7dOtherSKU,campaignId,campaignName,currency,matchType,otherAsin,sku,targetId,targetingExpression,targetingText,targetingType';
        } elseif (self::ASIN_SPRODUCT_ALLV3 === $groupName || self::ASIN_KEYWORD_SPRODUCT_ALLV3 === $groupName || self::ASIN_TARGET_SPRODUCT_ALLV3 === $groupName) {
            return 'date,portfolioId,campaignName,campaignId,adGroupName,adGroupId,keyword,keywordId,advertisedAsin,purchasedAsin,advertisedSku,campaignBudgetCurrencyCode,matchType,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,sales1d,sales7d,sales14d,sales30d,purchases1d,purchases7d,purchases14d,purchases30d,unitsSoldOtherSku1d,unitsSoldOtherSku7d,unitsSoldOtherSku14d,unitsSoldOtherSku30d,salesOtherSku1d,salesOtherSku7d,salesOtherSku14d,salesOtherSku30d,purchasesOtherSku1d,purchasesOtherSku7d,purchasesOtherSku14d,purchasesOtherSku30d';
        } elseif (self::ASIN_SDISPLAY_ALL === $groupName) {
            return 'adGroupId,adGroupName,asin,attributedSales14dOtherSKU,attributedSales1dOtherSKU,attributedSales30dOtherSKU,attributedSales7dOtherSKU,attributedUnitsOrdered14dOtherSKU,attributedUnitsOrdered1dOtherSKU,attributedUnitsOrdered30dOtherSKU,attributedUnitsOrdered7dOtherSKU,campaignId,campaignName,currency,otherAsin,sku';
        } elseif (self::AD_SBRAND_ALL === $groupName) {
            return 'adGroupId,adGroupName,applicableBudgetRuleId,applicableBudgetRuleName,attributedConversions14d,attributedConversions14dSameSKU,attributedDetailPageViewsClicks14d,attributedOrderRateNewToBrand14d,attributedOrdersNewToBrand14d,attributedOrdersNewToBrandPercentage14d,attributedSales14d,attributedSales14dSameSKU,attributedSalesNewToBrand14d,attributedSalesNewToBrandPercentage14d,attributedUnitsOrderedNewToBrand14d,attributedUnitsOrderedNewToBrandPercentage14d,campaignBudget,campaignBudgetType,campaignId,campaignName,campaignRuleBasedBudget,campaignStatus,clicks,cost,dpv14d,impressions,unitsSold14d,vctr,video5SecondViewRate,video5SecondViews,videoCompleteViews,videoFirstQuartileViews,videoMidpointViews,videoThirdQuartileViews,videoUnmutes,viewableImpressions,vtr';
        } elseif (self::MATCHED_TARGET_CAMPAIGNS_SDISPLAY_ALL === $groupName) {
            return 'attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignBudget,campaignId,campaignName,campaignStatus,clicks,cost,costType,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d';
        } elseif (self::MATCHED_TARGET_AD_GROUPS_SDISPLAY_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7dSameSKU,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,bidOptimization,campaignId,campaignName,clicks,cost,currency,impressions,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewImpressions,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d';
        } elseif (self::MATCHED_TARGET_TARGETS_SDISPLAY_ALL === $groupName) {
            return 'adGroupId,adGroupName,attributedConversions14d,attributedConversions14dSameSKU,attributedConversions1d,attributedConversions1dSameSKU,attributedConversions30d,attributedConversions30dSameSKU,attributedConversions7d,attributedConversions7dSameSKU,attributedDetailPageView14d,attributedOrdersNewToBrand14d,attributedSales14d,attributedSales14dSameSKU,attributedSales1d,attributedSales1dSameSKU,attributedSales30d,attributedSales30dSameSKU,attributedSales7d,attributedSales7dSameSKU,attributedSalesNewToBrand14d,attributedUnitsOrdered14d,attributedUnitsOrdered1d,attributedUnitsOrdered30d,attributedUnitsOrdered7d,attributedUnitsOrderedNewToBrand14d,campaignId,campaignName,clicks,cost,currency,impressions,targetId,targetingExpression,targetingText,targetingType,viewAttributedConversions14d,viewAttributedDetailPageView14d,viewAttributedSales14d,viewAttributedUnitsOrdered14d,viewAttributedOrdersNewToBrand14d,viewAttributedSalesNewToBrand14d,viewAttributedUnitsOrderedNewToBrand14d,attributedBrandedSearches14d,viewAttributedBrandedSearches14d';
        } elseif (self::PURCHASED_PRODUCT_SPRODUCT_ALLV3 === $groupName) {
            return 'date,startDate,endDate,portfolioId,campaignName,campaignId,adGroupName,adGroupId,keywordId,keyword,keywordType,advertisedAsin,purchasedAsin,advertisedSku,campaignBudgetCurrencyCode,matchType,unitsSoldClicks1d,unitsSoldClicks7d,unitsSoldClicks14d,unitsSoldClicks30d,sales1d,sales7d,sales14d,sales30d,purchases1d,purchases7d,purchases14d,purchases30d,unitsSoldOtherSku1d,unitsSoldOtherSku7d,unitsSoldOtherSku14d,unitsSoldOtherSku30d,salesOtherSku1d,salesOtherSku7d,salesOtherSku14d,salesOtherSku30d,purchasesOtherSku1d,purchasesOtherSku7d,purchasesOtherSku14d,purchasesOtherSku30d';
        } elseif (self::PURCHASED_PRODUCT_SBRAND_ALLV3 === $groupName) {
            return 'campaignId,adGroupId,date,startDate,endDate,campaignBudgetCurrencyCode,campaignName,adGroupName,attributionType,purchasedAsin,productName,productCategory,sales14d,orders14d,unitsSold14d,newToBrandSales14d,newToBrandPurchases14d,newToBrandUnitsSold14d,newToBrandSalesPercentage14d,newToBrandPurchasesPercentage14d,newToBrandUnitsSoldPercentage14d';
        } elseif (str_ends_with($groupName, '_attrp_all_v3')) {
            return 'attributedDetailPageViewsClicks14d,attributedAddToCartClicks14d,attributedPurchases14d,unitsSold14d,attributedSales14d,brandHaloDetailPageViewsClicks14d,brandHaloAttributedAddToCartClicks14d,brandHaloAttributedPurchases14d,brandHaloUnitsSold14d,brandHaloAttributedSales14d,attributedNewToBrandPurchases14d,attributedNewToBrandUnitsSold14d,attributedNewToBrandSales14d,brandHaloNewToBrandPurchases14d,brandHaloNewToBrandUnitsSold14d,brandHaloNewToBrandSales14d';
        } elseif (str_ends_with($groupName, '_attrf_all_v3')) {
            $metricList = 'Click-throughs,attributedDetailPageViewsClicks14d,attributedAddToCartClicks14d,attributedPurchases14d,unitsSold14d,attributedSales14d,attributedTotalDetailPageViewsClicks14d,attributedTotalAddToCartClicks14d,attributedTotalPurchases14d,totalUnitsSold14d,totalAttributedSales14d';
            if (self::CREATIVE_ATTR_PERFORMANCE_ALLV3 != $groupName) {
                $metricList .= ',brb_bonus_amount';
            }
            return $metricList;
        }

        throw new \InvalidArgumentException("$groupName is not a recognised Metric Group");
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function buildReportDataArrayFromMetricGroup($groupName, $dataArray=[], $isVendorProfile=false) {
        $dataArray['metrics'] = self::getMetricGroup($groupName);
        if ($isVendorProfile) {
            // Remove fields which are NOT for vendors
            $dataArray['metrics'] = str_replace(',sku,', ',', $dataArray['metrics']);
        } else {
            // Remove fields which are ONLY for vendors
            $dataArray['metrics'] = str_replace(',dpv14d,', ',', $dataArray['metrics']);
            $dataArray['metrics'] = str_replace(',unitsSold14d,', ',', $dataArray['metrics']);
        }
        return $dataArray;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function buildV3ReportDataArrayFromMetricGroup($groupName, $dataArray=[], $isVendorProfile=false) {
        $dataArray['columns'] = self::getMetricGroup($groupName);
        if ($isVendorProfile) {
            // Remove fields which are NOT for vendors
            $dataArray['columns'] = str_replace(',advertisedSku,', ',', $dataArray['columns']);
        } else {
            // Remove fields which are ONLY for vendors
            $dataArray['columns'] = str_replace(',unitsSold14d,', ',', $dataArray['columns']);
        }
        $dataArray['columns'] = explode(',', $dataArray['columns']);
        return $dataArray;
    }
}
