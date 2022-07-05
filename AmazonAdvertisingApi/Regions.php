<?php

namespace AmazonAdvertisingApi;

class Regions
{
    public $endpoints = array(
        "na" => array(
            "prod" => "advertising-api.amazon.com",
            "sandbox" => "advertising-api-test.amazon.com",
            "tokenUrl" => "api.amazon.com/auth/o2/token"),
        "eu" => array(
            "prod" => "advertising-api-eu.amazon.com",
            "sandbox" => "advertising-api-test.amazon.com",
            "tokenUrl" => "api.amazon.co.uk/auth/o2/token"
        ),
        "fe" => array(
            "prod" => "advertising-api-fe.amazon.com",
            "sandbox" => "advertising-api-test.amazon.com",
            "tokenUrl" => "api.amazon.co.jp/auth/o2/token"
        ),
    );

    public const SD_AVAILABLE_IN_MARKETPLACES = [
        'US', 'CA', 'MX',
        'UK', 'DE', 'FR', 'IT', 'ES',
        'AE',
        'AU',
        'BR',
        'IN',
        'JP',
        'NL',
    ];

    public static function isSponsoredDisplayAvailableInMarketplace($marketplace): bool
    {
        if (in_array($marketplace, self::SD_AVAILABLE_IN_MARKETPLACES)) {
            return true;
        }
        return false;
    }
}
