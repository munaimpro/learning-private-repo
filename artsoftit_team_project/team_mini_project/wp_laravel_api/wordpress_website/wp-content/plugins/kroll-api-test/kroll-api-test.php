<?php

if (!class_exists('SoapClient')) {
    die("SOAP extension is not enabled in PHP!");
}

// Point to the local WSDL file you just created
$wsdl = "file:///C:/xampp/htdocs/learning-private-repo/artsoftit_team_project/Kroll.wsdl";

// The explicit endpoint URL for the BasicHttpBinding.
$endpoint = "https://apiv2.krollcorp.com/EBusinessTest/Kroll.Dealer.EBusiness.svc/Basic";

// Replace with your actual credentials.
$loginKey = "YOUR_LOGIN_KEY_HERE";
$dealerAccountNumber = $loginKey;
$userId = $loginKey;
$password = $loginKey;

// IMPORTANT: Replace with actual SKUs from Kroll's catalog.
$skuList = ['SKU123', 'SKU456'];

try {
    $client = new SoapClient($wsdl, [
        'trace'      => 1,
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'location'   => $endpoint,
    ]);

    print_r($client->__getFunctions());


    $params = [
        'request' => [
            'DealerAccountNumber' => $dealerAccountNumber,
            'UserId'              => $userId,
            'Password'            => $password,
            'SkuList'             => $skuList,
        ],
    ];

    $response = $client->CheckProductAvailability($params);
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (SoapFault $e) {
    echo "SOAP Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";
}
