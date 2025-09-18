<?php

// Simple cURL test for MNotify API
$apiKey = env('MNOTIFY_API_KEY'); // Replace with your actual API key

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.mnotify.com/api/balance/sms',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $apiKey,
        'Accept: application/json',
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

// Also test with different header format
echo "\n--- Testing with ApiKey header ---\n";

$curl2 = curl_init();

curl_setopt_array($curl2, array(
    CURLOPT_URL => 'https://api.mnotify.com/api/balance/sms',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'ApiKey: ' . $apiKey,
        'Accept: application/json',
        'Content-Type: application/json'
    ),
));

$response2 = curl_exec($curl2);
$httpCode2 = curl_getinfo($curl2, CURLINFO_HTTP_CODE);

curl_close($curl2);

echo "HTTP Code: " . $httpCode2 . "\n";
echo "Response: " . $response2 . "\n";
