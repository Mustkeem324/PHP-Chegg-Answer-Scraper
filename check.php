<?php
$jsonFilePath = 'cookieschegg.json';
$jsonContents = file_get_contents($jsonFilePath);
$cookieArray = json_decode($jsonContents, true);
if ($cookieArray === null) {
    return 'Error decoding JSON.';
} else {
    $cookies = $cookieArray['cookies'];
}
$userStatusArray = [];
foreach ($cookies as $cookie) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"operationName":"resetBanner","variables":{},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"c78e259d8e022c643865405d193982aaa3c4a8167ea978dda04ce6b440cfdb55"}}}',
        CURLOPT_HTTPHEADER => array(
            'authority: gateway.chegg.com',
            'accept: */*, application/json',
            'accept-language: en-US,en;q=0.9',
            'apollographql-client-name: chegg-web',
            'apollographql-client-version: main-9484a536-5354791451',
            'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
            'content-type: application/json',
            'cookie:'.$cookie,
            'dnt: 1',
            'origin: https://www.chegg.com',
            'referer: https://www.chegg.com/',
            'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    //echo $response;
    $data = json_decode($response, true);
    $userStatusArray[] = $data['data']['me']['accountSharing']['userStatus'];

}
$userStatusJSON = json_encode($userStatusArray);
$file = 'user_statuses.json';
file_put_contents($file, $userStatusJSON);
echo $userStatusJSON;
?>