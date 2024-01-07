<?php

namespace AmazonAdvertisingApi;

require_once "Versions.php";
require_once "Regions.php";
require_once "CurlRequest.php";

class Client
{
    private const INTERFACE_REPORTS_V3 = 'reporting/reports';

    private $config = [
        "clientId" => null,
        "clientSecret" => null,
        "region" => null,
        "accessToken" => null,
        "refreshToken" => null,
        "sandbox" => false,
    ];

    private $apiVersion;
    private $applicationVersion;
    private $userAgent;
    private $endpoint = null;
    private $tokenUrl = null;
    private $requestId = null;
    private $endpoints;
    private $versionStrings;
    public $profileId = null;
    /*
    Also note that Amazon Attribution accounts are a separate type of "profile". Only Amazon Attribution profiles can be
    called within the Amazon Attribution API. When getting the Profiles resource, identify the correct Amazon Attribution
     profile by inspecting the "subType" property to ensure it's set to "AMAZON_ATTRIBUTION".
    https://advertising.amazon.com/API/docs/en-us/amazon-attribution-prod-3p/#/Advertisers
    */
    public $profileIdAttribution = null;
    private const ROUTE_ACCEPT_TYPE_MAPPING = [
        'sb/ads/creatives/list' => 'application/vnd.sbAdCreativeResource.v4+json'
    ];

    /**
     * @throws \Exception
     */
    public function __construct($config)
    {
        $regions = new Regions();
        $this->endpoints = $regions->endpoints;

        $versions = new Versions();
        $this->versionStrings = $versions->versionStrings;

        $this->apiVersion = $this->versionStrings["apiVersion"];
        $this->applicationVersion = $this->versionStrings["applicationVersion"];
        $this->userAgent = "AdvertisingAPI PHP Client Library v{$this->applicationVersion}";

        $this->_validateConfig($config);
        $this->_validateConfigParameters();
        $this->_setEndpoints();

        if (is_null($this->config["accessToken"]) && !is_null($this->config["refreshToken"])) {
            /* convenience */
            $this->doRefreshToken();
        }
    }

    /**
     * @throws \Exception
     */
    public function doRefreshToken()
    {
        $refresh_token = rawurldecode($this->config["refreshToken"]);

        $params = [
            "grant_type" => "refresh_token",
            "refresh_token" => $refresh_token,
            "client_id" => $this->config["clientId"],
            "client_secret" => $this->config["clientSecret"]
        ];

        $response = $this->_makeCurlTokenRequest($params);

        $response_array = json_decode($response["response"], true);
        if (!is_array($response_array)) {
            $this->_logAndThrow("Response expected to be JSON->array, but instead got: " . (is_null($response) ? 'NULL' : $response));
        } elseif (array_key_exists("access_token", $response_array)) {
            $this->config["accessToken"] = $response_array["access_token"];
        } else {
            $this->_logAndThrow("Unable to refresh token. 'access_token' not found in response. " . print_r($response, true));
        }

        return $response;
    }

    /**
     * @throws \Exception
     */
    public function convertAuthCodeToRefreshToken($authCode, $redirectUri)
    {
        $params = [
            "grant_type" => "authorization_code",
            "code" => $authCode,
            "redirect_uri" => $redirectUri,
            "client_id" => $this->config["clientId"],
            "client_secret" => $this->config["clientSecret"]
        ];

        $response = $this->_makeCurlTokenRequest($params);

        $response_array = json_decode($response["response"], true);
        if (array_key_exists("refresh_token", $response_array)) {
            $this->config["refreshToken"] = $response_array["refresh_token"];
            $this->config["accessToken"] = $response_array["access_token"];
        } else {
            $this->_logAndThrow("Unable to get initial refresh token. 'refresh_token' not found in response. " . print_r($response, true));
        }

        return [
            'refreshToken' => $this->config["refreshToken"],
            'response' => $response,
        ];
    }

    private function _makeCurlTokenRequest($params) {
        $headers = [
            "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
            "User-Agent: {$this->userAgent}"
        ];

        $data = '';
        foreach ($params as $k => $v) {
            $data .= "{$k}=" . rawurlencode($v) . "&";
        }

        $url = "https://{$this->tokenUrl}";

        $request = new CurlRequest();
        $request->setOption(CURLOPT_URL, $url);
        $request->setOption(CURLOPT_HTTPHEADER, $headers);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);
        $request->setOption(CURLOPT_POST, true);
        $request->setOption(CURLOPT_POSTFIELDS, rtrim($data, "&"));

        return $this->_executeRequest($request);
    }

    public function listProfiles()
    {
        return $this->_operation("profiles");
    }

    public function registerProfile($data)
    {
        return $this->_operation("profiles/register", $data, "PUT");
    }

    public function registerProfileStatus($profileId)
    {
        return $this->_operation("profiles/register/{$profileId}/status");
    }

    public function getProfile($profileId)
    {
        return $this->_operation("profiles/{$profileId}");
    }

    public function updateProfiles($data)
    {
        return $this->_operation("profiles", $data, "PUT");
    }

    public function getCampaign($campaignId)
    {
        return $this->_operation("sp/campaigns/{$campaignId}");
    }

    public function getCampaignEx($campaignId)
    {
        return $this->_operation("sp/campaigns/extended/{$campaignId}");
    }

    public function getCampaignExSponsoredDisplay($campaignId)
    {
        return $this->_operation("sd/campaigns/extended/{$campaignId}");
    }

    public function getCampaignExBrand($campaignId)
    {
        return $this->_operation("sb/campaigns/extended/{$campaignId}");
    }

    public function getCampaignSponsoredDisplay($campaignId)
    {
        return $this->_operation("sd/campaigns/{$campaignId}");
    }

    public function createCampaigns($data)
    {
        return $this->_operation("sp/campaigns", $data, "POST");
    }

    public function createCampaignsBrand($data)
    {
        return $this->_operation("sb/campaigns", $data, "POST");
    }

    public function createCampaignsSponsoredDisplay($data)
    {
        return $this->_operation("sd/campaigns", $data, "POST");
    }

    public function updateCampaigns($data)
    {
        return $this->_operation("sp/campaigns", $data, "PUT");
    }

    public function archiveCampaign($campaignId)
    {
        return $this->_operation("sp/campaigns/{$campaignId}", null, "DELETE");
    }

    public function archiveCampaignSponsoredDisplay($campaignId)
    {
        return $this->_operation("sd/campaigns/{$campaignId}", null, "DELETE");
    }

    public function listCampaigns($data = null)
    {
        return $this->_operation("sp/campaigns", $data);
    }

    public function listCampaignsEx($data = null)
    {
        return $this->_operation("sp/campaigns/extended", $data);
    }

    public function listCampaignsExSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/campaigns/extended", $data);
    }

    public function getCampaignBrand($campaignId)
    {
        return $this->_operation("sb/campaigns/{$campaignId}");
    }

    public function updateCampaignsBrand($data)
    {
        return $this->_operation("hsa/campaigns", $data, "PUT");
    }

    public function updateCampaignsSponsoredDisplay($data)
    {
        return $this->_operation("sd/campaigns", $data, "PUT");
    }

    public function archiveCampaignBrand($campaignId)
    {
        return $this->_operation("hsa/campaigns/{$campaignId}", null, "DELETE");
    }

    public function listCampaignsBrand($data = null)
    {
        return $this->_operation("hsa/campaigns", $data);
    }

    public function listCampaignsSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/campaigns", $data);
    }

    public function getAdGroup($adGroupId)
    {
        return $this->_operation("adGroups/{$adGroupId}");
    }

    public function getAdGroupSponsoredDisplay($adGroupId)
    {
        return $this->_operation("sd/adGroups/{$adGroupId}");
    }

    public function getAdGroupEx($adGroupId)
    {
        return $this->_operation("adGroups/extended/{$adGroupId}");
    }

    public function getAdGroupExSponsoredDisplay($adGroupId)
    {
        return $this->_operation("sd/adGroups/extended/{$adGroupId}");
    }

    public function createAdGroups($data)
    {
        return $this->_operation("adGroups", $data, "POST");
    }

    public function createAdGroupsSponsoredDisplay($data)
    {
        return $this->_operation("sd/adGroups", $data, "POST");
    }

    public function updateAdGroups($data)
    {
        return $this->_operation("adGroups", $data, "PUT");
    }

    public function updateAdGroupsSponsoredDisplay($data)
    {
        return $this->_operation("sd/adGroups", $data, "PUT");
    }

    public function archiveAdGroup($adGroupId)
    {
        return $this->_operation("adGroups/{$adGroupId}", null, "DELETE");
    }

    public function archiveAdGroupSponsoredDisplay($adGroupId)
    {
        return $this->_operation("sd/adGroups/{$adGroupId}", null, "DELETE");
    }

    public function listAdGroups($data = null)
    {
        return $this->_operation("adGroups", $data);
    }

    public function listAdGroupsBrand($data = null)
    {
        return $this->_operation("hsa/adGroups", $data);
    }

    public function listAdGroupsSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/adGroups", $data);
    }

    public function listAdGroupsEx($data = null)
    {
        return $this->_operation("adGroups/extended", $data);
    }

    public function listAdGroupsExSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/adGroups/extended", $data);
    }

    public function getBiddableKeyword($keywordId)
    {
        return $this->_operation("sp/keywords/{$keywordId}");
    }

    public function getBiddableKeywordEx($keywordId)
    {
        return $this->_operation("sp/keywords/extended/{$keywordId}");
    }

    public function createBiddableKeywords($data)
    {
        return $this->_operation("sp/keywords", $data, "POST");
    }

    public function createBiddableKeywordsBrands($data)
    {
        return $this->_operation("sb/keywords", $data, "POST");
    }

    public function updateBiddableKeywords($data)
    {
        return $this->_operation("sp/keywords", $data, "PUT");
    }

    public function archiveBiddableKeyword($keywordId)
    {
        return $this->_operation("sp/keywords/{$keywordId}", null, "DELETE");
    }

    public function listBiddableKeywords($data = null)
    {
        return $this->_operation("sp/keywords", $data);
    }

    public function listBiddableKeywordsEx($data = null)
    {
        return $this->_operation("sp/keywords/extended", $data);
    }

    public function getBiddableKeywordBrand($keywordId)
    {
        return $this->_operation("hsa/keywords/{$keywordId}");
    }

    public function createBiddableKeywordsBrand($data)
    {
        return $this->_operation("hsa/keywords", $data, "POST");
    }

    public function updateBiddableKeywordsBrand($data)
    {
        return $this->_operation("hsa/keywords", $data, "PUT");
    }

    public function archiveBiddableKeywordBrand($keywordId)
    {
        return $this->_operation("hsa/keywords/{$keywordId}", null, "DELETE");
    }

    public function getNegativeKeyword($keywordId)
    {
        return $this->_operation("sp/negativeKeywords/{$keywordId}");
    }

    public function getNegativeKeywordEx($keywordId)
    {
        return $this->_operation("sp/negativeKeywords/extended/{$keywordId}");
    }

    public function createNegativeKeywords($data)
    {
        return $this->_operation("sp/negativeKeywords", $data, "POST");
    }

    public function updateNegativeKeywords($data)
    {
        return $this->_operation("sp/negativeKeywords", $data, "PUT");
    }

    public function archiveNegativeKeyword($keywordId)
    {
        return $this->_operation("sp/negativeKeywords/{$keywordId}", null, "DELETE");
    }

    public function listNegativeKeywords($data = null)
    {
        return $this->_operation("sp/negativeKeywords", $data);
    }

    public function listNegativeKeywordsEx($data = null)
    {
        return $this->_operation("sp/negativeKeywords/extended", $data);
    }

    public function getCampaignNegativeKeyword($keywordId)
    {
        return $this->_operation("sp/campaignNegativeKeywords/{$keywordId}");
    }

    public function getCampaignNegativeKeywordEx($keywordId)
    {
        return $this->_operation("sp/campaignNegativeKeywords/extended/{$keywordId}");
    }

    public function createCampaignNegativeKeywords($data)
    {
        return $this->_operation("sp/campaignNegativeKeywords", $data, "POST");
    }

    public function updateCampaignNegativeKeywords($data)
    {
        return $this->_operation("sp/campaignNegativeKeywords", $data, "PUT");
    }

    public function removeCampaignNegativeKeyword($keywordId)
    {
        return $this->_operation("sp/campaignNegativeKeywords/{$keywordId}", null, "DELETE");
    }

    public function listCampaignNegativeKeywords($data = null)
    {
        return $this->_operation("sp/campaignNegativeKeywords", $data);
    }

    public function listCampaignNegativeKeywordsEx($data = null)
    {
        return $this->_operation("sp/campaignNegativeKeywords/extended", $data);
    }

    public function getProductAd($productAdId)
    {
        return $this->_operation("sp/productAds/{$productAdId}");
    }

    public function getProductAdSponsoredDisplay($productAdId)
    {
        return $this->_operation("sd/productAds/{$productAdId}");
    }

    public function getProductAdEx($productAdId)
    {
        return $this->_operation("sp/productAds/extended/{$productAdId}");
    }

    public function getProductAdExSponsoredDisplay($productAdId)
    {
        return $this->_operation("sd/productAds/extended/{$productAdId}");
    }

    public function createProductAds($data)
    {
        return $this->_operation("sp/productAds", $data, "POST");
    }

    public function createProductAdsSponsoredDisplay($data)
    {
        return $this->_operation("sd/productAds", $data, "POST");
    }

    public function updateProductAds($data)
    {
        return $this->_operation("sp/productAds", $data, "PUT");
    }

    public function updateProductAdsSponsoredDisplay($data)
    {
        return $this->_operation("sd/productAds", $data, "PUT");
    }

    public function archiveProductAd($productAdId)
    {
        return $this->_operation("sp/productAds/{$productAdId}", null, "DELETE");
    }

    public function archiveProductAdSponsoredDisplay($productAdId)
    {
        return $this->_operation("sd/productAds/{$productAdId}", null, "DELETE");
    }

    public function listProductAds($data = null)
    {
        return $this->_operation("sp/productAds", $data);
    }

    public function listProductAdsSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/productAds", $data);
    }

    public function listProductAdsEx($data = null)
    {
        return $this->_operation("sp/productAds/extended", $data);
    }

    public function listProductAdsExSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/productAds/extended", $data);
    }

    public function getAdGroupBidRecommendations($adGroupId)
    {
        return $this->_operation("sp/adGroups/{$adGroupId}/bidRecommendations");
    }

    public function getKeywordBidRecommendations($keywordId)
    {
        return $this->_operation("sp/keywords/{$keywordId}/bidRecommendations");
    }

    public function bulkGetKeywordBidRecommendations($adGroupId, $data)
    {
        $data = [
            "adGroupId" => $adGroupId,
            "keywords" => $data
        ];
        return $this->_operation("sp/keywords/bidRecommendations", $data, "POST");
    }

    public function getAdGroupKeywordSuggestions($data)
    {
        $adGroupId = $data["adGroupId"];
        unset($data["adGroupId"]);
        return $this->_operation("sp/AdGroups/{$adGroupId}/suggested/keywords", $data);
    }

    public function getAdGroupKeywordSuggestionsEx($data)
    {
        $adGroupId = $data["adGroupId"];
        unset($data["adGroupId"]);
        return $this->_operation("sp/AdGroups/{$adGroupId}/suggested/keywords/extended", $data);
    }

    public function getAsinKeywordSuggestions($data)
    {
        $asin = $data["asin"];
        unset($data["asin"]);
        return $this->_operation("asins/{$asin}/suggested/keywords", $data);
    }

    public function bulkGetAsinKeywordSuggestions($data)
    {
        return $this->_operation("asins/suggested/keywords", $data, "POST");
    }

    public function requestSnapshot($recordType, $data = null)
    {
        return $this->_operation("sp/{$recordType}/snapshot", $data, "POST");
    }

    public function requestSnapshotBrand($recordType, $data = null)
    {
        return $this->_operation("hsa/{$recordType}/snapshot", $data, "POST");
    }

    public function getSnapshot($snapshotId)
    {
        $req = $this->_operation("snapshots/{$snapshotId}");
        if ($req["success"]) {
            $json = json_decode($req["response"], true);
            if ($json["status"] == "SUCCESS") {
                return $this->_download($json["location"]);
            }
        }
        return $req;
    }

    public function getSnapshotBrands($snapshotId)
    {
        $req = $this->_operation("hsa/snapshots/{$snapshotId}");
        if ($req["success"]) {
            $json = json_decode($req["response"], true);
            if ($json["status"] == "SUCCESS") {
                return $this->_download($json["location"]);
            }
        }
        return $req;
    }


    public function requestReportV3($recordType, $data = null)
    {
        return $this->_operation(self::INTERFACE_REPORTS_V3, $data, "POST");
    }
    public function getReportStatusV3($reportId)
    {
        return $this->_operation(self::INTERFACE_REPORTS_V3 . '/' . $reportId);
    }

    public function requestReport($recordType, $data = null)
    {
        return $this->_operation("sp/{$recordType}/report", $data, "POST");
    }

    public function requestAsinReport($data = null)
    {
        return $this->_operation("asins/report", $data, "POST");
    }


    public function requestReportBrand($recordType, $data = null)
    {
        return $this->_operation("hsa/{$recordType}/report", $data, "POST");
    }

    public function requestReportSponsoredDisplay($recordType, $data = null)
    {
        return $this->_operation("sd/{$recordType}/report", $data, "POST");
    }


    public function requestReportSearchTerm($data = null)
    {
        return $this->_operation("sp/targets/report", $data, "POST");
    }

    public function getReport($reportId, bool $gunzipResponse=true)
    {
        $req = $this->_operation("reports/{$reportId}");
        if ($req["success"]) {
            $json = json_decode($req["response"], true);
            if ($json["status"] == "SUCCESS") {
                return $this->_download($json["location"], false, $gunzipResponse);
            }
        }
        return $req;
    }

    /**
     * Always returns the status of the report. Original getReport() method will automatically return the document.
     */
    public function getReportStatus($reportId)
    {
        $req = $this->_operation("reports/{$reportId}");
        return $req;
    }

    public function getTargetingClause($targetId)
    {
        return $this->_operation("sp/targets/{$targetId}");
    }

    public function getTargetingClauseSponsoredDisplay($targetId)
    {
        return $this->_operation("sd/targets/{$targetId}");
    }

    public function listTargetingClauses($data = null)
    {
        return $this->_operation("sp/targets", $data);
    }

    public function listTargetingClausesBrands($data = null)
    {
        return $this->_operation("/sb/targets/list", $data, "POST");
    }

    public function listTargetingClausesSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/targets", $data);
    }

    public function getTargetingClauseEx($targetId)
    {
        return $this->_operation("sp/targets/extended/{$targetId}");
    }

    public function getTargetingClauseExSponsoredDisplay($targetId)
    {
        return $this->_operation("sd/targets/extended/{$targetId}");
    }


    public function listTargetingClausesEx($data = null)
    {
        return $this->_operation("sp/targets/extended", $data);
    }

    public function listTargetingClausesExSponsoredDisplay($data = null)
    {
        return $this->_operation("sd/targets/extended", $data);
    }

    public function createTargetingClauses($data)
    {
        return $this->_operation("sp/targets", $data, "POST");
    }

    public function createTargetingClausesBrands($data)
    {
        return $this->_operation("sb/targets", $data, "POST");
    }

    public function createTargetingClausesSponsoredDisplay($data)
    {
        return $this->_operation("sd/targets", $data, "POST");
    }

    public function updateTargetingClauses($data)
    {
        return $this->_operation("sp/targets", $data, "PUT");
    }

    public function updateTargetingClausesBrands($data)
    {
        return $this->_operation("sb/targets", $data, "PUT");
    }

    public function updateTargetingClausesSponsoredDisplay($data)
    {
        return $this->_operation("sd/targets", $data, "PUT");
    }

    public function archiveTargetingClause($targetId)
    {
        return $this->_operation("sp/targets/" . $targetId, [], 'DELETE');
    }

    public function archiveTargetingClauseBrands($targetId)
    {
        return $this->_operation("sb/targets/" . $targetId, [], 'DELETE');
    }

    public function archiveTargetingClauseSponsoredDisplay($targetId)
    {
        return $this->_operation("sd/targets/" . $targetId, [], 'DELETE');
    }


    public function generateTargetsProductRecommendations($data)
    {
        return $this->_operation("sp/targets/productRecommendations", $data, 'POST');
    }

    public function getTargetingCategories($data)
    {
        return $this->_operation("sp/targets/categories", $data);
    }

    public function getBrandRecommendations($data)
    {
        return $this->_operation("sp/targets/brands", $data);
    }

    public function getNegativeTargetingClause($targetId)
    {
        return $this->_operation("sp/negativeTargets/" . $targetId);
    }

    public function getNegativeTargetingClauseEx($targetId)
    {
        return $this->_operation("sp/negativeTargets/extended/" . $targetId);
    }

    public function listNegativeTargetingClauses($data = null)
    {
        return $this->_operation("sp/negativeTargets", $data);
    }

    public function listNegativeTargetingClausesEx($data = null)
    {
        return $this->_operation("sp/negativeTargets/extended", $data);
    }

    public function createNegativeTargetingClauses($data)
    {
        return $this->_operation("sp/negativeTargets", $data, 'POST');
    }

    public function updateNegativeTargetingClauses($data)
    {
        return $this->_operation("sp/negativeTargets", $data, 'PUT');
    }

    public function archiveNegativeTargetingClause($targetId)
    {
        return $this->_operation("sp/negativeTargets/" . $targetId, 'DELETE');
    }

    public function getTargetBidRecommendations($data)
    {
        return $this->_operation("sp/targets/bidRecommendations", $data, "POST");
    }

    public function listPortfolios($data = null)
    {
        return $this->_operation("portfolios", $data);
    }

    public function listPortfoliosEx($data = null)
    {
        return $this->_operation("portfolios/extended", $data);
    }

    public function getPortfolio($portfolioId)
    {
        return $this->_operation("portfolios/{$portfolioId}");
    }

    public function getPortfolioEx($portfolioId)
    {
        return $this->_operation("portfolios/extended/{$portfolioId}");
    }

    public function createPortfolios($data)
    {
        return $this->_operation("portfolios", $data, "POST");
    }

    public function updatePortfolios($data)
    {
        return $this->_operation("portfolios", $data, "PUT");
    }

    public function getAdGroupSuggestedKeywords($adGroupId)
    {
        return $this->_operation("sp/adGroups/" . $adGroupId . "/suggested/keywords");
    }

    public function getAsinSuggestedKeywords($asinValue)
    {
        return $this->_operation("sp/asins/" . $asinValue . "/suggested/keywords");
    }

    public function bulkGetAsinSuggestedKeywords($data)
    {
        return $this->_operation("sp/asins/suggested/keywords", $data, "POST");
    }

    public function listAssets($data = null)
    {
        return $this->_operation("stores/assets", $data);
    }

    public function listBrands($data = null)
    {
        return $this->_operation("brands", $data);
    }

    /** Amazon attribution start  */
    public function getAttributionListPublishers($data = null)
    {
        return $this->_operation("attribution/publishers", $data);
    }

    public function getAttributionReports($data = null)
    {
        return $this->_operation("attribution/report", $data, "POST");
    }

    public function getAttributionNonMacroTemplateTag($data = null)
    {
        return $this->_operation("attribution/tags/nonMacroTemplateTag", $data);
    }

    public function getAttributionMacroTemplateTag($data = null)
    {
        return $this->_operation("attribution/tags/macroTag", $data);
    }

    public function getAttributionAdvertisers($data = null)
    {
        return $this->_operation("attribution/advertisers", $data);
    }

    /** Amazon attribution end */

    public function getHistoryData($data = null)
    {
        return $this->_operation("history", $data, "POST");
    }

    /** Amazon Ad Creatives Start  */

    public function listSbAdCreatives($data = null)
    {
        return $this->_operation("sb/ads/creatives/list", $data, "POST");
    }

    /** Amazon Ad Creatives End  */

    public function requestBrandMetrics($data = null)
    {
        return $this->_operation("insights/brandMetrics/report", $data, "POST");
    }

    public function getBrandMetricsReport($reportId, bool $gunzipResponse=true)
    {
        $req = $this->_operation("insights/brandMetrics/report/{$reportId}");
        if ($req["success"]) {
            $json = json_decode($req["response"], true);
            if ($json["status"] == "SUCCESSFUL") {
                $requestId = $this->requestId;
                try {
                    $response = file_get_contents($json["location"]);
                    return [
                        "success" => true,
                        "response" => $response,
                        "requestId" => $requestId
                    ];
                } catch (\Exception|\ValueError $e) {
                    if ('Path cannot be empty' === $e->getMessage()) {
                        // Happens when the report is empty
                        return [
                            "success" => true,
                            "response" => '{}',
                            "requestId" => $requestId
                        ];
                    }
                    return [
                        "success" => false,
                        "response" => $e->getMessage(),
                        "requestId" => $requestId
                    ];
                }
            }
        }
        return $req;
    }

    public function getBrandMetricsReportStatus($reportId)
    {
        $req = $this->_operation("insights/brandMetrics/report/{$reportId}");
        return $req;
    }

    /**
     * @param $data
     *  [        'assetInfo' => '{brandEntityId: "ENTITY123456", mediaType: "brandLogo"}'    ];
     * @param $filePath - real path to uploaded file
     * @param $imageType - one of PNG|JPEG|GIF
     * @param $fileName - example 'logo.png'
     * @return array
     * @throws \Exception
     */
    public function createAsset($data, $filePath, $imageType, $fileName)
    {
        $headers = [
            'Content-Disposition: ' . $fileName
        ];
        return $this->_UploadAsset($data, [$filePath], $headers, $imageType, $fileName);
    }

    private function _download($location, $downloadActualFile = false, bool $gunzipResponse=true)
    {
        $headers = [];

        if (!$downloadActualFile) {
            /* only send authorization header when not downloading actual file */
            array_push($headers, "Authorization: bearer {$this->config["accessToken"]}");
        }

        if (!is_null($this->profileId)) {
            array_push($headers, "Amazon-Advertising-API-Scope: {$this->profileId}");
        }

        if (!is_null($this->config['clientId'])) {
            array_push($headers, "Amazon-Advertising-API-ClientId: {$this->config['clientId']}");
        }

        $request = new CurlRequest();
        $request->setOption(CURLOPT_URL, $location);
        $request->setOption(CURLOPT_HTTPHEADER, $headers);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);

        if ($downloadActualFile) {
            $response = $this->_executeRequest($request, $gunzipResponse);
            if ($gunzipResponse) {
                try {
                    $response["response"] = gzdecode($response["response"]);
                    $response["gzencoded"] = 0;
                } catch (\Exception $e) {

                }
            } else {
                $response["gzencoded"] = 1;
            }
            return $response;
        }

        return $this->_executeRequest($request, $gunzipResponse);
    }

    private function _operation($interface, $params = [], $method = "GET", $additionalHeaders = [])
    {
        if (self::INTERFACE_REPORTS_V3 === $interface) {
            $contentType = 'application/vnd.createasyncreportrequest.v3+json';
        } elseif (isset(self::ROUTE_ACCEPT_TYPE_MAPPING[$interface])) {
            $contentType = 'text/plain';
            $acceptType = self::ROUTE_ACCEPT_TYPE_MAPPING[$interface];
        } else {
            $contentType = 'application/json';
        }

        $headers = [
            "Authorization: bearer {$this->config["accessToken"]}",
            "Content-Type: $contentType",
            "User-Agent: {$this->userAgent}",
        ];

        if (!empty($acceptType)) {
            $headers[] = "Accept: $acceptType";
        }

        if (!is_null($this->config['clientId'])) {
            array_push($headers, "Amazon-Advertising-API-ClientId: {$this->config['clientId']}");
        }

        if (!empty($additionalHeaders)) {
            foreach ($additionalHeaders as $header) {
                array_push($headers, $header);
            }
        }

        $request = new CurlRequest();
        $url = "{$this->endpoint}/{$interface}";

        $excludedVersionForInterfaceList = ['brands', 'stores/assets', 'sb/campaigns', 'sb/targets', 'sb/keywords', self::INTERFACE_REPORTS_V3];
        if (array_search($interface, $excludedVersionForInterfaceList) !== false) {
            $url = str_replace('/' . $this->apiVersion, '', $url);
        } elseif (str_contains($url, $this->apiVersion . '/' . self::INTERFACE_REPORTS_V3)) {
            $url = str_replace('/' . $this->apiVersion, '', $url);
        } elseif (strpos($url, 'sb/ads') !== false) {
            $url = str_replace('/' . $this->apiVersion, '', $url);
        } elseif (strpos($url, 'insights/brandMetrics') !== false) {
            $url = str_replace('/' . $this->apiVersion, '', $url);
        }

        if (strpos($url, 'sb/campaigns') !== false OR strpos($url, 'history') !== false) {
            $url = str_replace('/' . $this->apiVersion, '', $url);
        }

        if (strpos($url, '/attribution') !== false) {
            $url = str_replace('/' . $this->apiVersion, '', $url);
            if (!is_null($this->profileIdAttribution)) {
                array_push($headers, "Amazon-Advertising-API-Scope: {$this->profileIdAttribution}");
            }
        } else {
            if (!is_null($this->profileId)) {
                array_push($headers, "Amazon-Advertising-API-Scope: {$this->profileId}");
            }
        }

        $this->requestId = null;

        switch (strtolower($method)) {
            case "get":
                if (!empty($params)) {
                    $url .= "?";
                    foreach ($params as $k => $v) {
                        $url .= "{$k}=" . rawurlencode($v) . "&";
                    }
                    $url = rtrim($url, "&");
                }
                break;
            case "put":
            case "post":
            case "delete":
                if (!empty($params)) {
                    $data = json_encode($params);
                    $request->setOption(CURLOPT_POST, true);
                    $request->setOption(CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                $this->_logAndThrow("Unknown verb {$method}.");
        }

        $request->setOption(CURLOPT_URL, $url);
        $request->setOption(CURLOPT_HTTPHEADER, $headers);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);
        $request->setOption(CURLOPT_CUSTOMREQUEST, strtoupper($method));
        return $this->_executeRequest($request);
    }

    /**
     * @param array $params
     * @param array $filenames
     * @param array $additionalHeaders
     * @param string $imageType
     * @param string $fileName
     * @return array
     * @throws \Exception
     */
    private function _UploadAsset($params, $filenames, $additionalHeaders, $imageType, $fileName)
    {

        $files = [];
        foreach ($filenames as $f) {
            $files[$f] = file_get_contents($f);
        }


        $url = "{$this->endpoint}/stores/assets";
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $post_data = $this->build_data_files($boundary, $params, $files, $imageType, $fileName);

        $headers = [
            "Authorization: bearer {$this->config["accessToken"]}",
            "Content-Type: multipart/form-data; boundary=" . $delimiter,
            "Content-Length: " . strlen($post_data),
            "User-Agent: {$this->userAgent}"
        ];

        if (!is_null($this->profileId)) {
            array_push($headers, "Amazon-Advertising-API-Scope: {$this->profileId}");
        }

        if (!is_null($this->config['clientId'])) {
            array_push($headers, "Amazon-Advertising-API-ClientId: {$this->config['clientId']}");
        }

        if (!empty($additionalHeaders)) {
            foreach ($additionalHeaders as $header) {
                array_push($headers, $header);
            }
        }

        $request = new CurlRequest();
        $url = str_replace('/' . $this->apiVersion, '', $url);

        $this->requestId = null;
        $request->setOption(CURLOPT_POST, true);
        $request->setOption(CURLOPT_POSTFIELDS, $post_data);
        $request->setOption(CURLOPT_URL, $url);
        $request->setOption(CURLOPT_HTTPHEADER, $headers);
        $request->setOption(CURLOPT_USERAGENT, $this->userAgent);
        $request->setOption(CURLOPT_CUSTOMREQUEST, 'POST');
        return $this->_executeRequest($request);
    }


    /**
     * @throws \Exception
     */
    protected function build_data_files($boundary, $fields, $files, $imageType, $fileName)
    {

        $imageMimeType = '';
        switch ($imageType) {
            case 'PNG':
                $imageMimeType = 'image/png';
                break;

            case 'JPEG':
                $imageMimeType = 'image/jpeg';
                break;

            case 'GIF':
                $imageMimeType = 'image/gif';
                break;

            default:
                $this->_logAndThrow("Unknown image type {$imageType}.");
        }

        $data = '';
        $eol = "\r\n";
        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
                . $content . $eol;
        }

        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="asset"; filename="' . $fileName . '"' . $eol
                . 'Content-Type: ' . $imageMimeType . $eol
                . 'Content-Transfer-Encoding: binary' . $eol;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--" . $eol;

        return $data;
    }


    protected function _executeRequest($request, bool $gunzipResponse=true)
    {
        $response = $request->execute();
        $this->requestId = $request->requestId;
        $response_info = $request->getInfo();
        $request->close();


        if ($response_info["http_code"] == 307) {
            /* application/octet-stream */
            return $this->_download($response_info["redirect_url"], true, $gunzipResponse);
        }

        if (!preg_match("/^([23])\d{2}$/", $response_info["http_code"])) {
            $requestId = 0;
            $json = json_decode($response, true);
            if (!is_null($json)) {
                if (array_key_exists("requestId", $json)) {
                    $requestId = json_decode($response, true)["requestId"];
                }
            }
            $success = false;
        } else {
            $success = true;
            $requestId = $this->requestId;
        }
        return [
            "success" => $success,
            "code" => $response_info["http_code"],
            "response" => $response,
            "requestId" => $requestId
        ];
    }

    /**
     * @throws \Exception
     */
    private function _validateConfig($config)
    {
        if (is_null($config)) {
            $this->_logAndThrow("'config' cannot be null.");
        }

        foreach ($config as $k => $v) {
            if (array_key_exists($k, $this->config)) {
                $this->config[$k] = $v;
            } else {
                $this->_logAndThrow("Unknown parameter '{$k}' in config.");
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function _validateConfigParameters()
    {
        foreach ($this->config as $k => $v) {
            if (is_null($v) && $k !== "accessToken" && $k !== "refreshToken") {
                $this->_logAndThrow("Missing required parameter '{$k}'.");
            }
            switch ($k) {
                case "clientId":
                    if (!preg_match("/^amzn1\.application-oa2-client\.[a-z0-9]{32}$/i", $v)) {
                        $this->_logAndThrow("Invalid parameter value for clientId.");
                    }
                    break;
                case "clientSecret":
                    if (!preg_match("/^[a-z0-9]{64}$/i", $v)) {
                        $this->_logAndThrow("Invalid parameter value for clientSecret.");
                    }
                    break;
                case "accessToken":
                    if (!is_null($v)) {
                        if (!preg_match("/^Atza(\||%7C|%7c).*$/", $v)) {
                            $this->_logAndThrow("Invalid parameter value for accessToken.");
                        }
                    }
                    break;
                case "refreshToken":
                    if (!is_null($v)) {
                        if (!preg_match("/^Atzr(\||%7C|%7c).*$/", $v)) {
                            $this->_logAndThrow("Invalid parameter value for refreshToken.");
                        }
                    }
                    break;
                case "sandbox":
                    if (!is_bool($v)) {
                        $this->_logAndThrow("Invalid parameter value for sandbox.");
                    }
                    break;
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function _setEndpoints()
    {
        /* check if region exists and set api/token endpoints */
        if (array_key_exists(strtolower($this->config["region"]), $this->endpoints)) {
            $region_code = strtolower($this->config["region"]);
            if ($this->config["sandbox"]) {
                $this->endpoint = "https://{$this->endpoints[$region_code]["sandbox"]}/{$this->apiVersion}";
            } else {
                $this->endpoint = "https://{$this->endpoints[$region_code]["prod"]}/{$this->apiVersion}";
            }

            $this->tokenUrl = $this->endpoints[$region_code]["tokenUrl"];
        } else {
            $this->_logAndThrow("Invalid region.");
        }
    }

    /**
     * @param $message
     * @throws \Exception
     */
    private function _logAndThrow($message)
    {
        error_log($message, 0);
        throw new \Exception($message);
    }
}
