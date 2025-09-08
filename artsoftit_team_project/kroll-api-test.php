<?php
/** 
 * Plugin name: Kroll API Test
 * Plugin URI: 
 * Description: This is a Kroll API testing API
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * License: GPLv2 or later
 * Text Domain: apiuser
*/


if (!class_exists('SoapClient')) {
    die("SOAP extension is not enabled in PHP!");
}

// Test WSDL ব্যবহার করো (প্রোডাকশনে যাওয়ার আগে টেস্ট করা উচিত)
$wsdl = "https://api.krollcorp.com/EBusinessTEST/Kroll.Dealer.EBusiness.svc?wsdl";

// তোমার দেয়া API key (যদি শুধু loginKey থাকে, DealerAccountNumber = loginKey, UserId = loginKey)
$loginKey = "fefddffrf343fdsfstrcdsfgcfdsadcfdasas";

try {
    $client = new SoapClient($wsdl, [
        'trace'      => 1,
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_NONE,
    ]);

    $params = [
        'request' => [
            'DealerAccountNumber' => $loginKey,
            'UserId'              => $loginKey,
            'Password'            => $loginKey,
            'SkuList'             => ['SKU123', 'SKU456'], // এখানে যেকোনো আসল SKU দিতে হবে
        ],
    ];

    $response = $client->CheckProductAvailability($params);

    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}



?>