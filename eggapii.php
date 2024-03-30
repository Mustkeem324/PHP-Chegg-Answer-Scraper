<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url=$_GET['url']; //request get to url
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $text = $url;
    }
    else{
        echo "Invalid URL provided check url";
        exit();
    }
    //cokies check is 200 staus
    function getRandomCookie($jsonFilePath) {
        $jsonContents = file_get_contents($jsonFilePath);
        $cookieArray = json_decode($jsonContents, true);
        if ($cookieArray === null) {
            return 'Error decoding JSON.';
        } else {
            $cookies = $cookieArray['cookies'];
            if (!empty($cookies)) {
                // Shuffle the cookies array
                shuffle($cookies);
                return $cookies;
            } else {
                return array();
            }
        }
    }
     
    function checkAndUpdateCookie($cookies, $jsonFilePath) {
        foreach ($cookies as $cookie) {
            $curltrue = curl_init();
            curl_setopt_array($curltrue, array(
                CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{"operationName":"hasActiveCheggStudy","variables":{},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"f6707940e697a3a359f218b04ad23eee36b4d11cea2b8b59221a572fdd8c554b"}}}',
                CURLOPT_HTTPHEADER => array(
                    'authority: gateway.chegg.com',
                    'accept: */*, application/json',
                    'accept-language: en-US,en;q=0.9',
                    'apollographql-client-name: chegg-web',
                    'apollographql-client-version: main-47f7e7d4-5403504590',
                    'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                    'content-type: application/json',
                    'cookie: '.$cookie,
                    'dnt: 1',
                    'origin: https://www.chegg.com',
                    'referer: https://www.chegg.com/',
                    'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
                    'sec-ch-ua-mobile: ?0',
                    'sec-ch-ua-platform: "Linux"',
                    'sec-fetch-dest: empty',
                    'sec-fetch-mode: cors',
                    'sec-fetch-site: same-site',
                    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36',
                    ),
                ));
            $responsetrue = curl_exec($curltrue);
            curl_close($curltrue);
            $responseDatatrue = json_decode($responsetrue, true);
            if ($responseDatatrue['data']['me']['hasCheggStudy'] === true){
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
        
                // Parse the response
                $responseData = json_decode($response, true);
                //echo $response;
                if ($responseData['data']['me']['accountSharing']['userStatus'] === 'RELEASED' || $responseData['data']['me']['accountSharing']['userStatus'] === 'OK') {
                    // If hasCheggStudy is true, return the cookie
                    return $cookie;
                }
            }    
        }
    
        // If no valid cookies are found, return an empty string
        return '';
    }
    
    $jsonFilePath = 'cookieschegg.json';
    $cookies = getRandomCookie($jsonFilePath);
    $validCookie = checkAndUpdateCookie($cookies, $jsonFilePath);

    //questionbody
    function Transformlink($text){
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
            CURLOPT_POSTFIELDS =>'{"operationName":"TransformUrl","variables":{"url":{"url":"'.$text.'","hostPrefix":false}},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"03e0153ed664185b1ec608f1e30ed431054d03d09e308ad0a4ff19b6e5725512"}}}',
            CURLOPT_HTTPHEADER => array(
                'authority: gateway.chegg.com',
                'accept: */*, application/json',
                'accept-language: en-US,en;q=0.9',
                'apollographql-client-name: chegg-web',
                'apollographql-client-version: main-5ab73977-5576088612',
                'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                'content-type: application/json',
                'cookie: CVID=dd2c6013-9482-4d5d-8074-9bbbbb671c3b; V=2c39fde34e8e9db5996ae8b783e19aed64a11dea8f5c28.50689748; _pxvid=8f5ee825-18a4-11ee-9989-a6b25880e637; C=0; O=0; _pubcid=ce5a1325-8f1e-4eb0-adca-c82de3d000fe; _au_1d=AU1D-0100-001696010749-H4EZTKMX-A0HN; _pubcid_cst=zix7LPQsHA%3D%3D; _scid=50c62da3-711c-49f3-8c6a-79c596664c6f; loupeclientID=59b2979-5c0a-4649-a22b-e1b49bfc9db3; _hjSessionUser_2946394=eyJpZCI6ImQ3ODE4YzYyLTQ2OTQtNWIxZC1iMTQ5LTQ4ODZkYWVhODQxMSIsImNyZWF0ZWQiOjE2OTY3MDc4Nzc3NTgsImV4aXN0aW5nIjp0cnVlfQ==; _ga_E1732NRZXV=GS1.2.1696707877.1.0.1696707883.54.0.0; _ga_L6CX34MVT2=GS1.1.1696707876.1.1.1696708724.60.0.0; _hjSessionUser_3091164=eyJpZCI6IjJjMDhlNTRmLTAyM2YtNTQ3ZC05ZDNhLTNlMzNmNDU5ZjQ4YiIsImNyZWF0ZWQiOjE2OTcwNDIxMTczMjQsImV4aXN0aW5nIjp0cnVlfQ==; DFID=web|yXBiJB2k7XUWqDDjbm7C; permutive-id=819b3494-0fe5-4a3c-af4e-578a8162f9f8; _cc_id=e5da260b97579753866c070ad2d63164; _sctr=1%7C1698517800000; connectId=%7B%22vmuid%22%3A%22f3Ey19mzv3aO9lBwLBiirCSICDlzTNr3hNrdVVInmQ43fMPtJSlB7AQ4ZibUk-3SU6Gy4k90sA2kldAzew89fQ%22%2C%22connectid%22%3A%22f3Ey19mzv3aO9lBwLBiirCSICDlzTNr3hNrdVVInmQ43fMPtJSlB7AQ4ZibUk-3SU6Gy4k90sA2kldAzew89fQ%22%2C%22connectId%22%3A%22f3Ey19mzv3aO9lBwLBiirCSICDlzTNr3hNrdVVInmQ43fMPtJSlB7AQ4ZibUk-3SU6Gy4k90sA2kldAzew89fQ%22%2C%22ttl%22%3A24%2C%22he%22%3A%22e39006d09eeaaf335086af1daf292be816dae6c9ae8376efac8ca747eb61527d%22%2C%22lastSynced%22%3A1698674628568%2C%22lastUsed%22%3A1698757792196%7D; _awl=2.1698844334.5-67dab70069250890556a45328d8e9dcc-6763652d617369612d6561737431-0; exp_id=8feb3db37315af938ae312888419d2ef6542a43e8b4394.12677425; ab.storage.sessionId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22bb647ad0-85ac-eb33-f950-8f8f05e42af2%22%2C%22e%22%3A1698868038842%2C%22c%22%3A1698866238846%2C%22l%22%3A1698866238846%7D; ab.storage.deviceId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22272aaef5-6d65-d1fd-6a96-e441f30f85e9%22%2C%22c%22%3A1697130622703%2C%22l%22%3A1698866238849%7D; ab.storage.userId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22c58e0d65-c105-461b-8c24-1e4181ce9f8c%22%2C%22c%22%3A1697559560745%2C%22l%22%3A1698866238851%7D; optimizelyEndUserId=oeu1699029012366r0.3548941515510595; _cs_c=0; _cs_id=37d65b65-3442-aa80-fcb3-23cb552916eb.1699029013.1.1699029036.1699029013.1.1733193013405; _scid_r=50c62da3-711c-49f3-8c6a-79c596664c6f; forterToken=de6d955102dd49499306913113a72b06_1699936025257__UDF43-m4_13ck; U=0; _iidt=0HDx9EhhoDle/dKH/vsjGK1MixJlctKDAvvD/Hdy3L7Sql6Jq7MEEfv6bH4/CgofudP8TlWVDcLr1md/6qkFnMFyow==; _vid_t=Y1ldmm8kFFPtv9cno7pmZbbmsM/lcU0NNm0B2TB68rzUVe1vpn84zyzb9i8u3rKP+zWNFe308iXVTzLHzPSvvwnB2Q==; panoramaId_expiry=1700814356849; panoramaId=791ea04a092fd847902d5ef0525916d5393804009d9170b350a0d032dd3e428d; panoramaIdType=panoIndiv; opt-user-profile=dd2c6013-9482-4d5d-8074-9bbbbb671c3b%252C24080330904%253A24091301300%252C24105130281%253A24093410251%252C24483060027%253A24374571107%252C24639721375%253A24665850370%252C24407410763%253A24408150549%252C24985571146%253A24944641121%252C25230140379%253A25249890628%252C25233851370%253A25293620061%252C26210340118%253A26222240070; _ga_HRYBF3GGTD=GS1.1.1700224592.16.0.1700224594.0.0.0; _ga_1Y0W4H48JW=GS1.1.1700224592.16.1.1700224594.0.0.0; _ga=GA1.2.1290475448.1696010748; exp=C026A%7CA127D; expkey=65A93264A6F0D70DBA7B125AA92FC04A; SU=VNRSH7IeJJ5AVgCHJ-HYadsauEvl5C1yEXZHnKwja5ROPobCt6fM3AipqwM1DyTZqx-CjNn-hRTErs11Lz6Ioxv0ATlCFdjoUT6zMNGbEvNO9ExDUlYaTPolJHkRdHMt; country_code=IN; pxcts=63a82b97-869f-11ee-8ef1-1dcdab27ec13; CSID=1700494241547; _gid=GA1.2.1902144065.1700494254; _au_last_seen_pixels=eyJhcG4iOjE3MDA0OTQyNTMsInR0ZCI6MTcwMDQ5NDI1MywicHViIjoxNzAwNDk0MjUzLCJydWIiOjE3MDA0OTQyNTMsInRhcGFkIjoxNzAwNDk0MjUzLCJhZHgiOjE3MDA0OTQyNTMsImdvbyI6MTcwMDQ5NDI1MywidW5ydWx5IjoxNzAwNDk0MzM1LCJpbXByIjoxNzAwNDk0MzM1LCJ0YWJvb2xhIjoxNzAwNDk0MzM1LCJzb24iOjE3MDA0OTQyNTMsIm9wZW54IjoxNzAwNDk0MzM1LCJpbmRleCI6MTcwMDQ5NDI1MywiYW1vIjoxNzAwNDk0MjUzLCJhZG8iOjE3MDA0OTQzMzUsImNvbG9zc3VzIjoxNzAwNDk0MzM1LCJzbWFydCI6MTcwMDQ5NDMzNSwicHBudCI6MTcwMDQ5NDMzNSwiYmVlcyI6MTcwMDQ5NDMzNX0%3D; __gads=ID=39136120173e9d1a:T=1689703704:RT=1700498709:S=ALNI_MZ4Hdp-B35cUffShw4vvTWEhYpVcw; __gpi=UID=00000c2204e0fa5b:T=1689703704:RT=1700498709:S=ALNI_MajvCfR9zxGPRCB3ngLKLgjCkZJ_w; refreshToken=ext.a0.t00.v1.MzTIsWsp055y9pzZ9if0Sx2botKKGPqbUe3HtoGp6q8Yw9tvzUWRfaPhmL7C4O-Bvt21Jg74BQsjhVrMYRnWtKaG; refresh_token=ext.a0.t00.v1.MzTIsWsp055y9pzZ9if0Sx2botKKGPqbUe3HtoGp6q8Yw9tvzUWRfaPhmL7C4O-Bvt21Jg74BQsjhVrMYRnWtKaG; id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6Im11c3RrZWVtMzI0QGdtYWlsLmNvbSIsImlzcyI6Imh1Yi5jaGVnZy5jb20iLCJhdWQiOiJDSEdHIiwiaWF0IjoxNzAwNDk5ODMxLCJleHAiOjE3MTYwNTE4MzEsInN1YiI6IjRjYzI3NThiLTEzMDMtNDBlNi1hNjI1LTcyZGQ2ZmRkMTEwMSIsInJlcGFja2VyX2lkIjoiYXB3IiwiY3R5cCI6ImlkIiwiaWRzaWQiOiI2N2JhZmNmNiIsImlkc3QiOjE3MDA0OTk4MzExNzcsImlkc2ciOiJ1bmtub3duIn0.xY28GPxUqiHZLuWDgqTrUYCjRLl3MDh873tLRifTUcczie4JuOdwJtP7P_UgLoZWEp15dlcOoCSbPxlhYDevk_zSkgp4JYDJ_fZqjMpxbkIeP-mPLFAqxp1jtC4OVl9jq04G4uaL3VmqymR1nr4edUQ0kW8z3xtMvy8sSc3xCqPW-4Itjk4sgck2_LPFZRo_U7iXB2Ygm9tpZ5LN4F2P-tKCF3SnnHU0ZA1YSJiTl-zNyiKE3YkTY2CWrQmon9mvqphCpOjullBy6ChxHgB6cPuz8ibxcp3u3LxRk9ZemLg7P7gLC4oq-Z7l47tftf4rWzjv7Es7xQe8O3j7GPYQsg; access_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwczovL3Byb3h5LmNoZWdnLmNvbS9jbGFpbXMvYXBwSWQiOiJDSEdHIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsInN1YiI6IjRjYzI3NThiLTEzMDMtNDBlNi1hNjI1LTcyZGQ2ZmRkMTEwMSIsImF1ZCI6WyJ0ZXN0LWNoZWdnIl0sImlhdCI6MTcwMDQ5OTgzMSwiZXhwIjoxNzAwNTAxMjcxLCJhenAiOiJGaXBqM2FuRjRVejhOVVlIT2NiakxNeDZxNHpWS0VPZSIsInNjb3BlIjoib3BlbmlkIHByb2ZpbGUgZW1haWwgYWRkcmVzcyBwaG9uZSBvZmZsaW5lX2FjY2VzcyIsImd0eSI6WyJyZWZyZXNoX3Rva2VuIiwicGFzc3dvcmQiXSwicmVwYWNrZXJfaWQiOiJhcHciLCJjdHlwIjoiYWNjZXNzIn0.lx-cPKQTJuJu0KdmAKkcuGb49nt0ZzQQiLI51wwxcuCVhFsJPaPHVgHMxhJ-6ihik36exADCBAga-F715wtif1yGlrAfFaElTw7aspS3zkgQSxOss50MW5z30Osjqz9L5z1gewyIbn0hPbTUFcZatrP6CVtaJFpurXLYxkaM0cAPaZjhxpcuLpNUGGbO67hnloghqixzDYG4bOuEPAjO-qX0RUbUWtQXVGrIHyaeSprdviER0n6mJL-qcQ0uiLN_JkDAtMzSV5R3oJs1JBdBsztWcyYvlSDZdcLNC8SGof0i_i_LjY6iiWEuDe1BTuVt5BMmR5x5HxZFTCyiCnEBMg; access_token_expires_at=1700501271185; OptanonConsent=isGpcEnabled=0&datestamp=Mon+Nov+20+2023+22%3A43%3A23+GMT%2B0530+(India+Standard+Time)&version=202310.2.0&isIABGlobal=false&hosts=&consentId=c13a1fdb-4315-4338-8ba1-bb2ae973da03&interactionCount=1&landingPath=NotLandingPage&groups=fnc%3A0%2Csnc%3A1%2Ctrg%3A0%2Cprf%3A0&AwaitingReconsent=false&browserGpcFlag=0; _px3=fc1711027cac2a7de138804598ea41cfbc61da41616edaa479d9f3b315358ca9:zLB34mSXJhcI6kqCGt46pP5R26/aMRVp6RHd/dAOJW8BKOEvnZBp5ROVcyMLSvlwiN8tRIfUM9ZvBcRJswwb5w==:1000:Fq7E6i8FpeCAcV0DNN3cWl6138Ap9Ma1A3Bwz5ECtlDBhsuu/v8ZHYTn0am1gvq9TQ6WrN+GgUqzoX13BvtvlYGMk1JnFWScBedtgYIfnmrunTfLf5Aiw0mRAcbFKDZ1MFkbCykTFM4WhNpdFCb9ET211WtT0mPLzOu5QyEgFULaShfS4LyVQlgweioGY/B4cbjfZ17VJNPYhPoRhyPHK9X0TAiMXMfYOwPHDocPfPM=; _px=zLB34mSXJhcI6kqCGt46pP5R26/aMRVp6RHd/dAOJW8BKOEvnZBp5ROVcyMLSvlwiN8tRIfUM9ZvBcRJswwb5w==:1000:j0nLC65uzLm9ohirkRArw+63k1fx1Gpwmn6S4yuf1K5FX0Q/r14v6SagWujWzHyyfMqeGpp5xyO3b2HgOuWyA/b4ZxSr7uaWaucjFszn2mUmCh/jOnfcMUcEiawauWvuvgiKtGPShq3/fWuBJzWefLk1HNMZYLjisB33TWo1+LKRC4FDYeA+Zk0Ng/D6hmM1pv3gc22pC1UMaYSPbSyupR4Ja9amQzHSQUkqDGfIXovgmNQx+wsta4hF33KSxy5SPSA8vaJKTEc1TjOVeTKF5g==; _pxde=948419dd98245493be647a04539858b4c8f576c98811e9a6391b48b418264c63:eyJ0aW1lc3RhbXAiOjE3MDA1MDA2NTU5MDN9',
                'dnt: 1',
                'origin: https://www.chegg.com',
                'referer: https://www.chegg.com/',
                'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Linux"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-site',
                'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
                ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        //echo $response;
        $data =json_decode($response,true);
        $uuidstring =$data['data']['transformUrl']['iosDeeplinkEncoded'];
        $parts = explode('%2F', $uuidstring);
        $uuid = end($parts);
        return $uuid;
    }
    function questionBody($uuid) {
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
        CURLOPT_POSTFIELDS => '{"operationName":"QuestionById","variables":{"uuid":"'.$uuid.'"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"6fb6122e78f35ff4ef1005cadc05efa7359480ce0581b949ae946fef51659f59"}}}',
        CURLOPT_HTTPHEADER => array(
            'authority: gateway.chegg.com',
            'accept: */*, application/json',
            'accept-language: en-US,en;q=0.9',
            'apollographql-client-name: chegg-web',
            'apollographql-client-version: main-5ab73977-5576088612',
            'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
            'content-type: application/json',
            'cookie: CVID=d1f9c6c8-ec7b-4399-9b4b-d2ff2f278c23; V=b0434a206185d1248be05d0946eae46264326471c20c48.54596028; _scid=6c255f11-eeeb-4952-9f47-f526ef3502ac; OneTrustWPCCPAGoogleOptOut=true; _pxvid=adcf6dfd-d6bc-11ed-8e6d-0faf9f3ee8a1; C=0; O=0; exp=C026A; sbm_country=IN; _pubcid=ca273e29-6149-470c-a834-33f240966d09; _lr_env_src_ats=false; gid=1; gidr=MA; pbjs-unifiedid_cst=0Cw6LNAs7Q%3D%3D; _pubcid_cst=zix7LPQsHA%3D%3D; permutive-id=a8230da6-f7e3-4894-822f-5ee4d8850c78; _ga=GA1.1.323224641.1698858627; _hjSessionUser_3091164=eyJpZCI6ImQ4MTY0ZTRkLWZkZTctNTY0OS05OGRkLTA2MTU0NGY3ZjA0ZCIsImNyZWF0ZWQiOjE2OTg4NTg2MjY2NTQsImV4aXN0aW5nIjp0cnVlfQ==; expkey=031A203D5924CDC5B25EB8E2333DF41C; exp_id=c902e93cd0626069a79faa54c3bcd02a654558a8f95810.66458786; ab.storage.sessionId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22e5ac116f-243c-5471-fc60-267be20c5a74%22%2C%22e%22%3A1699045297078%2C%22c%22%3A1699043497079%2C%22l%22%3A1699043497079%7D; ab.storage.deviceId.49cbafe3-96ed-4893-bfd9-34253c05d80e=%7B%22g%22%3A%22e37fbffa-c67e-998e-455e-e6c03776bf68%22%2C%22c%22%3A1699043497086%2C%22l%22%3A1699043497086%7D; _ga_1Y0W4H48JW=GS1.1.1699043401.3.1.1699043675.0.0.0; _ga_HRYBF3GGTD=GS1.1.1699043400.3.1.1699043675.0.0.0; _sp_id.ad8a=8598e398-146e-457a-ad6c-1ef557423766.1696510704.4.1699680974.1698097029.30db20cc-8a0f-459f-b64a-824613ad4e52; opt-user-profile=b0434a206185d1248be05d0946eae46264326471c20c48.54596028%2C24115930466%3A24157310052%2C24985571146%3A24987031066; _vid_t=0f2pITEI2LF8uIGMF1tT5v18DlnwBpT9bjW45MyUPSlwXJGNcvDWWkxSUIgV77AUbG2EpwC2BAc/viEn9qSzJqcQTw==; DFID=web|yXBiJB2k7XUWqDDjbm7C; _pbjs_userid_consent_data=3524755945110770; _sharedid=000d51b9-597f-42d5-b061-9809491ad0c9; pbjs-unifiedid=%7B%22TDID%22%3A%223bc55e1c-a5d4-4638-b877-8d9a5af9ce43%22%2C%22TDID_LOOKUP%22%3A%22TRUE%22%2C%22TDID_CREATED_AT%22%3A%222023-10-15T11%3A23%3A53%22%7D; connectId=%7B%22vmuid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectId%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22ttl%22%3A24%2C%22he%22%3A%225ecf6d62dc5d50beea8797f84ab271ff4dc634f5f4fb408cfdf46be53bc94e02%22%2C%22lastSynced%22%3A1700047434018%2C%22lastUsed%22%3A1700047434018%7D; connectid=%7B%22vmuid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectid%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22connectId%22%3A%22KySDV45ROVyHmZA4nFaxcz9r2Ly3IzoTzFHASjHpbJdjgz0g9u4H1U_ZRIbZjS6nR9cg_jk2U5SJCl9oq7dH-g%22%2C%22ttl%22%3A24%2C%22he%22%3A%225ecf6d62dc5d50beea8797f84ab271ff4dc634f5f4fb408cfdf46be53bc94e02%22%2C%22lastSynced%22%3A1700047434018%2C%22lastUsed%22%3A1700047434018%7D; connectid_cst=0Cw6LNAs7Q%3D%3D; _sctr=1%7C1700159400000; __gads=ID=36304ba78a4a5160:T=1700047432:RT=1700253304:S=ALNI_MZ295J3vDa5ej2Bd0IXVMeI1TBoPw; __gpi=UID=00000c87d1bc609c:T=1700047432:RT=1700253304:S=ALNI_MYhff7elPkh8EWtC5_2gHFU8OB9uw; _awl=2.1700253381.5-c627b420b5c568406859e7b7638f97e9-6763652d617369612d6561737431-1; usprivacy=1YYY; _scid_r=6c255f11-eeeb-4952-9f47-f526ef3502ac; country_code=IN; hwh_order_ref=/homework-help/questions-and-answers/task2-curriculum-bachelor-science-task-create-knowledge-base-describing-courses-prerequisi-q119964027; CSID=1700499233437; pxcts=6547d14b-87c5-11ee-bf10-2842ecff1d6f; schoolapi=null; PHPSESSID=62flk55dgq1863cihkpquhbu78; CSessionID=b335218c-5a62-4353-8f1e-3e114296be20; user_geo_location=%7B%22country_iso_code%22%3A%22IN%22%2C%22country_name%22%3A%22India%22%2C%22region%22%3A%22DL%22%2C%22region_full%22%3A%22National+Capital+Territory+of+Delhi%22%2C%22city_name%22%3A%22Delhi%22%2C%22postal_code%22%3A%22110008%22%2C%22locale%22%3A%7B%22localeCode%22%3A%5B%22en-IN%22%2C%22hi-IN%22%2C%22gu-IN%22%2C%22kn-IN%22%2C%22kok-IN%22%2C%22mr-IN%22%2C%22sa-IN%22%2C%22ta-IN%22%2C%22te-IN%22%2C%22pa-IN%22%5D%7D%7D; _pxff_fp=1; sbm_a_b_test=1-control; ftr_blst_1h=1700499241699; _cc_id=5224b6adb348393dbd27b58a4198feea; panoramaId_expiry=1701104043476; panoramaId=d7959165730aa3084965a088e79516d5393847885fb7239a0429c8ad63ad8577; panoramaIdType=panoIndiv; forterToken=051b65f74be54aecbe9e6cd210cc9e81_1700499240767__UDF43-m4_13ck; id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6Im11c3RrZWVtb2ZmaWNpYWxAZ21haWwuY29tIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsImF1ZCI6IkNIR0ciLCJpYXQiOjE3MDA0OTkyNTAsImV4cCI6MTcxNjA1MTI1MCwic3ViIjoiNzdkZGU2ZDgtZGIwYi00YzkwLTgyMzUtMzk1YzFlYmU0Yzg3IiwicmVwYWNrZXJfaWQiOiJhcHciLCJjdHlwIjoiaWQiLCJpZHNpZCI6IjEyZGJkODYzIiwiaWRzdCI6MTcwMDQ5OTI1MDU1NiwiaWRzZyI6InBhc3N3b3JkbGVzcyJ9.OAkMcZUd_pOsNyZzAqIyq9VLjc3BFNw_TnS7baSx3IAZMce1U2DXL3VbRLNNvwz29jMISVQPaTet8b64hBEU7wXyN2JT8rncmcnlrG-fo1KH0gJiWuLcVzu9X9Z-unIspH2xUQp26J-iMzmbz4li5lFb7Ach5V_tfM973hNWzeiWezE8ajJ2RMA73ZInfi188xwAgCVs8HwEhE0KK1q8IBcG4WCKs6qv8LXAQz9GbgJX6dQ_Zpweuh18j1ghbrQKJpnSJS2wk2dcAGPUAEZIgsV4SUV4_NzQyaGUctV5VpMoeln8WyIXXkvnE5zQa0JO2Bdnvk1yepoeNTOCOoI5vw; access_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwczovL3Byb3h5LmNoZWdnLmNvbS9jbGFpbXMvYXBwSWQiOiJDSEdHIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsInN1YiI6Ijc3ZGRlNmQ4LWRiMGItNGM5MC04MjM1LTM5NWMxZWJlNGM4NyIsImF1ZCI6WyJ0ZXN0LWNoZWdnIiwiaHR0cHM6Ly9jaGVnZy1wcm9kLmNoZWdnLmF1dGgwLmNvbS91c2VyaW5mbyJdLCJpYXQiOjE3MDA0OTkyNTAsImV4cCI6MTcwMDUwMDY5MCwiYXpwIjoiRmlwajNhbkY0VXo4TlVZSE9jYmpMTXg2cTR6VktFT2UiLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIGFkZHJlc3MgcGhvbmUgb2ZmbGluZV9hY2Nlc3MiLCJndHkiOiJwYXNzd29yZCIsInJlcGFja2VyX2lkIjoiYXB3IiwiY2hnaHJkIjp0cnVlLCJjdHlwIjoiYWNjZXNzIn0.sFEXOHtw_2GqIhUqve-fOMudZu1b5h1pEWr8YywiD5rxfl0dO0smsnM4xLahzycOTylFZIs2AHBw60IKlL1DDuwL8NHmmQedKsUlba-DeP8eShySf5btWijVIGKh-7-M1pxXow3iquV_CDEHcn4GG0YalQP4laC6q6huxeTmmtOAYfO-Xp-7lNTu7BTGVmEgdXvJHR_9PV4x71_Yi1CtAsy0UY-_9mZ0iu5kPlJZFzW_bW1ZRgVtx5ZwQSZ_S3yc2wI3BqPw-O5uJk_1tD_MBLs1_mo3lj9RgefV_FvYDtGW1vv2UZ-HienaVhsggH3QMnvPOXlGl_gsMjumIu26xg; U=35e10940cc1e405f15beb1aa71c2eecd; SU=cgFvt3lxMoj2nk36qeMlDtBVR434GeWMgb6kH_oUpvZ342Jfjja7zQMGcOPECCyCguX5mCrpVMHh9J9GCvgVAY9vnJWiR36GHxYY0cq7QNwz5j416uHwJYg9P-JTeN4Q; OptanonConsent=isGpcEnabled=0&datestamp=Mon+Nov+20+2023+22%3A24%3A16+GMT%2B0530+(India+Standard+Time)&version=202310.2.0&isIABGlobal=false&hosts=&consentId=ec54b66b-f6a4-4247-8a8a-d0311a89fe7d&interactionCount=1&landingPath=NotLandingPage&groups=fnc%3A0%2Csnc%3A1%2Ctrg%3A0%2Cprf%3A0&AwaitingReconsent=false&browserGpcFlag=0; _px3=57ec2b6d526da01f42a30ac6d49681513fe427c70b4eae93bc6c5014495d69a4:946lUVQ4sH0OiYszVxKgx8E4vgzBayxW0uthG2SvP6pqEIRRDEDGj6YZ5S3rVsKT3H2j7Sj+rJ4RceLUlS6Y+A==:1000:4nfPJWldk4gWKjt5AvoopdLb1VnDMqNQB7T3qF1pW528FruTofUT3bZMo1UagUEjfdOMr+9bArV0a7g+pCs4YqtpmS5Whki0Tpx2qewQ+WpMP9/SDvu82ErCJdreTeM3mrrRHxJw45EAyi5tyUnnGbz7oZi0/rx+LL617mYqjME/o54ruCKdhLCEffepRnqLNVv05rLShk4kw1x9PJMabXFX8TBOh2vN6mQ4Tpl67V4=; _px=946lUVQ4sH0OiYszVxKgx8E4vgzBayxW0uthG2SvP6pqEIRRDEDGj6YZ5S3rVsKT3H2j7Sj+rJ4RceLUlS6Y+A==:1000:3w4jtF3awNF+UNeSSy7Ha918mL7NJGC+vMh60lw/YORQWPUnmhzYK7GXzXP9xUM0n73oQEmvjK1oKTwL8Y4eCKy1ut2i4K7wZdqVWHmSH62vSn6xK080cO6++0AVNA1Ti/mgzqCYrildo+QRdico/dkU7F+cVyQVQxv9DbcY5PVMNL7v6JAHL43+6Yr0a4VK3mJsJFc4suF9kTa8GZRZTelgwVAO2vkBl+tsjyJcTENXBZWdDDXIxmYe/FbH0TO6RiLPRgoZf6x0nAb/CWZjaQ==; _pxde=6b1c1d8e0736f7489d8ba26072b05c3fb65c3c43e10a9cd3d119abf7b6225cc6:eyJ0aW1lc3RhbXAiOjE3MDA0OTkyNjExMDd9',
            'dnt: 1',
            'origin: https://www.chegg.com',
            'referer: https://www.chegg.com/',
            'sec-ch-ua: "Chromium";v="118", "Google Chrome";v="118", "Not=A?Brand";v="99"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36'
        ),
    )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;
    $data =json_decode($response,true);
    $questionbody =$data['data']['questionByUuid']['content']['body'];
    $questiontran = $data['data']['questionByUuid']['content']['transcribedData'];

    return [$questionbody,$questiontran];

    }

    //answer_response 
    function answerresponse($idQ){
        $jsonFilePath = 'cookieschegg.json';
        $cookies = getRandomCookie($jsonFilePath);
        $validCookie = checkAndUpdateCookie($cookies, $jsonFilePath);
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
            CURLOPT_POSTFIELDS =>'{"operationName":"QnaPageAnswerSub","variables":{"id":'.$idQ.'},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"f820ff6ecd2a845d20524d72e1a9a06dee761c219c39f5d7f031d09b7823afe8"}}}',
            CURLOPT_HTTPHEADER => array(
                'authority: gateway.chegg.com',
                'accept: */*, application/json',
                'accept-language: en-US,en;q=0.9,ru;q=0.8',
                'apollographql-client-name: chegg-web',
                'apollographql-client-version: main-07b3436a-6108930852',
                'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                'content-type: application/json',
                'cookie: '.$validCookie,
                'dnt: 1',
                'origin: https://www.chegg.com',
                'referer: https://www.chegg.com/',
                'sec-ch-ua: "Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Linux"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-site',
                'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        //echo $response;
        $answer = json_decode($response, true);
        return [$answer,$response];
    }
    //like for html answer
    function htmlanslike($legacyId){
        /*$jsonFilePath = 'cookieschegg.json';
        $cookies = getRandomCookie($jsonFilePath);
        $validCookie = checkAndUpdateCookie($cookies, $jsonFilePath);
        */
        $jsonlike='{"operationName":"Reviews","variables":{"reviewForContentQueryArguments":{"contentId":"'.$legacyId.'","contentReviewType":"LIKE_DISLIKE","contentType":"ANSWER"}},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"2044a012e91d0bdd2959ed33ac5c0113c9315c48cf513a59c2c4281914ba01e8"}}}';
        $curllike = curl_init();

        curl_setopt_array($curllike, array(
            CURLOPT_URL => 'https://gateway.chegg.com/one-graph/graphql',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$jsonlike,
            CURLOPT_HTTPHEADER => array(
                'authority: gateway.chegg.com',
                'accept: */*',
                'accept-language: ar',
                'apollographql-client-name: chegg-web',
                'apollographql-client-version: main-67ea3c69-5199236990',
                'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                'content-type: application/json',
                'cookie: id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6Im11c3RrZWVtb2ZmaWNpYWxAZ21haWwuY29tIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsImF1ZCI6IkNIR0ciLCJpYXQiOjE3MDE4NjUzODksImV4cCI6MTcxNzQxNzM4OSwic3ViIjoiNzdkZGU2ZDgtZGIwYi00YzkwLTgyMzUtMzk1YzFlYmU0Yzg3IiwicmVwYWNrZXJfaWQiOiJhcHciLCJjdHlwIjoiaWQiLCJpZHNpZCI6Ijc1ZTJkM2JiIiwiaWRzdCI6MTcwMTg2NTM4OTM1MSwiaWRzZyI6InBhc3N3b3JkIn0.QRqSGtZ5OE2A_ImxXJ_T7K-FmX4J_egIMOBCLKMASlhQX7hdrPqQcUZrs4AdtqvnyR3yHlNKq1TJmy2JGRq7zLoYJamZmvZJQ5Ymz5lSXzHP67VuvyR0deTkx0CvvDDVQ25Z9prQq3OLvqDnWLTmFE3lsDgjvlQgcBjv-rCiJaoxRpdLUUJ5DZvisOmU2uTVQ_gZJ4ljN6noJBxvnREkGvt4qxrufF_dS5kzQMyFCRFSVHF7F-kDhzSE6Plc5bVXZ2vsaQwipF26c1FOSNwmvbBDGp6zQABt0aExqS0y50mQIXuAdMJjW-I1ThzMkzTeJexRmiB-GiI-FdOTLBaXew',
                'dnt: 1',
                'origin: https://www.chegg.com',
                'referer: https://www.chegg.com/',
                'sec-ch-ua: ".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-site',
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36'
            ),
        ));
        $responselike = curl_exec($curllike);
        curl_close($curllike);
        $answerlike = json_decode($responselike, true);
        try {
            if (isset($answerlike['data']['allReviews'][0]['count'])) {
                $like = $answerlike['data']['allReviews'][0]['count'];
            } else {
                $like = 0;
            }
        
            if (isset($answerlike['data']['allReviews'][1]['count'])) {
                $dislike = $answerlike['data']['allReviews'][1]['count'];
            } else {
                $dislike = 0;
            }
        } catch (Exception $e) {
            //echo $e;
            // Handle the exception here if needed
        }
        return [$like,$dislike];
    }
    //get Problem ID & subject id
    function getProblemID($urlchegg){
        $op =$urlchegg;
        $pattern = '/chapter-(.*)-problem-(.*)-solution-(.*)/';
        preg_match_all($pattern, $op, $matches);
        //echo $matches[1][0];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://gateway.chegg.com/study-bff-web/graphql',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"operationName":"tbsProblemDetailsSignedOut","variables":{"isbn13":"'.$matches[3][0].'","chapterName":"'.strtoupper($matches[1][0]).'","problemName":"'.strtoupper($matches[2][0]).'"},"query":"query tbsProblemDetailsSignedOut($isbn13: String, $chapterName: String, $problemName: String) {\n  textbook_solution(isbn13: $isbn13) {\n    isbn13\n    isbn10\n    editionName\n    editionNumber\n    editionNumberOrdinal\n    tbsOrganicUrl\n    solutionManualSM3Url\n    book {\n      title\n      languageId\n      __typename\n    }\n    bookAuthor {\n      name\n      url\n      __typename\n    }\n    alternateISBNDetails {\n      isPrimary\n      secondaryIsbn13s\n      primaryBookTitle\n      primaryBookEdition\n      primaryBookURL\n      bookURL\n      __typename\n    }\n    subjectData {\n      subjectData {\n        id\n        name\n        __typename\n      }\n      subSubjectData {\n        id\n        name\n        __typename\n      }\n      parentSubjectData {\n        id\n        name\n        __typename\n      }\n      __typename\n    }\n    pdpUrl\n    coverImageURL {\n      coverImageLargeURL\n      __typename\n    }\n    solutionCount {\n      totalSolutionCount\n      __typename\n    }\n    chapter(chapterName: $chapterName) {\n      problems(problemName: $problemName, chapterName: $chapterName) {\n        problemHtml\n        canonicalUrl\n        problemOrganicUrl\n        hasSolution\n        problemBreadCrumb {\n          name\n          url\n          label\n          __typename\n        }\n        problemName\n        problemId\n        __typename\n      }\n      __typename\n    }\n    TOC: chapter {\n      chapterId\n      chapterOrganicUrl\n      chapterName\n      problems(chapterName: $chapterName) {\n        problemName\n        problemOrganicUrl\n        problemId\n        __typename\n      }\n      __typename\n    }\n    bookmarkData {\n      id\n      __typename\n    }\n    __typename\n  }\n}\n"}',
            CURLOPT_HTTPHEADER => array(
                'authority: gateway.chegg.com',
                'accept: */*',
                'accept-language: en-US,en;q=0.9',
                'content-type: application/json',
                'cookie: CVID=1849aa1b-3490-44ce-8c9f-2cbc4c17c1ee; CSID=1697576896640; V=58a6861c13a0e3af2af9dd64dfc5fe4d652ef7c2726e98.46287090; pxcts=4c435ded-6d31-11ee-b4c6-675aaf520ba4; _pxvid=4c434ea4-6d31-11ee-b4c6-9ffdfa53dc69; CSessionID=975d27ed-3e1e-4f93-82f0-b5f01ddd197a; OptanonConsent=isGpcEnabled=0&datestamp=Wed+Oct+18+2023+02%3A48%3A40+GMT%2B0530+(India+Standard+Time)&version=202309.1.0&browserGpcFlag=0&isIABGlobal=false&hosts=&consentId=6fc914b5-8d60-42ca-a9d1-68bd54d8679e&interactionCount=1&landingPath=NotLandingPage&groups=fnc%3A0%2Csnc%3A1%2Ctrg%3A0%2Cprf%3A0&AwaitingReconsent=false; hwh_order_ref=https%3A//www.chegg.com/homework-help/estimated-regression-equation-model-involving-two-independen-chapter-13-problem-1e-solution-9781337516563-exc; _px3=122ac4b792a5eec00d62d85227366ae780dbb09f885d611710632020804f3552:HoCaPu0YaZk03+/a01uVeuHIdlJHesNA3sEaIwSCq9sl/JcLUeL6qfNz8C08+A0ZqYIUKoHJBCFu7eGdjW0iTQ==:1000:dtFqneG6I8JrEDizZf0NEBkzzQt99w5lMmahzbPlohRPybzBfzUNFicPfmjHHWCJuUecGejxGACltSoRAkNc1hF+zfvj5wVndYJLxK8JS9UYKrrRVeq5nx6xMuD5qMp/6iU3j7aJJgUUDQvvT7oEVisPy99BB9x9dqVrgUpAkApUS7Fo1I/WNVbGsv73k9V1IX7RANdcywxLSbmhFmv2YpAlJIuO5RHW2fg6dKbxC2I=; _px=HoCaPu0YaZk03+/a01uVeuHIdlJHesNA3sEaIwSCq9sl/JcLUeL6qfNz8C08+A0ZqYIUKoHJBCFu7eGdjW0iTQ==:1000:sOn2lYAugDEl4G8jxoDf7YQSbweP4PZj058aU8IVfUvmgssc0VOI6jYvlMFmKCJCZW7eYixF9UNAh9z/iY8cFaq3ngEv+tdeYF6EYtFTVh1S3gd0FGmnoQA9TR05OnsIPqZRibjI4g7ALzLKFliZu6yViIwFwvWwyBno2YSFEelzUbS4UfoatvMbMziHPOPeYcY8PnIiBOUGKiOHEwSlpIR7GBH3+WtC/zCp/RRTwZIlWdHqEtXrEiPCqOsyG93Q9EohG3kauIWrzGJqHkFe6w==; _pxde=a3974e0b851dca99e28e57dfacea716439f929aa7938c4bc7a8384c850b848e5:eyJ0aW1lc3RhbXAiOjE2OTc1Nzc1Mjc0NjR9; CSID=1697576923947; CVID=c8aad436-4f10-431f-b6c5-500ebe740842; SU=U2s9Eb8u9Kez3awAyWrigaCsMpQ8IgfNxQSfYCm4MOjnmhdX5I5XJMfHlcEb9j3sWdHs46VB4imHQ5K3ZbkYqZlJKOaBoGEUMzYFU5tYpf7ivUO8yD57bl2IL6lEZw2t; country_code=IN; exp=C026A; exp_id=e7a6fa48-5a43-4cd2-97fb-e826ce600836; expkey=1779983DCB20FDA48CAD8C384918B961; id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6InNhZWFqc3VjYXN1eXRhaXdqeWFzaWlldTk4NzJAb3V0bG9vay5jb20iLCJpc3MiOiJodWIuY2hlZ2cuY29tIiwiYXVkIjoiQ0hHRyIsImlhdCI6MTY5NzM4NjY0NCwiZXhwIjoxNzEyOTM4NjQ0LCJzdWIiOiI0MWE1Yjk1YS04MDNlLTQ2ODktYjM5Yy01NTkyODBkNmZmOTEiLCJyZXBhY2tlcl9pZCI6ImFwdyIsImN0eXAiOiJpZCJ9.tkMOMYr3TEMh3JYJ-MjDVDUFkWtbmjOUxmauzjGQMls2ihALIFkspjXaOo8yterF7NiVbB9tG7yxY_ovhMQaxiYC9zehoEtCxl1lKDxDr_fTGqqjlJR7VqvK_3TkGsjUuxO3g9ARmLrAiyc7vGyBvxiFQAsiaCgTKqnzCjcbXSaQeQ-ol1oLuKPWd9EdpDDllH8q5lVRtGpw6yP-NyDZxlvjrH6OebzFCzoXomBqrYEkIoEHfSpOf1dcCin13HlFGyt3GFSc5Mz33JVAYqlvyWs4S3AnDhd5MoHyegvQZyfDQRT1VVPVNIOVN82IAeL1xTlRIou4MUenzCO-uR5aAA; refresh_token=ext.a0.t00.v1.MZzokeXx1ywEHW06qQZiJoJeq3pilh6dLhgyPLopsgn21q9eOieu-Ooqz0LAmXmV7NMazm_BsoWyDwVvEJyWf7E',
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
        $isbn13=json_decode($response, true)["data"]["textbook_solution"]["isbn13"];
        //echo $isbn13;
        $chapter = json_decode($response, true)["data"]["textbook_solution"]["chapter"];
        $questionhtml = json_decode($response, true)["data"]["textbook_solution"]["chapter"][0]["problems"][0]["problemHtml"];
        foreach($chapter as $x){
            foreach($x['problems'] as $problemName){
                if ($problemName['problemName'] == strtoupper($matches[2][0])) {
                    $problemId=$problemName['problemId'];
                    //echo $problemId;
                }
            }
        }
        // Now you can use $chapterId and $problemId for further processing.
        return [$problemId,$isbn13,$questionhtml];
    }
    //textbook like && dislike
    function textbookrating($contentId){
        /*$jsonFilePath = 'cookieschegg.json';
        $cookies = getRandomCookie($jsonFilePath);
        $validCookie = checkAndUpdateCookie($cookies, $jsonFilePath);
        */
        $jsonPosttbslike='{"operationName":"AllReviews","variables":{"reviewForContentQueryArguments":{"contentId":"'.$contentId .'","contentReviewType":"LIKE_DISLIKE","contentType":"SOLUTION"}},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"e34176a12c96329dc705bdd05c1b6e3a65bd819be9be754f99ca806524acf6f8"}}}';
        
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
            CURLOPT_POSTFIELDS =>$jsonPosttbslike,
            CURLOPT_HTTPHEADER => array(
            'authority: gateway.chegg.com',
            'accept: */*',
            'accept-language: ar',
            'apollographql-client-name: chegg-web',
            'apollographql-client-version: main-61879f0a-5319065108',
            'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
            'content-type: application/json',
            'cookie: id_token=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJlbWFpbCI6Im11c3RrZWVtb2ZmaWNpYWxAZ21haWwuY29tIiwiaXNzIjoiaHViLmNoZWdnLmNvbSIsImF1ZCI6IkNIR0ciLCJpYXQiOjE3MDE4NjUzODksImV4cCI6MTcxNzQxNzM4OSwic3ViIjoiNzdkZGU2ZDgtZGIwYi00YzkwLTgyMzUtMzk1YzFlYmU0Yzg3IiwicmVwYWNrZXJfaWQiOiJhcHciLCJjdHlwIjoiaWQiLCJpZHNpZCI6Ijc1ZTJkM2JiIiwiaWRzdCI6MTcwMTg2NTM4OTM1MSwiaWRzZyI6InBhc3N3b3JkIn0.QRqSGtZ5OE2A_ImxXJ_T7K-FmX4J_egIMOBCLKMASlhQX7hdrPqQcUZrs4AdtqvnyR3yHlNKq1TJmy2JGRq7zLoYJamZmvZJQ5Ymz5lSXzHP67VuvyR0deTkx0CvvDDVQ25Z9prQq3OLvqDnWLTmFE3lsDgjvlQgcBjv-rCiJaoxRpdLUUJ5DZvisOmU2uTVQ_gZJ4ljN6noJBxvnREkGvt4qxrufF_dS5kzQMyFCRFSVHF7F-kDhzSE6Plc5bVXZ2vsaQwipF26c1FOSNwmvbBDGp6zQABt0aExqS0y50mQIXuAdMJjW-I1ThzMkzTeJexRmiB-GiI-FdOTLBaXew',
            'dnt: 1',
            'origin: https://www.chegg.com',
            'referer: https://www.chegg.com/',
            'sec-ch-ua: ".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36'
            ),
        ));
        
        $responsetbslike = curl_exec($curl);
        curl_close($curl);
        $datatbslike = json_decode($responsetbslike, true);
        try {
            if (isset($datatbslike['data']['reviewForContent'][0]['count'])) {
                $like = $datatbslike['data']['reviewForContent'][0]['count'];
            } else {
                $like = 0;
            }
        
            if (isset($datatbslike['data']['reviewForContent'][1]['count'])) {
                $dislike = $datatbslike['data']['reviewForContent'][1]['count'];
            } else {
                $dislike = 0;
            }
        } catch (Exception $e) {
            //echo $e;
            // Handle the exception here if needed
        }
        return [$like,$dislike];
    }
    //textbook answer
    function textbookanswer($urls){
        $jsonFilePath = 'cookieschegg.json';
        $cookies = getRandomCookie($jsonFilePath);
        $validCookie = checkAndUpdateCookie($cookies, $jsonFilePath);
        $allnumber = getProblemID($urls); //get problemID
        $jsonPosttbs='{"operationName":"SolutionContent","variables":{"ean":"'.$allnumber[1].'","problemId":"'.$allnumber[0].'"},"extensions":{"persistedQuery":{"version":1,"sha256Hash":"0322a443504ba5d0db5e19b8d61c620d5cab59c99f91368c74dcffdbea3e502f"}}}';
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
            CURLOPT_POSTFIELDS =>$jsonPosttbs,
            CURLOPT_HTTPHEADER => array(
                'authority: gateway.chegg.com',
                'accept: */*, application/json',
                'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7,hi;q=0.6',
                'apollographql-client-name: chegg-web',
                'apollographql-client-version: main-61879f0a-5319065108',
                'authorization: Basic TnNZS3dJMGxMdVhBQWQwenFTMHFlak5UVXAwb1l1WDY6R09JZVdFRnVvNndRRFZ4Ug==',
                'content-type: application/json',
                'cookie: '.$validCookie,
                'origin: https://www.chegg.com',
                'referer: https://www.chegg.com/',
                'sec-ch-ua: "Google Chrome";v="113", "Chromium";v="113", "Not-A.Brand";v="24"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Linux"',
                'sec-fetch-dest: empty',
                'sec-fetch-mode: cors',
                'sec-fetch-site: same-site',
                'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36',
                ),
            ));
        $responsetbs = curl_exec($curl);
        curl_close($curl);//echo $responsetbs;
        $datatbs = json_decode($responsetbs, true);
        return $datatbs;
    }
    // Define buildCss() function in a higher scope
    function buildCss($styles){
        $css = '';
        $cssProperties = [
            "textAlign" => "text-align",
            "verticalAlign" => "vertical-align",
            "borderBottom" => "border-bottom"
        ];

        foreach ($styles as $key => $value) {
            // Check if the key needs replacement
            $keyToUse = array_key_exists($key, $cssProperties) ? $cssProperties[$key] : $key;
            $css .= $keyToUse . ': ' . $value . ';';
        }
        return $css;
    }
    // Extract and process cell data
    function extractCellData($cells)
    {
        $extractedData = array();

        foreach ($cells as $cellKey => $cell) {
            if ($cell && isset($cell['value']) && isset($cell['value']['blocks'])) {
                $extractedData[$cellKey] = implode(' ', array_map(function ($block) {
                    return $block['text'];
                }, $cell['value']['blocks']));
            }
        }

        return $extractedData;
    }
    //TABLE
    function generateTableHTML($block) {
        if ($block['type'] === 'TABLE') {
            $table = $block['block'];
            $tableHTML = '<table border="4">';
            
            for ($row = 0; $row < $table['rows']; $row++) {
                $tableHTML .= '<tr>'; 
                
                for ($col = 0; $col < $table['columns']; $col++) {
                    $cellKey = "{$col}-{$row}";
                    
                    if (isset($table['cells'][$cellKey]['value']['content'])) {
                        $cellValue = "";
                        
                        foreach ($table['cells'][$cellKey]['value']['content'] as $content) {
                            if (isset($content['content'])) {
                                foreach ($content['content'] as $contentElement) {
                                    if (isset($contentElement['text'])) {
                                        $cellValue .= $contentElement['text'] . "<br>";
                                    }
                                }
                            }
                        }
                        
                        $tableHTML .= "<td><br>$cellValue</td>"; 
                    } else {
                        if ($block && isset($block['block']['cells'])) {
                            $rowCount = 0;
                            $tableHTML .= '<table border="5">';
                            
                            foreach ($block['block']['cells'] as $cell) {
                                $cell2 = $cell['value']['blocks'];
                                $cellText = '';
                                
                                foreach ($cell2 as $cell3) {
                                    if (isset($cell3['text'])) {
                                        $cellText .= $cell3['text'];
                                    }
                                }
                                
                                if ($rowCount == 0) {
                                    $tableHTML .= '<tr><th>' . $cellText . '</th>';
                                } else {
                                    $tableHTML .= '<td>' . $cellText . '</td>';
                                }
                                
                                if (($rowCount + 1) % $table['columns'] == 0) {
                                    $tableHTML .= '</tr>'; 
                                }
                                
                                $rowCount++;
                            }
                        }
                    }
                }
                
                $tableHTML .= '</tr>'; 
            }
            
            $tableHTML .= '</table>'; 
            return $tableHTML;
        }
        
        return ''; // Return an empty string if the block type is not 'TABLE'
    }
    //*********sqnaanswer*********//
    function extractTextAndImages($data) {
        $elements = [];
        $stepCount = count($data['stepByStep']['steps']);
        if (isset($data['stepByStep']['steps'])) {
            foreach ($data['stepByStep']['steps'] as $index => $step) {
                $stepName = '<h3>Step ' . ($index + 1) . '/' . $stepCount . '</h3>';
                $elements[] = '<div class="steps" style="width: 100%; background-color: rgb(239, 245, 254); line-height: 1; font-size: 0.875rem; font-weight: bold; padding: 0.75rem 1rem; border: 1px solid rgb(231, 231, 231); box-sizing: border-box; display: flex; justify-content: space-between; cursor: pointer;">' . $stepName . '</div>';
                foreach ($step['blocks'] as $block) {
                    //entity map
                    if (isset($block['block']['editorContentState']['blocks']) || isset($block['block']['editorContentState']['entityMap'])) {
                        $text='';
                        foreach ($block['block']['editorContentState']['blocks'] as $textBlock) {
                            if (isset($textBlock['text']) && !empty($textBlock['text'])) {
                                $text.= $textBlock['text'];
                                $entityKey = $textBlock['entityRanges'][0]['key']; 
                                $entity = $block['block']['editorContentState']['entityMap'][$entityKey];
                                if ($entity) {
                                    $entityType = $entity['type'];
                                    $entityData = $entity['data'];
                                    if ($entityType === 'INLINE-EQUATION' ||$entity['type'] === 'CHEM-INLINE-EQUATION'|| isset($entityData['text'])) {
                                        $text.= '`'.$entityData['text'].'`';
                                    }
                                }
                            }
                        }
                        if(isset($text) && !empty($text)){
                            $elements[] = '<p>' . $text . '</p>';
                        }
                    }                                                                                                
                    //TEXT
                    if (($block['type'] === 'TEXT')){
                        if($block['block']['editorContentState']['type']=== 'doc'){
                            foreach($block['block']['editorContentState']['content'] as $content){
                                $textAlign=$content['attrs']['textAlign'];
                                //paragraph
                                if($content['type'] ==='paragraph'){
                                    $paratext='';
                                    foreach($content['content'] as $content2){
                                        if($content2['type']==='text'){
                                            if(isset($content2['text']) && !empty($content2['text'])){
                                                $paratext.= $content2['text'];
                                            }    
                                        }
                                        //inlineMath
                                        if($content2['type'] ==='inlineMath'){
                                            foreach($content2['content'] as $content3){
                                                if(isset($content3['text']) && !empty($content3['text'])){
                                                    $paratext.= '`'.$content3['text'].'`';
                                                }
                                            }
                                        }
                                    }
                                    $elements[]= '<p>'.$paratext.'</p>';
                                }
                                //heading
                                if($content['type'] === 'heading') {
                                    $textAlign = $content['attrs']['textAlign'];
                                    $level = $content['attrs']['level'];
                                    foreach($content['content']as $content2){
                                        if(isset($content2) && $content2['type']=="text"){
                                            foreach($content2['marks'] as $mark){
                                                if ($mark['type'] === 'bold') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><strong>' .$content2['text'] . '</strong></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'underline') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><u>' .$content2['text'] . '</u></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'italic') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><i>' .$content2['text'] . '</i></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'code') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><strong>' .$content2['text'] . '</strong></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'strikethrough') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><s>' .$content2['text'] . '</s></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'superscript') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><sup>' .$content2['text'] . '</sup></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'subscript') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><sub>' .$content2['text'] . '</sub></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'highlight') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><mark>' .$content2['text'] . '</mark></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'link') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><a href="' .$content2['text'] . '>' .$content2['text'] . '</a></h'.$level.'>'; 
                                                }
                                            }
                                        }
                                    }
                                }
                                //bulletlist
                                if ($content['type'] === 'bulletList') {
                                    foreach ($content['content'] as $item) {
                                        if ($item['type'] === 'listItem') {
                                            foreach ($item['content'] as $paragraph) {
                                                if(isset($paragraph['text']) && !empty($paragraph['text'])) {
                                                    $elements[] = '<p>' . $paragraph['text'] . '</p>';
                                                }
                                                if ($paragraph['type'] === 'paragraph') {
                                                    $displayText = '';
                                                    foreach ($paragraph['content'] as $textItem) {
                                                        if ($textItem['type'] === 'text') {
                                                            $displayText .= $textItem['text'];
                                                        } elseif ($textItem['type'] === 'inlineMath') {
                                                            foreach ($textItem['content'] as $mathText) {
                                                                $displayText .= '`'.$mathText['text'].'`';
                                                            }
                                                        }
                                                    }
                                                    $elements[] = '<li>'.$displayText .'</li>';
                                                }
                                            }
                                        }
                                    }
                                }
                                

                               
                                //orderlsit
                                if ($content['type'] === 'orderedList'){
                                    $content422text='';
                                    foreach($content['content']as $content2){
                                        if ($content2['type'] ==='listItem'){
                                            foreach($content2['content'] as $content3){
                                                if ($content3['type'] === 'paragraph') {
                                                    $textAlign = $content3['attrs']['textAlign'];
                                                    foreach($content3['content'] as $content4) {
                                                        if ($content4['type'] === 'text') {
                                                            if(isset($content4['text']) && !empty($content4['text'])){
                                                                $content422text.=$content4['text'];
                                                            }  
                                                        }
                                                        if (isset($content4['type']) && $content4['type'] === 'inlineMath') {
                                                            $mathtype = $content4['attrs']['mathType'];
                                                            if($mathtype=== 'mhchem'){
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $content422text.='`'.$content5['text'].'`';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            else{
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $content422text.='`'.$content5['text'].'`';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if(isset($content422text) && !empty($content422text)){
                                        $elements[] ='<li>' .$content422text. '</li>';
                                    }                          
                                }
                            }
                        }  
                    }
                    //TWO_LINE_MIT_BLOCK
                    if (isset($block['block']['subBlock']) && !empty($block['block']['subBlock']) && $block['block']['subBlock'] === 'TWO_LINE_MIT_BLOCK') {
                        if ($block['block']['title']['type'] === 'doc' ||$block['block']['result']['type'] === 'doc'||$block['block']['expression']['type'] === 'doc') {
                            foreach ($block['block']['title']['content'] as $content) {
                                if ($content['type'] === 'paragraph') {
                                    foreach ($content['content'] as $element) {
                                        if (isset($element['text']) && !empty($element['text'])) {
                                            $elements[] = '<p>' . $element['text'] . '</p>';
                                        }
                                    }
                                }
                            }
                            foreach ($block['block']['expression']['content'] as $content) {
                                if ($content['type'] === 'paragraph') {
                                    foreach ($content['content'] as $element) {
                                        if (isset($element['text']) && !empty($element['text'])) {
                                            $elements[] = '<p>`' . $element['text'] . '`</p>';
                                        }
                                    }
                                }
                            }
                            foreach ($block['block']['result']['content'] as $content) {
                                if ($content['type'] === 'paragraph') {
                                    foreach ($content['content'] as $element) {
                                        if (isset($element['text']) && !empty($element['text'])) {
                                            $elements[] = '<p>`' . $element['text'] . '`</p>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //THREE_LINE_MIT_BLOCK
                    if(isset($block['block']['subBlock']) && !empty($block['block']['subBlock']) && $block['block']['subBlock'] === 'THREE_LINE_MIT_BLOCK'){
                        foreach ($block['block']['title']['editorContentState']['blocks'] as $element) {
                            if (isset($element['text']) && !empty($element['text'])) {
                                        $elements[] = '<p>' . $element['text'] . '</p>';
                                    }
                                }
                        foreach ($block['block']['expression']['editorContentState']['blocks'] as $element) {
                            if (isset($element['text']) && !empty($element['text'])) {
                                        $elements[] = '<p>' . $element['text'] . '</p>';
                                    }
                                }
                        foreach ($block['block']['result']['editorContentState']['blocks'] as $element) {
                            if (isset($element['text']) && !empty($element['text'])) {
                                        $elements[] = '<p>' . $element['text'] . '</p>';
                                    }
                                }
                        foreach($block['block']['title']['content'] as $content){
                            if($content['type'] === 'paragraph'){
                                foreach ($content['content'] as $content2){
                                    if(isset($content2['text']) && !empty($content2['text'])){
                                        $elements[] = '<p>' . $content2['text'] . '</p>';
                                    }
                                }
                            }
                        }
                        foreach($block['block']['expression']['content'] as $content){
                            if($content['type'] === 'paragraph'){
                                foreach ($content['content'] as $content2){
                                    if(isset($content2['text']) && !empty($content2['text'])){
                                        $elements[] = '<p>' . $content2['text'] . '</p>';
                                    }
                                }
                            }
                        }          
                        foreach($block['block']['result']['content'] as $content){
                            if($content['type'] === 'paragraph'){
                                foreach ($content['content'] as $content2){
                                    if(isset($content2['text']) && !empty($content2['text'])){
                                        $elements[] = '<p>' . $content2['text'] . '</p>';
                                    }
                                }
                            }
                        }        
                    }
                    //EQUATION_RENDERER
                    if ($block['type']==='EQUATION_RENDERER'|| isset($block['block']['lines'])) {
                        foreach ($block['block']['lines'] as $line) {
                            $elements[] = '<p>`'.$line['left'].$line['operator'].$line['right'].'`</p>';
                            //echo "Left: " . $line['left'] . "\n"; echo "Right: " . $line['right'] . "\n"; echo "Operator: " . $line['operator'] . "\n\n";
                        }
                    }
                    //CODE_SNIPPET
                    if($block['type']==='CODE_SNIPPET'){
                        foreach($block['block'] as $contentcode){
                            if($contentcode['type'] === 'doc'){
                                foreach($contentcode['content'] as $contentcode2){
                                    if($contentcode2['type'] === 'codeBlock'){
                                        foreach($contentcode2['content'] as $contentcode3){
                                            if(isset($contentcode3['text']) && !empty($contentcode3['text'])){
                                                $elements[] = '<div class="code_line"><pre><code>'.$contentcode3['text'].'</code></pre></div>';
                                            }
                                        }
                                    }
                                }
                            }
                        }  
                    }
                    //codeData
                    if(isset($block['block']['codeData']) && !empty($block['block']['codeData'])){
                        $escapedCodeData = htmlspecialchars($block['block']['codeData']);
                        $elements[] = '<div class="code_line"><pre><code class="language-html line-numbers" data-prismjs-copy="Copy the HTML snippet!">' . $escapedCodeData . '</code></pre></div>';
                    }
                    //codeData2
                    if(isset($block['block']['codeData'])){
                        $elements[] = '<div class="code_line"><pre><code>'.$block['block']['codeData'].'</code></pre></div>';
                    }
                    //EXPLANATION
                    if($block['type']==='EXPLANATION'){
                        if($block['block']['editorContentState']['type']=== 'doc'){
                            foreach($block['block']['editorContentState']['content'] as $content){
                                $textAlign=$content['attrs']['textAlign'];
                                //paragraph
                                if($content['type'] ==='paragraph'){
                                    $decodedText = '';
                                    foreach($content['content'] as $content2){
                                        if($content2['type']==='text'){
                                            if(isset($content2['text']) && !empty($content2['text'])){
                                                $decodedText.=$content2['text'];
                                            }    
                                        }
                                        //inlineMath
                                        if($content2['type'] ==='inlineMath'){
                                            $mathtype =$content2['attrs']['mathType'];
                                            foreach($content2['content'] as $content3){
                                                if(isset($content3['text']) && !empty($content3['text'])){
                                                    $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><p>`'.$content3['text'].'`</p></fieldset>';
                                                }
                                            }
                                        }
                                    }
                                    $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><p align="'.$textAlign.'">'.$decodedText.'</p></fieldset>';
                                }
                                //heading
                                if($content['type'] === 'heading') {
                                    $textAlign = $content['attrs']['textAlign'];
                                    $level = $content['attrs']['level'];
                                    foreach($content['content']as $content2){
                                        if(isset($content2['text']) &&!empty($content2['text'])){
                                            $elements[] ='<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><h'.$level.' align="' . $textAlign . '">' .$content2['text'] . '</h'.$level.'></fieldset>';
                                        }
                                    }
                                }
                                //bulletlist
                                if ($content['type'] ==='bulletList'){
                                    foreach($content['content'] as $listItem){
                                        if ($listItem['type'] ==='listItem'){
                                            foreach($listItem['content'] as $itemContent){
                                                $elements[] = '<p>`'.$itemContent['text'].'`</p>';
                                                $textAlign=$itemContent['attrs']['textAlign'];
                                                if ($itemContent['type'] ==='paragraph'){
                                                    $content4text='';
                                                    foreach($itemContent['content'] as $content4){
                                                        if(isset($content4['text']) && !empty($content4['text'])){
                                                            $content4text.= $content4['text'];
                                                        }
                                                        if($content4['type']==='inlineMath'){
                                                            $content5text='';
                                                            foreach($content4['content'] as $content5){
                                                                if(isset($content5['text']) && !empty($content5['text'])){
                                                                    $content5text.= $content5['text'];
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if(isset($content4text) && !empty($content4text)){
                                                        $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><li>'.$content4text.'</li></fieldset>';
                                                    }
                                                    if(isset($content5text) && !empty($content5text)){
                                                        $elements[] = '<p>`'.$content5text.'`<p>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //orderlsit
                                if ($content['type'] === 'orderedList'){
                                    foreach($content['content']as $content2){
                                        if ($content2['type'] ==='listItem'){
                                            foreach($content2['content'] as $content3){
                                                if ($content3['type'] === 'paragraph') {
                                                    $textAlign = $content3['attrs']['textAlign'];
                                                    $content42text='';
                                                    foreach($content3['content'] as $content4) {
                                                        if ($content4['type'] === 'text') {
                                                            foreach($content4['marks'] as $marks){
                                                                if($marks['type'] === 'bold'){
                                                                    if(isset($content4['text']) && !empty($content4['text'])){
                                                                        $content42text.='<strong>'.$content4['text'].'</strong>';
                                                                    } 
                                                                }
                                                                if($mark['type'] === 'underline'){
                                                                    if(isset($content4['text']) && !empty($content4['text'])){
                                                                        $content42text.='<u>'.$content4['text'].'</u>';
                                                                    } 
                                                                }
                                                            }
                                                             
                                                        }
                                                        if (isset($content4['type']) && $content4['type'] === 'inlineMath') {
                                                            $mathtype = $content4['attrs']['mathType'];
                                                            if($mathtype=== 'mhchem'){
                                                                $content52text= '';
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $content52text.= $content5['text'];
                                                                        }
                                                                    }
                                                                }
                                                                $elements[] ='<p>`' . $content52text . '`</p>';
                                                            }
                                                            else{
                                                                $content522text='';
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $content522text.= $content5['text'];
                                                                        }
                                                                    }
                                                                }
                                                                if(isset($content522text) && !empty($content522text)){
                                                                $elements[] ='<p>`'.$content522text.'`</p>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if($content3['type'] === 'bulletList'){
                                                    foreach($content3['content'] as $content4) {
                                                        if($content4['type'] === 'listItem'){
                                                            foreach($content4['content'] as $content5){
                                                                if($content5['type'] === 'paragraph'){
                                                                    foreach($content5['content'] as $content6){
                                                                        if($content6['type'] === 'text'){
                                                                            $content6text= $content6['text'];
                                                                            $content42text.='<p>'.$content6text.'</p>';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if(isset($content42text) && !empty($content42text)){
                                                $elements[] ='<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><li>' .$content42text . '</li></fieldset>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //CHEMISTRY
                    if($block['type'] === 'CHEMISTRY'){
                        $svg= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
                        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" width="1000" height="700">';

                        // Loop through the shapes in the JSON and add them to the SVG
                        foreach ($block['block']['shapes'] as $shape) {
                            if ($shape['type'] === 'Bond') {
                                $x1 = $shape['points'][0]['x'] + $shape['x'];
                                $y1 = $shape['points'][0]['y'] + $shape['y'];
                                $x2 = $shape['points'][1]['x'] + $shape['x'];
                                $y2 = $shape['points'][1]['y'] + $shape['y'];
                                $strokeWidth = 2;
                                $strokeWidth2 = 10;
                                $strokeWidth3 = 5;

                                if ($shape['bondType'] === 'Single') {
                                    $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="black" stroke-width="' . $strokeWidth . '" />';
                                } elseif ($shape['bondType'] === 'Wedge') {
                                    // Add code for the wedge bond
                                    $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="black" stroke-width="' . $strokeWidth2 . '" stroke-dasharray="20" />';
                                } elseif ($shape['bondType'] === 'Hashed wedge') {
                                    // Add code for the hashed wedge bond
                                    $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="black" stroke-width="' . $strokeWidth3 . '" stroke-dasharray="2,2" />';
                                }
                            } elseif ($shape['type'] === 'Text' or $shape['type'] === 'InlineChemShape' ) {
                                $x = $shape['x'];
                                $y = $shape['y'];
                                $fontSize = $shape['style']['fontSize'];
                                foreach ($shape['value'] as $item) {
                                    $text = $item['text'];
                                // echo $text . "\n";
                                    
                                }

                                // Check if the text is not empty
                                if (!empty($text)) {
                                    $svg .='<text x="' . $x . '" y="' . $y . '" fill="rgb(51, 51, 51)" stroke="rgb(51, 51, 51)" stroke-width="0" stroke-dasharray="0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" dominant-baseline="text-before-edge" vector-effect="non-scaling-stroke" font-size="' . $fontSize . '" transform-origin="' . $x . ' ' . $y . '" style="white-space: nowrap;"><tspan>' . $text . '</tspan></text>';
                                    }
                            }
                            elseif ($shape['type'] === 'Line') {
                                [$startPoint, $endPoint] = $shape['points'];
                                $dashArray = isset($shape['style']['strokeDasharray']) ? 'stroke-dasharray:' . $shape['style']['strokeDasharray'] . ';' : '';
                                $svg .= '<line x1="' . ($startPoint['x'] + $shape['x']) . '" y1="' . ($startPoint['y'] + $shape['y']) . '" x2="' . ($endPoint['x'] + $shape['x']) . '" y2="' . ($endPoint['y'] + $shape['y']) . '" style="stroke:black;stroke-width:2;' . $dashArray . '" />';
                            }
                            elseif ($shape['type'] === 'CompoundShape' or $shape['type'] === 'CompoundShape') {
                                $x = $shape['x'];
                                $y = $shape['y'];
                                $fontSize = $shape['shapes']['label']['style']['fontSize'];
                                foreach ($shape['shapes']['label']['value'] as $item) {
                                    $text = $item['text'];
                                // echo $text . "\n";
                                    
                                }

                                // Check if the text is not empty
                                if (!empty($text)) {
                                    $svg .='<text x="' . $x . '" y="' . $y . '" fill="rgb(51, 51, 51)" stroke="rgb(51, 51, 51)" stroke-width="0" stroke-dasharray="0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" dominant-baseline="text-before-edge" vector-effect="non-scaling-stroke" font-size="' . $fontSize . '" transform-origin="' . $x . ' ' . $y . '" style="white-space: nowrap;"><tspan>' . $text . '</tspan></text>';
                                }
                            }
                        }

                        // End the SVG
                        $svg .= '</svg>';

                        // Output the SVG
                        header('Content-type: image/svg+xml');
                        $elements[] = $svg;
                    }
                    //DRAWING
                    if($block['type'] === 'DRAWING'){
                        // Initialize $stepsHtml
                         $stepsHtml = '';

                         // Start the SVG element with the viewBox
                         $viewBox = $block['block']['settings']['viewBox'];
                         $stepsHtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="300px" height="400px" viewBox="' . $viewBox['x'] . ' ' . $viewBox['y'] . ' ' . $viewBox['w'] . ' ' . $viewBox['h'] . '">';

                         // Define the "arrowhead" marker
                         $stepsHtml .= '
                             <defs>
                                 <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="0" refY="3.5" orient="auto" markerUnits="strokeWidth">
                                     <path d="M0,0 L0,7 L10,3.5 z" fill="#000" />
                                 </marker>
                             </defs>
                         ';
                         // Check if the function processShape already exists
                         if (!function_exists('processShape')) {
                             function processShape($shape, &$stepsHtml) {
                                 switch ($shape['type']) {
                                    case 'Line':
                                        [$startPoint, $endPoint] = $shape['points'];
                                        $dashArray = isset($shape['style']['strokeDasharray']) ? 'stroke-dasharray:' . $shape['style']['strokeDasharray'] . ';' : '';
                                        $stepsHtml .= '<line x1="' . ($startPoint['x'] + $shape['x']) . '" y1="' . ($startPoint['y'] + $shape['y']) . '" x2="' . ($endPoint['x'] + $shape['x']) . '" y2="' . ($endPoint['y'] + $shape['y']) . '" style="stroke:black;stroke-width:2;' . $dashArray . '" />';
                                        break;

                                    case 'Text':
                                        $stepsHtml .= '<text x="' . $shape['x'] . '" y="' . $shape['y'] . '" font-size="' . $shape['style']['fontSize'] . '">' . $shape['value'][0]['text'] . '</text>';
                                        break;
                                    case 'Math':
                                        $stepsHtml .= '<text x="' . $shape['x'] . '" y="' . $shape['y'] . '" font-size="' . $shape['style']['fontSize'] . '">' . $shape['value'][0]['text'] . '</text>';
                                        break;    

                                    case 'Arrow':
                                        [$arrowStart, $arrowEnd] = $shape['points'];
                                        $stepsHtml .= '<line x1="' . ($arrowStart['x'] + $shape['x']) . '" y1="' . ($arrowStart['y'] + $shape['y']) . '" x2="' . ($arrowEnd['x'] + $shape['x']) . '" y2="' . ($arrowEnd['y'] + $shape['y']) . '" style="stroke:black;stroke-width:2" marker-end="url(#arrowhead)" />';
                                        break;
                                    case 'CartesianChartTicks':
                                        $stepsHtml .= '<rect x="' . $shape['x'] . '" y="' . $shape['y'] . '" width="' . $shape['w'] . '" height="' . $shape['h'] . '" style="fill:none;stroke:black;stroke-width:' . $shape['style']['strokeWidth'] . '" />';  
                                    case 'CurvedLine':
                                        [$start, $control1, $control2, $end] = $shape->points;
                                        $stepsHtml .= '<path d="M ' . ($shape['x'] + $shape['x']) . ',' . ($shape['y'] + $shape['y']) . ' C ' . ($control1->x + $shape['x']) . ',' . ($control1->y + $shape['y']) . ' ' . ($control2->x + $shape['x']) . ',' . ($control2->y + $shape['y']) . ' ' . ($end->x + $shape['x']) . ',' . ($end->y + $shape['y']) . '" fill="none" stroke="black" stroke-width="2"/>';
                                        break;
                                
                                    case 'Rect':
                                        $stepsHtml .= '<rect x="' . $shape['x']. '" y="' . $shape['y'] . '" width="' . $shape['w'] . '" height="' . $shape['h'] . '" style="fill:none;stroke:black;stroke-width:2" />';
                                        break;
                                
                                    case 'Ellipse':
                                        $rx = $shape->w / 2;
                                        $ry = $shape->h / 2;
                                        $cx = $shape->x + $rx;
                                        $cy = $shape->y + $ry;
                                        $stepsHtml .= '<ellipse cx="' . $cx . '" cy="' . $cy . '" rx="' . $rx . '" ry="' . $ry . '" style="fill:none;stroke:black;stroke-width:2" />';
                                        break;
                                
                                    case 'Polygon':
                                        $pointsStr = implode(' ', array_map(function ($p) use ($shape) {
                                            return ($p->x + $shape->x) . ',' . ($p->y + $shape->y);
                                        }, $shape->points));
                                        $stepsHtml .= '<polygon points="' . $pointsStr . '" style="fill:none;stroke:black;stroke-width:2" />';
                                        break;
                                
                                    case 'Circle':
                                        $radius = $shape->w / 2; // Assuming width and height are equal
                                        $centerX = $shape->x + $radius;
                                        $centerY = $shape->y + $radius;
                                        $stepsHtml .= '<circle cx="' . $centerX . '" cy="' . $centerY . '" r="' . $radius . '" style="fill:none;stroke:black;stroke-width:2" />';
                                        break;
                                    case 'Square':
                                        $stepsHtml .= '<rect x="' . $shape->x . '" y="' . $shape->y . '" width="' . $shape->w . '" height="' . $shape->h . '" style="fill:none;stroke:black;stroke-width:2" />';
                                        break;
                                    case 'Angle':
                                        $pointsHtml = '';
                                        foreach ($shape->points as $point) {
                                          $pointsHtml .= ($point['x'] + $shape['x']) . ',' . ($point['x'] + $shape['y']) . ' ';
                                        }
                                        $stepsHtml .= '<polygon points="' . rtrim($pointsHtml) . '" style="fill:none;stroke:black;stroke-width:2" />';
                                        break;
        
                                    case 'CompoundShape':
                                        if (is_array($shape->shapes)) {
                                            foreach ($shape->shapes as $innerShape) {
                                                processShape($innerShape,$stepsHtml); 
                                            }
                                        }
                                        break;
                                                                                    
                                    // Handle other shape types as needed...
                                 }
                                 return $stepsHtml;
                             }
                         }

                         foreach ($block['block']['shapes'] as $shape) {
                             processShape($shape, $stepsHtml);
                         }

                         $stepsHtml .= '</svg>';

                         // Output the SVG
                         $elements[] = $stepsHtml;
                    }
                    
                    //TABLE 2
                    if ($block['type'] === 'TABLE') {
                        if (!isset($block['block']) || !isset($block['block']['cells'])) {
                            //error_log("Missing expected properties for TABLE block: " . json_encode($block));
                            return;
                        }
                    
                        $cells = $block['block']['cells'];
                    
                        $numRows = $block['block']['rows'];
                        $numColumns = $block['block']['columns'];
                        $tableHtml = '<table border="1" style="width:100%; border-collapse: collapse; background-color: #FFFDD0;">';
                    
                        $extractedCells = extractCellData($cells);
                    
                        for ($r = 0; $r < $numColumns; $r++) {
                            $tableHtml .= '<tr>';
                            for ($c = 0; $c < $numRows; $c++) {
                                $cellKey = $r . '-' . $c;
                                $cellContent = isset($extractedCells[$cellKey]) ? $extractedCells[$cellKey] : '';
                                $tableHtml .= '<td style="border: 1px solid #000; padding: 8px; background-color: #ffffff; color: #333333; font-family: Arial, sans-serif; font-size: 14px;">' . $cellContent . '</td>';
                            }
                            $tableHtml .= '</tr>';
                        }
                    
                        $tableHtml .= '</table>';
                        $elements[]= $tableHtml;
                    }
                    //TABLE PRO
                    if ($block['type'] === 'TABLE') {
                        $tableHTML = generateTableHTML($block);
                        if (!empty($tableHTML)) {
                            $elements[] = $tableHTML;
                        }
                    }
                    //ACCOUNTING_TABLE
                    if($block['type'] === 'ACCOUNTING_TABLE'){
                        
                        // Begin constructing the HTML table
                        $html_table = '<table border="2" style="border-collapse: collapse; border-color: red;">';

                        // Header row
                        $html_table .= '<tr>';
                        foreach ($block['block']['entries'][0]['headerCells'] as $cell) {
                            $style = $cell['style']['css'];
                            $html_table .= '<th style="' . buildCss($style) . '">' . $cell['value'] . '</th>';
                        }
                        $html_table .= '</tr>';

                        // Body rows
                        foreach ($block['block']['entries'] as $entry) {
                            $html_table .= '<tr>';
                            foreach ($entry['bodyCells'] as $cell) {
                                $style = $cell['style']['css'];
                                $value = $cell['value'];
                                if (is_array($value)) {
                                    $value = isset($value['content'][0]['content'][0]['text']) ? $value['content'][0]['content'][0]['text'] : '';
                                }
                                $html_table .= '<td style="' . buildCss($style) . '">' . $value . '</td>';
                            }
                            $html_table .= '</tr>';
                        }

                        $html_table .= '</table>';
                        // Output the HTML table
                        $elements[]=$html_table;
                    }
                    //IMAGE PATH
                    if ($block['type'] === 'IMAGE_UPLOAD') {
                        if (isset($block['block']['imagePath']) && !empty($block['block']['imagePath'])) {
                            $elements[] = '<img src="'.$block['block']['imagePath'].'" alt="imagePath">';
                        }
                    }
                }
                foreach($step['explanation']['editorContentState']['blocks'] as $exlanationtext){
                    if (isset($exlanationtext['text']) && !empty($exlanationtext['text'])) {
                        $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><p>' . $exlanationtext['text'] . '</p></fieldset>';
                    }
                }
            }
        }
        return $elements;
    }
    
    //final answer
    function finalAnswer($data) {
        $elements = [];
        if (isset($data['finalAnswer']['blocks'])) {
            foreach ($data['finalAnswer']['blocks'] as $block) {
                    //entity map
                    if (isset($block['block']['editorContentState']['blocks']) || isset($block['block']['editorContentState']['entityMap'])) {
                        foreach ($block['block']['editorContentState']['blocks'] as $textBlock) {
                            if (isset($textBlock['text']) && !empty($textBlock['text'])) {
                                $text = $textBlock['text'];
                                $entityKey = $textBlock['entityRanges'][0]['key']; 
                                $entity = $block['block']['editorContentState']['entityMap'][$entityKey];
                                if ($entity) {
                                    $entityType = $entity['type'];
                                    $entityData = $entity['data'];
                                    if ($entityType === 'INLINE-EQUATION' ||$entity['type'] === 'CHEM-INLINE-EQUATION'|| isset($entityData['text'])) {
                                        $equationText = $entityData['text']; 
                                        $text .= '`'.$equationText.'`';
                                    }
                                }
                                $elements[] = '<p>' . $text . '</p>';
                            }
                        }
                    }
                    //TEXT
                    if (($block['type'] === 'TEXT')){
                        if($block['block']['editorContentState']['type']=== 'doc'){
                            foreach($block['block']['editorContentState']['content'] as $content){
                                $textAlign=$content['attrs']['textAlign'];
                                //paragraph
                                if($content['type'] ==='paragraph'){
                                    $paratext='';
                                    foreach($content['content'] as $content2){
                                        if($content2['type']==='text'){
                                            if(isset($content2['text']) && !empty($content2['text'])){
                                                $paratext.= $content2['text'];
                                            }    
                                        }
                                        //inlineMath
                                        if($content2['type'] ==='inlineMath'){
                                            foreach($content2['content'] as $content3){
                                                if(isset($content3['text']) && !empty($content3['text'])){
                                                    $paratext.= '`'.$content3['text'].'`';
                                                }
                                            }
                                        }
                                    }
                                    $elements[]= '<p>'.$paratext.'</p>';
                                }
                                //heading
                                if($content['type'] === 'heading') {
                                    $textAlign = $content['attrs']['textAlign'];
                                    $level = $content['attrs']['level'];
                                    foreach($content['content']as $content2){
                                        if(isset($content2) && $content2['type']=="text"){
                                            if(isset($content2['text']) &&!empty($content2['text'])){
                                                $elements[] ='<h'.$level.' align="' . $textAlign . '">' .$content2['text'] . '</h'.$level.'>';
                                            }
                                            foreach($content2['marks'] as $mark){
                                                if ($mark['type'] === 'bold') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><strong>' .$content2['text'] . '</strong></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'underline') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><u>' .$content2['text'] . '</u></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'italic') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><i>' .$content2['text'] . '</i></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'code') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><strong>' .$content2['text'] . '</strong></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'strikethrough') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><s>' .$content2['text'] . '</s></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'superscript') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><sup>' .$content2['text'] . '</sup></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'subscript') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><sub>' .$content2['text'] . '</sub></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'highlight') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><mark>' .$content2['text'] . '</mark></h'.$level.'>';
                                                }
                                                if ($mark['type'] === 'link') {
                                                    $elements[] ='<h'.$level.' align="' . $textAlign . '"><a href="' .$content2['text'] . '>' .$content2['text'] . '</a></h'.$level.'>'; 
                                                }
                                            }
                                        }
                                    }
                                }
                                //bulletlist
                                if ($content['type'] === 'bulletList') {
                                    foreach ($content['content'] as $item) {
                                        if ($item['type'] === 'listItem') {
                                            foreach ($item['content'] as $paragraph) {
                                                if(isset($paragraph['text']) && !empty($paragraph['text'])) {
                                                    $elements[] = '<p>' . $paragraph['text'] . '</p>';
                                                }
                                                if ($paragraph['type'] === 'paragraph') {
                                                    $displayText = '';
                                                    foreach ($paragraph['content'] as $textItem) {
                                                        if ($textItem['type'] === 'text') {
                                                            $displayText .= $textItem['text'];
                                                        } elseif ($textItem['type'] === 'inlineMath') {
                                                            foreach ($textItem['content'] as $mathText) {
                                                                $displayText .= '`'.$mathText['text'].'`';
                                                            }
                                                        }
                                                    }
                                                    $elements[] = '<p>'.$displayText .'</p>';
                                                }
                                            }
                                        }
                                    }
                                }
                                

                               
                                //orderlsit
                                if ($content['type'] === 'orderedList'){
                                    foreach($content['content']as $content2){
                                        if ($content2['type'] ==='listItem'){
                                            foreach($content2['content'] as $content3){
                                                if ($content3['type'] === 'paragraph') {
                                                    $textAlign = $content3['attrs']['textAlign'];
                                                    foreach($content3['content'] as $content4) {
                                                        if ($content4['type'] === 'text') {
                                                            if(isset($content4['text']) && !empty($content4['text'])){
                                                                $elements[] ='<p>' .$content4['text'] . '</p>';
                                                            }  
                                                        }
                                                        if (isset($content4['type']) && $content4['type'] === 'inlineMath') {
                                                            $mathtype = $content4['attrs']['mathType'];
                                                            if($mathtype=== 'mhchem'){
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $elements[] ='<p>`' . $content5['text'] . '`</p>';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            else{
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $elements[] ='<p>`'.$content5['text'].'`</p>';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                            }
                        }  
                    }
                    //TWO_LINE_MIT_BLOCK
                    if (isset($block['block']['subBlock']) && !empty($block['block']['subBlock']) && $block['block']['subBlock'] === 'TWO_LINE_MIT_BLOCK') {
                        if ($block['block']['title']['type'] === 'doc' ||$block['block']['result']['type'] === 'doc'||$block['block']['expression']['type'] === 'doc') {
                            foreach ($block['block']['title']['content'] as $content) {
                                if ($content['type'] === 'paragraph') {
                                    foreach ($content['content'] as $element) {
                                        if (isset($element['text']) && !empty($element['text'])) {
                                            $elements[] = '<p>' . $element['text'] . '</p>';
                                        }
                                    }
                                }
                            }
                            foreach ($block['block']['expression']['content'] as $content) {
                                if ($content['type'] === 'paragraph') {
                                    foreach ($content['content'] as $element) {
                                        if (isset($element['text']) && !empty($element['text'])) {
                                            $elements[] = '<p>`' . $element['text'] . '`</p>';
                                        }
                                    }
                                }
                            }
                            foreach ($block['block']['result']['content'] as $content) {
                                if ($content['type'] === 'paragraph') {
                                    foreach ($content['content'] as $element) {
                                        if (isset($element['text']) && !empty($element['text'])) {
                                            $elements[] = '<p>`' . $element['text'] . '`</p>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //THREE_LINE_MIT_BLOCK
                    if(isset($block['block']['subBlock']) && !empty($block['block']['subBlock']) && $block['block']['subBlock'] === 'THREE_LINE_MIT_BLOCK'){
                        foreach ($block['block']['title']['editorContentState']['blocks'] as $element) {
                            if (isset($element['text']) && !empty($element['text'])) {
                                        $elements[] = '<p>' . $element['text'] . '</p>';
                                    }
                                }
                        foreach ($block['block']['expression']['editorContentState']['blocks'] as $element) {
                            if (isset($element['text']) && !empty($element['text'])) {
                                        $elements[] = '<p>' . $element['text'] . '</p>';
                                    }
                                }
                        foreach ($block['block']['result']['editorContentState']['blocks'] as $element) {
                            if (isset($element['text']) && !empty($element['text'])) {
                                        $elements[] = '<p>' . $element['text'] . '</p>';
                                    }
                                }
                        foreach($block['block']['title']['content'] as $content){
                            if($content['type'] === 'paragraph'){
                                foreach ($content['content'] as $content2){
                                    if(isset($content2['text']) && !empty($content2['text'])){
                                        $elements[] = '<p>' . $content2['text'] . '</p>';
                                    }
                                }
                            }
                        }
                        foreach($block['block']['expression']['content'] as $content){
                            if($content['type'] === 'paragraph'){
                                foreach ($content['content'] as $content2){
                                    if(isset($content2['text']) && !empty($content2['text'])){
                                        $elements[] = '<p>' . $content2['text'] . '</p>';
                                    }
                                }
                            }
                        }          
                        foreach($block['block']['result']['content'] as $content){
                            if($content['type'] === 'paragraph'){
                                foreach ($content['content'] as $content2){
                                    if(isset($content2['text']) && !empty($content2['text'])){
                                        $elements[] = '<p>' . $content2['text'] . '</p>';
                                    }
                                }
                            }
                        }        
                    }
                    //EQUATION_RENDERER
                    if ($block['type']==='EQUATION_RENDERER'|| isset($block['block']['lines'])) {
                        foreach ($block['block']['lines'] as $line) {
                            $elements[] = '<p>`'.$line['left'].$line['operator'].$line['right'].'`</p>';
                            //echo "Left: " . $line['left'] . "\n"; echo "Right: " . $line['right'] . "\n"; echo "Operator: " . $line['operator'] . "\n\n";
                        }
                    }
                    //CODE_SNIPPET
                    if($block['type']==='CODE_SNIPPET'){
                        foreach($block['block'] as $contentcode){
                            if($contentcode['type'] === 'doc'){
                                foreach($contentcode['content'] as $contentcode2){
                                    if($contentcode2['type'] === 'codeBlock'){
                                        foreach($contentcode2['content'] as $contentcode3){
                                            if(isset($contentcode3['text']) && !empty($contentcode3['text'])){
                                                $elements[] = '<div class="code_line"><pre><code>'.$contentcode3['text'].'</code></pre></div>';
                                            }
                                        }
                                    }
                                }
                            }
                        }  
                    }
                    //codeData
                    if(isset($block['block']['codeData']) && !empty($block['block']['codeData'])){
                        $escapedCodeData = htmlspecialchars($block['block']['codeData']);
                        $elements[] = '<div class="code_line"><pre><code class="language-html line-numbers" data-prismjs-copy="Copy the HTML snippet!">' . $escapedCodeData . '</code></pre></div>';
                    }
                    //codeData2
                    if(isset($block['block']['codeData'])){
                        $elements[] = '<div class="code_line"><pre><code>'.$block['block']['codeData'].'</code></pre></div>';
                    }
                    //EXPLANATION
                    if($block['type']==='EXPLANATION'){
                        if($block['block']['editorContentState']['type']=== 'doc'){
                            foreach($block['block']['editorContentState']['content'] as $content){
                                $textAlign=$content['attrs']['textAlign'];
                                //paragraph
                                if($content['type'] ==='paragraph'){
                                    $decodedText = '';
                                    foreach($content['content'] as $content2){
                                        if($content2['type']==='text'){
                                            if(isset($content2['text']) && !empty($content2['text'])){
                                                $decodedText.=$content2['text'];
                                            }    
                                        }
                                        //inlineMath
                                        if($content2['type'] ==='inlineMath'){
                                            $mathtype =$content2['attrs']['mathType'];
                                            foreach($content2['content'] as $content3){
                                                if(isset($content3['text']) && !empty($content3['text'])){
                                                    $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><p>`'.$content3['text'].'`</p></fieldset>';
                                                }
                                            }
                                        }
                                    }
                                    $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><p align="'.$textAlign.'">'.$decodedText.'</p></fieldset>';
                                }
                                //heading
                                if($content['type'] === 'heading') {
                                    $textAlign = $content['attrs']['textAlign'];
                                    $level = $content['attrs']['level'];
                                    foreach($content['content']as $content2){
                                        if(isset($content2['text']) &&!empty($content2['text'])){
                                            $elements[] ='<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><h'.$level.' align="' . $textAlign . '">' .$content2['text'] . '</h'.$level.'></fieldset>';
                                        }
                                    }
                                }
                                //bulletlist
                                if ($content['type'] ==='bulletList'){
                                    foreach($content['content'] as $listItem){
                                        if ($listItem['type'] ==='listItem'){
                                            foreach($listItem['content'] as $itemContent){
                                                $elements[] = '<p>`'.$itemContent['text'].'`</p>';
                                                $textAlign=$itemContent['attrs']['textAlign'];
                                                if ($itemContent['type'] ==='paragraph'){
                                                    $content4text='';
                                                    foreach($itemContent['content'] as $content4){
                                                        if(isset($content4['text']) && !empty($content4['text'])){
                                                            $content4text.= $content4['text'];
                                                        }
                                                        if($content4['type']==='inlineMath'){
                                                            $content5text='';
                                                            foreach($content4['content'] as $content5){
                                                                if(isset($content5['text']) && !empty($content5['text'])){
                                                                    $content5text.= $content5['text'];
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if(isset($content4text) && !empty($content4text)){
                                                        $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><li>'.$content4text.'</li></fieldset>';
                                                    }
                                                    if(isset($content5text) && !empty($content5text)){
                                                        $elements[] = '<p>`'.$content5text.'`<p>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                //orderlsit
                                if ($content['type'] === 'orderedList'){
                                    foreach($content['content']as $content2){
                                        if ($content2['type'] ==='listItem'){
                                            foreach($content2['content'] as $content3){
                                                if ($content3['type'] === 'paragraph') {
                                                    $textAlign = $content3['attrs']['textAlign'];
                                                    $content42text='';
                                                    foreach($content3['content'] as $content4) {
                                                        if ($content4['type'] === 'text') {
                                                            foreach($content4['marks'] as $marks){
                                                                if($marks['type'] === 'bold'){
                                                                    if(isset($content4['text']) && !empty($content4['text'])){
                                                                        $content42text.='<strong>'.$content4['text'].'</strong>';
                                                                    } 
                                                                }
                                                                if($mark['type'] === 'underline'){
                                                                    if(isset($content4['text']) && !empty($content4['text'])){
                                                                        $content42text.='<u>'.$content4['text'].'</u>';
                                                                    } 
                                                                }
                                                            }
                                                             
                                                        }
                                                        if (isset($content4['type']) && $content4['type'] === 'inlineMath') {
                                                            $mathtype = $content4['attrs']['mathType'];
                                                            if($mathtype=== 'mhchem'){
                                                                $content52text= '';
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $content52text.= $content5['text'];
                                                                        }
                                                                    }
                                                                }
                                                                $elements[] ='<p>\(\ce{' . $content52text . '}\)</p>';
                                                            }
                                                            else{
                                                                $content522text='';
                                                                foreach ($content4['content'] as $content5) {
                                                                    if (isset($content5['type']) &&  $content5['type'] === 'text') {
                                                                        if(isset($content5['text']) && !empty($content5['text'])){
                                                                            $content522text.= $content5['text'];
                                                                        }
                                                                    }
                                                                }
                                                                if(isset($content522text) && !empty($content522text)){
                                                                $elements[] ='<p>`'.$content522text.'`</p>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if($content3['type'] === 'bulletList'){
                                                    foreach($content3['content'] as $content4) {
                                                        if($content4['type'] === 'listItem'){
                                                            foreach($content4['content'] as $content5){
                                                                if($content5['type'] === 'paragraph'){
                                                                    foreach($content5['content'] as $content6){
                                                                        if($content6['type'] === 'text'){
                                                                            $content42text.='<p>'.$content6['text'].'</p>';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if(isset($content42text) && !empty($content42text)){
                                                $elements[] ='<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><li>' .$content42text . '</li></fieldset>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //CHEMISTRY
                    if($block['type'] === 'CHEMISTRY'){
                        $svg= '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
                        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.2" baseProfile="tiny" width="1000" height="700">';

                        // Loop through the shapes in the JSON and add them to the SVG
                        foreach ($block['block']['shapes'] as $shape) {
                            if ($shape['type'] === 'Bond') {
                                $x1 = $shape['points'][0]['x'] + $shape['x'];
                                $y1 = $shape['points'][0]['y'] + $shape['y'];
                                $x2 = $shape['points'][1]['x'] + $shape['x'];
                                $y2 = $shape['points'][1]['y'] + $shape['y'];
                                $strokeWidth = 2;
                                $strokeWidth2 = 10;
                                $strokeWidth3 = 5;

                                if ($shape['bondType'] === 'Single') {
                                    $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="black" stroke-width="' . $strokeWidth . '" />';
                                } elseif ($shape['bondType'] === 'Wedge') {
                                    // Add code for the wedge bond
                                    $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="black" stroke-width="' . $strokeWidth2 . '" stroke-dasharray="20" />';
                                } elseif ($shape['bondType'] === 'Hashed wedge') {
                                    // Add code for the hashed wedge bond
                                    $svg .= '<line x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '" stroke="black" stroke-width="' . $strokeWidth3 . '" stroke-dasharray="2,2" />';
                                }
                            } if ($shape['type'] === 'Text' or $shape['type'] === 'InlineChemShape' ) {
                                $x = $shape['x'];
                                $y = $shape['y'];
                                $fontSize = $shape['style']['fontSize'];
                                foreach ($shape['value'] as $item) {
                                    $text = $item['text'];
                                // echo $text . "\n";
                                    
                                }

                                // Check if the text is not empty
                                if (!empty($text)) {
                                    $svg .='<text x="' . $x . '" y="' . $y . '" fill="rgb(51, 51, 51)" stroke="rgb(51, 51, 51)" stroke-width="0" stroke-dasharray="0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" dominant-baseline="text-before-edge" vector-effect="non-scaling-stroke" font-size="' . $fontSize . '" transform-origin="' . $x . ' ' . $y . '" style="white-space: nowrap;"><tspan>' . $text . '</tspan></text>';
                                    }
                            }
                            if ($shape['type'] === 'Line') {
                                [$startPoint, $endPoint] = $shape['points'];
                                $dashArray = isset($shape['style']['strokeDasharray']) ? 'stroke-dasharray:' . $shape['style']['strokeDasharray'] . ';' : '';
                                $svg .= '<line x1="' . ($startPoint['x'] + $shape['x']) . '" y1="' . ($startPoint['y'] + $shape['y']) . '" x2="' . ($endPoint['x'] + $shape['x']) . '" y2="' . ($endPoint['y'] + $shape['y']) . '" style="stroke:black;stroke-width:2;' . $dashArray . '" />';
                            }
                            if ($shape['type'] === 'CompoundShape' or $shape['type'] === 'CompoundShape') {
                                $x = $shape['x'];
                                $y = $shape['y'];
                                $fontSize = $shape['shapes']['label']['style']['fontSize'];
                                foreach ($shape['shapes']['label']['value'] as $item) {
                                    $text = $item['text'];
                                // echo $text . "\n";
                                    
                                }

                                // Check if the text is not empty
                                if (!empty($text)) {
                                    $svg .='<text x="' . $x . '" y="' . $y . '" fill="rgb(51, 51, 51)" stroke="rgb(51, 51, 51)" stroke-width="0" stroke-dasharray="0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" dominant-baseline="text-before-edge" vector-effect="non-scaling-stroke" font-size="' . $fontSize . '" transform-origin="' . $x . ' ' . $y . '" style="white-space: nowrap;"><tspan>' . $text . '</tspan></text>';
                                }
                            }
                        }

                        // End the SVG
                        $svg .= '</svg>';

                        // Output the SVG
                        header('Content-type: image/svg+xml');
                        $elements[] = $svg;
                    }
                    //DRAWING
                    if($block['type'] === 'DRAWING'){
                        // Initialize $stepsHtml
                         $stepsHtml = '';

                         // Start the SVG element with the viewBox
                         $viewBox = $block['block']['settings']['viewBox'];
                         $stepsHtml .= '<svg xmlns="http://www.w3.org/2000/svg" width="300px" height="400px" viewBox="' . $viewBox['x'] . ' ' . $viewBox['y'] . ' ' . $viewBox['w'] . ' ' . $viewBox['h'] . '">';

                         // Define the "arrowhead" marker
                         $stepsHtml .= '
                             <defs>
                                 <marker id="arrowhead" markerWidth="10" markerHeight="7" refX="0" refY="3.5" orient="auto" markerUnits="strokeWidth">
                                     <path d="M0,0 L0,7 L10,3.5 z" fill="#000" />
                                 </marker>
                             </defs>
                         ';
                         // Check if the function processShape already exists
                         if (!function_exists('processShape')) {
                             function processShape($shape, &$stepsHtml) {
                                 switch ($shape['type']) {
                                     case 'Line':
                                         [$startPoint, $endPoint] = $shape['points'];
                                         $dashArray = isset($shape['style']['strokeDasharray']) ? 'stroke-dasharray:' . $shape['style']['strokeDasharray'] . ';' : '';
                                         $stepsHtml .= '<line x1="' . ($startPoint['x'] + $shape['x']) . '" y1="' . ($startPoint['y'] + $shape['y']) . '" x2="' . ($endPoint['x'] + $shape['x']) . '" y2="' . ($endPoint['y'] + $shape['y']) . '" style="stroke:black;stroke-width:2;' . $dashArray . '" />';
                                         break;

                                     case 'Text':
                                         $stepsHtml .= '<text x="' . $shape['x'] . '" y="' . $shape['y'] . '" font-size="' . $shape['style']['fontSize'] . '">' . $shape['value'][0]['text'] . '</text>';
                                         break;
                                     case 'Math':
                                         $stepsHtml .= '<text x="' . $shape['x'] . '" y="' . $shape['y'] . '" font-size="' . $shape['style']['fontSize'] . '">' . $shape['value'][0]['text'] . '</text>';
                                         break;    

                                     case 'Arrow':
                                         [$arrowStart, $arrowEnd] = $shape['points'];
                                         $stepsHtml .= '<line x1="' . ($arrowStart['x'] + $shape['x']) . '" y1="' . ($arrowStart['y'] + $shape['y']) . '" x2="' . ($arrowEnd['x'] + $shape['x']) . '" y2="' . ($arrowEnd['y'] + $shape['y']) . '" style="stroke:black;stroke-width:2" marker-end="url(#arrowhead)" />';
                                         break;
                                     case 'CartesianChartTicks':
                                         $stepsHtml .= '<rect x="' . $shape['x'] . '" y="' . $shape['y'] . '" width="' . $shape['w'] . '" height="' . $shape['h'] . '" style="fill:none;stroke:black;stroke-width:' . $shape['style']['strokeWidth'] . '" />';  
                                     // Handle other shape types as needed...
                                 }
                                 return $stepsHtml;
                             }
                         }

                         foreach ($block['block']['shapes'] as $shape) {
                             processShape($shape, $stepsHtml);
                         }

                         $stepsHtml .= '</svg>';

                         // Output the SVG
                         $elements[] = $stepsHtml;
                    }
                    
                    //TABLE 2
                    if ($block['type'] === 'TABLE') {
                        if (!isset($block['block']) || !isset($block['block']['cells'])) {
                            //error_log("Missing expected properties for TABLE block: " . json_encode($block));
                            return;
                        }
                    
                        $cells = $block['block']['cells'];
                    
                        $numRows = $block['block']['rows'];
                        $numColumns = $block['block']['columns'];
                        $tableHtml = '<table border="1" style="width:100%; border-collapse: collapse; background-color: #FFFDD0;">';
                    
                        $extractedCells = extractCellData($cells);
                    
                        for ($r = 0; $r < $numColumns; $r++) {
                            $tableHtml .= '<tr>';
                            for ($c = 0; $c < $numRows; $c++) {
                                $cellKey = $r . '-' . $c;
                                $cellContent = isset($extractedCells[$cellKey]) ? $extractedCells[$cellKey] : '';
                                $tableHtml .= '<td style="border: 1px solid #000; padding: 8px; background-color: #ffffff; color: #333333; font-family: Arial, sans-serif; font-size: 14px;">' . $cellContent . '</td>';
                            }
                            $tableHtml .= '</tr>';
                        }
                    
                        $tableHtml .= '</table>';
                        $elements[]= $tableHtml;
                    }
                    //TABLE PRO
                    if ($block['type'] === 'TABLE') {
                        $tableHTML = generateTableHTML($block);
                        if (!empty($tableHTML)) {
                            $elements[] = $tableHTML;
                        }
                    }
                    //ACCOUNTING_TABLE
                    if($block['type'] === 'ACCOUNTING_TABLE'){
                        
                        // Begin constructing the HTML table
                        $html_table = '<table border="2" style="border-collapse: collapse; border-color: red;">';

                        // Header row
                        $html_table .= '<tr>';
                        foreach ($block['block']['entries'][0]['headerCells'] as $cell) {
                            $style = $cell['style']['css'];
                            $html_table .= '<th style="' . buildCss($style) . '">' . $cell['value'] . '</th>';
                        }
                        $html_table .= '</tr>';

                        // Body rows
                        foreach ($block['block']['entries'] as $entry) {
                            $html_table .= '<tr>';
                            foreach ($entry['bodyCells'] as $cell) {
                                $style = $cell['style']['css'];
                                $value = $cell['value'];
                                if (is_array($value)) {
                                    $value = isset($value['content'][0]['content'][0]['text']) ? $value['content'][0]['content'][0]['text'] : '';
                                }
                                $html_table .= '<td style="' . buildCss($style) . '">' . $value . '</td>';
                            }
                            $html_table .= '</tr>';
                        }

                        $html_table .= '</table>';
                        // Output the HTML table
                        $elements[]=$html_table;
                    }
                    //IMAGE PATH
                    if ($block['type'] === 'IMAGE_UPLOAD') {
                        if (isset($block['block']['imagePath']) && !empty($block['block']['imagePath'])) {
                            $elements[] = '<img src="'.$block['block']['imagePath'].'" alt="imagePath">';
                        }
                    }
                }
                foreach($block['explanation']['editorContentState']['blocks'] as $exlanationtext){
                    if (isset($exlanationtext['text']) && !empty($exlanationtext['text'])) {
                        $elements[] = '<fieldset style="box-shadow: 5px 5px 10px #888888; border: 2px solid #ddd; border-radius: 8px; padding: 15px; margin: 20px;"><legend><h2><u>Explanation</u></h2></legend><p>' . $exlanationtext['text'] . '</p></fieldset>';
                    }
                }
            }
        return $elements;
    }
    
    
    //chegg qna answer
    if (preg_match("/-?q(\d+)/", $text, $matches) && preg_match("/q(\d+)$/", $text, $matches) or preg_match('/-q(\d+)/', $text, $matches) ) {
        $idQ = $matches[1];//echo "Question ID: $idQ";
        $responsecurl = answerresponse($idQ);
        $response = $responsecurl[1];
        if (preg_match('/HTMLAnswers/',$response) or preg_match('/answerCount/', $response)){
            $answercurl = answerresponse($idQ);
            $answer = $answercurl[0];
            $uuid = Transformlink($text);
            $questionbodyhtml=questionBody($uuid);
            $htmlAnswers = $answer['data']['questionByLegacyId']['displayAnswers']['htmlAnswers'];
            $legacyId = $answer['data']['questionByLegacyId']['displayAnswers']['htmlAnswers'][0]['legacyId'];
            $countquestion = $answer['data']['questionByLegacyId']['displayAnswers']['htmlAnswers'][0]['answerData']['author']['answerCount'];
            foreach ($htmlAnswers as $index => $htmlAnswer) {
                $ans = $htmlAnswer['answerData']['html'];
                //echo "HTML Answer $index: $html" . PHP_EOL;
                if ($ans !== null) {
                    $rating = htmlanslike($legacyId); //like and dislike
                    $answerhtml.='<!DOCTYPE html><html><head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name="description" content=""> <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js" type="text/javascript"></script> <script type="text/x-mathjax-config"> MathJax.Hub.Config({ config: ["MMLorHTML.js"], jax: ["input/TeX", "input/MathML", "output/HTML-CSS", "output/NativeMML"], extensions: ["tex2jax.js", "mml2jax.js", "MathMenu.js", "MathZoom.js"], TeX: { extensions: ["AMSmath.js", "AMSsymbols.js", "noErrors.js", "noUndefined.js"] } }); </script> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"></head><body> <div class="container"> <div id="app"> <div class="container"> <div class="section"> <div class="box" style="word-break: break-all;"> <h1>Question Link</h1> <div class="url">' . $text . '</div> </div> <div class="box"> <div class="content"> <h1>Question</h1> <div class="questionnx">' . $questionbodyhtml[0] . '<br><p>' . $questionbodyhtml[1] . '</p></div><br> <div class="rate"> <h3>Expert-verified <img src="https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png" style="width:27px;"></h3> <i class="fas fa-user-tie fa-2x"> ' . $countquestion . '</i> </div> <br> <div class="like-dislike-icons"> <i class="fas fa-thumbs-up fa-2x"> ' . $rating[0] . '</i>&nbsp&nbsp <i class="fas fa-thumbs-down fa-2x"> ' . $rating[1] . '</i> </div> </div> </div> <div class="box"> <div class="content"> <h1>Answer</h1> <div class="answernx">' . $ans . '</div> </div> </div> </div> </div> </div> </div></body></html>';
                    echo $answerhtml;
                }
                else{
                    echo "This question hasn't been solved yet";
                }
            }
        }
        elseif (preg_match('/SBSAnswer/',$response)){
            $datacurl = answerresponse($idQ);
            $data = $datacurl[0];
            $steps= $data['data']['questionByLegacyId']['displayAnswers']['body']['steps'];
            $stepCount =count($steps);
            $correctAnswerMdText=$data['data']['questionByLegacyId']['displayAnswers']['body']['correctAnswerMdText'];
            $legacyId=$data['data']['questionByLegacyId']['displayAnswers']['id'];
            $uuid = Transformlink($text);
            $questionbodyhtml=questionBody($uuid);
            $rating = htmlanslike($legacyId); //like and dislike
            $stepMdText='';
            $explanationMdText='';
            foreach($steps as $index => $step){
                $stepName = '<span style="color: red;">Step ' . ($index + 1) . '/' .$stepCount . '</span>';
                $elements = '<h2>' . $stepName . '</h2>';
                $stepMdText='<p>'.$step['stepMdText'].'</p>';
                $explanationMdText='<fieldset style="box-shadow: 5px 5px 10px #888888; width: 100%; height: 70px;"><legend><h2><u>Explanation</u></h2></legend>'.$step['explanationMdText'].'</p></fieldset>';
                $answer.=$elements.$stepMdText.$explanationMdText;
                $script='<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" integrity="sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV" crossorigin="anonymous">
                <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js" integrity="sha384-XjKyOOlGwcjNTAIQHIpgOno0Hl1YQqzUOEleOLALmuqehneUG+vnGctmUb0ZY0l8" crossorigin="anonymous"></script>
                <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        renderMathInElement(document.body, {
                        // customised options
                        // • auto-render specific keys, e.g.:
                        delimiters: [
                            {left: "$$", right: "$$", display: true},
                            {left: "$", right: "$", display: false},
                            {left: "\\(", right: "\\)", display: false},
                            {left: "\\[", right: "\\]", display: true}
                        ],
                        // • rendering keys, e.g.:
                        throwOnError : false
                        });
                    });
                </script>';
                $answerhtml='<!DOCTYPE html><html><head> <meta charset=utf-8> <meta name=viewport content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name=description content> <meta name=viewport content="width=device-width, initial-scale=1"> <link rel="shortcut icon" type=image/x-icon href=assets/img/favicon.ico> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css><link rel=stylesheet href=https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css integrity=sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV crossorigin=anonymous> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css>'.$script.'</head><body> <div class=container> <div id=app> <div class=container> <div class=section> <div class=box style=word-break:break-all> <h1>Question Link</h1> <div class=url>' . $text . '</div> </div> <div class=box> <div class=content> <h1>Question</h1> <div class=questionnx>' . $questionbodyhtml[0] . '</div><br> <div class=rate> <h3>Expert-verified <img src=https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png style=width:27px></h3></div> <br> <div class=like-dislike-icons> <i class="fas fa-thumbs-up fa-2x"> '.$rating[0].'</i>   <i class="fas fa-thumbs-down fa-2x"> '.$rating[1].'</i> </div> </div> </div> <div class=box> <div class=content> <h1>Answer</h1> <div class=answernx> ' . $answer . ' <h2 style=color:red>Final Answer</h2>'.$correctAnswerMdText.'</div></div> </div> </div> </div> </div> </div></body></html>';       
            }
            if($correctAnswerMdText !== null){
                echo $answerhtml;
            }
            else{
                $answer= '<p>'.$data['data']['questionByLegacyId']['displayAnswers']['stepPreviewMdText'].'</p>';
                $answerhtml='<!DOCTYPE html><html><head> <meta charset=utf-8> <meta name=viewport content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name=description content> <meta name=viewport content="width=device-width, initial-scale=1"> <link rel="shortcut icon" type=image/x-icon href=assets/img/favicon.ico> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css><link rel=stylesheet href=https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css integrity=sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV crossorigin=anonymous> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css>'.$script.'</head><body> <div class=container> <div id=app> <div class=container> <div class=section> <div class=box style=word-break:break-all> <h1>Question Link</h1> <div class=url>' . $text . '</div> </div> <div class=box> <div class=content> <h1>Question</h1> <div class=questionnx>' . $questionbodyhtml[0] . '</div><br> <div class=rate> <h3>Expert-verified <img src=https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png style=width:27px></h3></div> <br> <div class=like-dislike-icons> <i class="fas fa-thumbs-up fa-2x"> '.$like.'</i>   <i class="fas fa-thumbs-down fa-2x"> '.$dislike.'</i> </div> </div> </div> <div class=box> <div class=content> <h1>Answer</h1> <div class=answernx> ' . $answer . ' <h2 style=color:red>Final Answer</h2>'.$correctAnswerMdText.'</div></div> </div> </div> </div> </div> </div></body></html>';
                echo $answerhtml;    
            }
            
        }
        elseif (preg_match('/TextAnswer/',$response)){
            $datacurl = answerresponse($idQ);
            $data = $datacurl[0];
            $bodyMdText= $data['data']['questionByLegacyId']['displayAnswers']['bodyMdText'];
            $legacyId=$data['data']['questionByLegacyId']['displayAnswers']['id'];
            $uuid = Transformlink($text);
            $questionbodyhtml=questionBody($uuid);
            $rating = htmlanslike($legacyId); //like and dislike
            $script='<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" integrity="sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV" crossorigin="anonymous">
                <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js" integrity="sha384-XjKyOOlGwcjNTAIQHIpgOno0Hl1YQqzUOEleOLALmuqehneUG+vnGctmUb0ZY0l8" crossorigin="anonymous"></script>
                <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        renderMathInElement(document.body, {
                        // customised options
                        // • auto-render specific keys, e.g.:
                        delimiters: [
                            {left: "$$", right: "$$", display: true},
                            {left: "$", right: "$", display: false},
                            {left: "\\(", right: "\\)", display: false},
                            {left: "\\[", right: "\\]", display: true}
                        ],
                        // • rendering keys, e.g.:
                        throwOnError : false
                        });
                    });
                </script>';
            if ($bodyMdText == null){
                $bodyMdText = $data['data']['questionByLegacyId']['displayAnswers']['previewMdText'];
                $answerhtml='<!DOCTYPE html><html><head> <meta charset=utf-8> <meta name=viewport content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name=description content> <meta name=viewport content="width=device-width, initial-scale=1"> <link rel="shortcut icon" type=image/x-icon href=assets/img/favicon.ico> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css><link rel=stylesheet href=https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css integrity=sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV crossorigin=anonymous> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css>'.$script.'</head><body> <div class=container> <div id=app> <div class=container> <div class=section> <div class=box style=word-break:break-all> <h1>Question Link</h1> <div class=url>' . $text . '</div> </div> <div class=box> <div class=content> <h1>Question</h1> <div class=questionnx>' . $questionbodyhtml[0] . '</div><br> <div class=rate> <h3>Expert-verified <img src=https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png style=width:27px></h3></div> <br> <div class=like-dislike-icons> <i class="fas fa-thumbs-up fa-2x"> '.$rating[0].'</i>   <i class="fas fa-thumbs-down fa-2x"> '.$rating[1].'</i> </div> </div> </div> <div class=box> <div class=content> <h1>Answer</h1> <div class=answernx> ' . $bodyMdText . ' </div></div> </div> </div> </div> </div> </div></body></html>';
                echo $answerhtml; 
            }
            else{
                $bodyMdText = $data['data']['questionByLegacyId']['displayAnswers']['bodyMdText'];
                $answerhtml='<!DOCTYPE html><html><head> <meta charset=utf-8> <meta name=viewport content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name=description content> <meta name=viewport content="width=device-width, initial-scale=1"> <link rel="shortcut icon" type=image/x-icon href=assets/img/favicon.ico> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css><link rel=stylesheet href=https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css integrity=sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV crossorigin=anonymous> <link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css>'.$script.'</head><body> <div class=container> <div id=app> <div class=container> <div class=section> <div class=box style=word-break:break-all> <h1>Question Link</h1> <div class=url>' . $text . '</div> </div> <div class=box> <div class=content> <h1>Question</h1> <div class=questionnx>' . $questionbodyhtml[0] . '</div><br> <div class=rate> <h3>Expert-verified <img src=https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png style=width:27px></h3></div> <br> <div class=like-dislike-icons> <i class="fas fa-thumbs-up fa-2x"> '.$rating[0].'</i>   <i class="fas fa-thumbs-down fa-2x"> '.$rating[1].'</i> </div> </div> </div> <div class=box> <div class=content> <h1>Answer</h1> <div class=answernx> ' . $bodyMdText. '</div></div> </div> </div> </div> </div> </div></body></html>';
                echo $answerhtml;
            }
        }
        elseif (preg_match('/generalGuidance/', $response) and preg_match('/ecAnswers/', $response) and preg_match('/finalAnswerHtml/', $response) ){
            $ECAnswerscurl = answerresponse($idQ);
            $ECAnswers = $ECAnswerscurl[0];
            $answerPreview=$ECAnswers['data']['questionByLegacyId']['displayAnswers']['ecAnswers'][0]['answerPreview'];
            $legacyId=$ECAnswers['data']['questionByLegacyId']['displayAnswers']['ecAnswers'][0]['legacyId'];
            $uuid = Transformlink($text);
            $questionbodyhtml=questionBody($uuid);
            $generalGuidancehtml='<h2  style="color: red;">General guidance</h2>';
            $Stepbystep='<h2  style="color: red;">Step-by-step</h2>';
            $generalGuidance = ''; // Initialize as an empty string
            $textHtml = ''; // Initialize as an empty string
            $answerHtml = ''; // Initialize as an empty string
            $explanationHtml = ''; // Initialize as an empty string
            $hintsHtml = ''; // Initialize as an empty string
            $commonMistakesHtml2 = ''; // Initialize as an empty string
            foreach ($ECAnswers['data']['questionByLegacyId']['displayAnswers']['ecAnswers'][0]['answerData']['generalGuidance'] as $item) {
                $generalGuidance.= '<br><div class="general-guidance"><br><h3>'.$item['title'].'</h3><br>'.$item['html'].'<br></div>';
                //echo $generalGuidance;
            }
            foreach ($ECAnswers['data']['questionByLegacyId']['displayAnswers']['ecAnswers'][0]['answerData']['steps'] as $item) {
                $stepNumber=$item['stepNumber'];
                $textHtml.= '<br><h3>Step '.$stepNumber.'</h3><br><div class="text-html">'.$item['textHtml'].'</div>';
                $answerHtml.= '<br><div class="answer-html">'.$item['answerHtml'].'</div>';
                $explanationHtml.= '<br><div class="explanation-html"><h5>Explanation</h5>'.$item['explanationHtml'].'</div>';
                foreach($item['hintsHtml'] as $itemhint){
                    if ($itemhint!== null){
                        $hintsHtml.= '<br><div class="hints-html"><h5>Hint for Next Steps</h5>'.$itemhint.'</div>';
                        //echo $hintsHtml;
                    }
                    else{
                        $hintsHtml='';
                    }
                    
                }
                foreach($item['commonMistakesHtml'] as $commonMistakesHtml){
                    if ($commonMistakesHtml!== null){
                        $commonMistakesHtml2.= '<br><div class="hints-html"><h5>Common Mistakes</h5>'.$commonMistakesHtml.'</div>';
                    }
                    else{
                        $commonMistakesHtml2='';
                    }
                    
                }
            }
            $finalAnswerHtml = ''; // Initialize as an empty string
            foreach ($ECAnswers['data']['questionByLegacyId']['displayAnswers']['ecAnswers'][0]['answerData']['finalAnswerHtml'] as $item) {
                $finalAnswerHtml.= '<br><div class="final-answer"><br>'.$item.'</div>';
                //echo $generalGuidance;
            }
            $answerall.=$generalGuidancehtml.$generalGuidance.$textHtml.$answerHtml.$explanationHtml.$hintsHtml.$commonMistakesHtml2;
            $rating = htmlanslike($legacyId); //like and dislike
            $answerhtml='<!DOCTYPE html><html><head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name="description" content=""> <meta name="viewport" content="width=device-width, initial-scale=1"> <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> <style> .logo { color: #E36F23; font-size: 24px; font-weight: bold; } .card-custom { border-radius: 12px; overflow: hidden; } .header-bg { background-color: #E36F23; } .btn-custom { background-color: #2C3E50; color: white; } </style><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" integrity="sha384-n8MVd4RsNIU0tAv4ct0nTaAbDJwPJzDEaqSD1odI+WdtXRGWt2kTvGFasHpSy3SV" crossorigin="anonymous">
            <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js" integrity="sha384-XjKyOOlGwcjNTAIQHIpgOno0Hl1YQqzUOEleOLALmuqehneUG+vnGctmUb0ZY0l8" crossorigin="anonymous"></script>
            <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"
                onload="renderMathInElement(document.body);"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"></head><body> <div class="container"> <div id="app"> <div class="container"> <div class="section"> <div class="box" style="word-break: break-all;"> <h1>Question Link</h1> <div class="url">' . $text . '</div> </div> <div class="box"> <div class="content"> <h1>Question</h1> <div class="questionnx">'.$questionbodyhtml[0].'</div><br> <div class="rate"> <h3>Expert-verified <img src="https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png" style="width:27px;"></h3></div> <br> <div class="like-dislike-icons"> <i class="fas fa-thumbs-up fa-2x"> '.$rating[0].'</i>&nbsp&nbsp <i class="fas fa-thumbs-down fa-2x"> '.$rating[1].'</i> </div> </div> </div> <div class="box"> <div class="content"> <h1>Answer</h1> <div class="answernx">'.$answerall.'</p><h2  style="color: red;">Final Answer</h2>'.$finalAnswerHtml.'</div></div> </div> </div> </div> </div> </div></body></html>';
            echo $answerhtml;
        
        }
        elseif (preg_match('/sqnaAnswers/', $response) and preg_match('/stepByStep/', $response) and preg_match('/finalAnswer/', $response) ){
            $answercurl = answerresponse($idQ);
            $answer3 = $answercurl[0];
            $SqnaAnswers3 = $answer3['data']['questionByLegacyId']['displayAnswers']['sqnaAnswers']['answerData'][0]['body']['text'];
            $legacyId = $answer3['data']['questionByLegacyId']['displayAnswers']['sqnaAnswers']['answerData'][0]['legacyId'];
            $rating = htmlanslike($legacyId); //like and dislike
            //*********Chegg sqnaanswers**********//
            $SqnaAnswerjson=<<<JSON
            $SqnaAnswers3
            JSON;
            //*********Chegg sqnaanswers**********//
            $data = json_decode($SqnaAnswerjson , true);
            //*********data SqnaAnswerjson**********//
            if(isset($SqnaAnswerjson) && preg_match('/stepByStep/',$SqnaAnswerjson) or preg_match('/finalAnswer/',$SqnaAnswerjson)){
            $extractedData = extractTextAndImages($data);
            foreach ($extractedData as $element) {
                $ans.=$element;
            }
            $finalAnswerData = finalAnswer($data);
            foreach ($finalAnswerData as $finalAnswerelement) {
                $finalAnswerans.=$finalAnswerelement;
            }
            $uuid = Transformlink($text);
            $questionbodyhtml=questionBody($uuid);
            if ($ans !== null){
                $script='<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex/dist/katex.min.css">
                <script defer src="https://cdn.jsdelivr.net/npm/katex/dist/katex.min.js"></script>
                <script defer src="https://cdn.jsdelivr.net/npm/katex/dist/contrib/auto-render.min.js"
                        onload="renderMathInElement(document.body);"></script>
                <script defer src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML"></script>
                <script defer src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/extensions/TeX/mhchem.js"></script>
                <script type="text/x-mathjax-config">
                MathJax.Hub.Config({
                    extensions: ["tex2jax.js"],
                    jax: ["input/TeX", "output/HTML-CSS"],
                    tex2jax: {
                    inlineMath: [["$", "$"], ["\\(", "\\)"]],
                    displayMath: [["$$", "$$"], ["\\[", "\\]"]],
                    processEscapes: true,
                    skipTags: ["script", "noscript", "style", "textarea", "pre", "code"]
                    },
                    TeX: {
                    extensions: ["mhchem.js"]
                    }
                });
                </script>';
                $answerhtml.='<!DOCTYPE html><html><head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name="description" content=""> <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=AM_CHTML"></script> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"></head><body> <div class="container"> <div id="app"> <div class="container"> <div class="section"> <div class="box" style="word-break: break-all;"> <h1>Question Link</h1> <div class="url">' . $text . '</div> </div> <div class="box"> <div class="content"> <h1>Question</h1> <div class="questionnx"> ' . $questionbodyhtml[0] . ' <br><br> <h4>Transcribing text</h4> <p>'.$questionbodyhtml[1].'</p> </div> <br> <div class="rate"> <h3>Expert-verified <img src="https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png" style="width:27px;"></h3> </div> <br> <div class="like-dislike-icons"> <i class="fas fa-thumbs-up fa-2x"> '.$rating[0].'</i>&nbsp&nbsp <i class="fas fa-thumbs-down fa-2x">'.$rating[1].'</i> </div> </div> </div> <div class="box"> <div class="content"> <h1>Answer</h1> <div class="answernx">' . $ans. '<div class="steps" style="width: 100%; background-color: rgb(239, 245, 254); line-height: 1; font-size: 0.875rem; font-weight: bold; padding: 0.75rem 1rem; border: 1px solid rgb(231, 231, 231); box-sizing: border-box; display: flex; justify-content: space-between; cursor: pointer;"> <h3>Final answer</h3> </div>'.$finalAnswerans.'</div> </div> </div> </div> </div> </div> </div></body></html>';
                echo $answerhtml;
                }
            else{
                echo "This question hasn't been solved yet";
                }
            }
        }
    }
    //textbook answer
    elseif(preg_match("/-exc/", $text)){
        if (preg_match("/trackid/" ,$text)){
            $pattern = "/\?trackid=[^&]+&strackid=[^&]+$/";
            $cleanLink = preg_replace($pattern, '', $text);
            $pattern = "/([^\/]+)$/";
            if (preg_match($pattern, $cleanLink, $matches)) {
                $lastPart = $matches[1];
                //echo $lastPart.'1';
            }
        }else{
            $pattern = "/([^\/]+)$/";
            if (preg_match($pattern, $text, $matches)) {
                $lastPart = $matches[1];
                //echo $lastPart.'2';
            }
        }
        $questionexc="https://www.chegg.com/homework-help/".$lastPart;
        $modifiedUrl ="https://www.chegg.com/homework-help/".$lastPart. "-exc";
            if (strpos($questionexc, "-exc") !== false) {
                $urls = str_replace("-exc", "", $questionexc);
                
            } else {
                $urls = str_replace("-exc", "", $modifiedUrl);
                
            }  
        
        $datatbs =textbookanswer($urls);
        $contentId =$datatbs['data']['tbsSolutionContent'][0]['id']; //contentid for rating
        foreach ($datatbs['data']['tbsSolutionContent'] as $content) {// Access the desired value using a for loop
            foreach ($content['stepsLink'] as $link) {
                $htmlContent = $link['html'];
                $ans.=$htmlContent;
                //echo $htmlContent . "\n";
            }
        }
        $rating = textbookrating($contentId); //like and dislike
        if ($ans !== null){
            $answerhtml.='<!DOCTYPE html><html><head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name="description" content=""> <meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js" type="text/javascript"></script> <script type="text/x-mathjax-config"> MathJax.Hub.Config({ config: ["MMLorHTML.js"], jax: ["input/TeX", "input/MathML", "output/HTML-CSS", "output/NativeMML"], extensions: ["tex2jax.js", "mml2jax.js", "MathMenu.js", "MathZoom.js"], TeX: { extensions: ["AMSmath.js", "AMSsymbols.js", "noErrors.js", "noUndefined.js"] } }); </script><link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"></head><body> <div class="container"> <div id="app"> <div class="container"> <div class="section"> <div class="box" style="word-break: break-all;"> <h1>Question Link</h1> <div class="url">' . $text . '</div> </div> <div class="box"> <div class="content"> <h1>Question</h1> <div class="questionnx">'.$allnumber[2].'</div><br> <div class="rate"> <h3>Expert-verified <img src="https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png" style="width:27px;"></h3></div> <br> <div class="like-dislike-icons"> <i class="fas fa-thumbs-up fa-2x"> '.$rating[0].'</i>&nbsp&nbsp <i class="fas fa-thumbs-down fa-2x"> '.$rating[1].'</i> </div> </div> </div> <div class="box"> <div class="content"> <h1>Answer</h1> <div class="answernx"> ' . $ans . ' </div></div> </div> </div> </div> </div> </div></body></html>';
            echo $answerhtml;
        }
        else{
            echo "This question hasn't been solved yet";
        }
        

    }
    else{
        if (preg_match("/trackid/" ,$text)){
            $pattern = "/\?trackid=[^&]+&strackid=[^&]+$/";
            $cleanLink = preg_replace($pattern, '', $text);
            $pattern = "/([^\/]+)$/";
            if (preg_match($pattern, $cleanLink, $matches)) {
                $lastPart = $matches[1];
                //echo $lastPart.'1';
            }
        }else{
            $pattern = "/([^\/]+)$/";
            if (preg_match($pattern, $text, $matches)) {
                $lastPart = $matches[1];
                //echo $lastPart.'2';
            }
        }
        $questionexc="https://www.chegg.com/homework-help/".$lastPart;
        $modifiedUrl ="https://www.chegg.com/homework-help/".$lastPart. "-exc";
            if (strpos($questionexc, "-exc") !== false) {
                $urls = str_replace("-exc", "", $questionexc);
                
            } else {
                $urls = str_replace("-exc", "", $modifiedUrl);
                
            }  
        $datatbs =textbookanswer($urls);
        $contentId =$datatbs['data']['tbsSolutionContent'][0]['id']; //contentid for rating
        // Access the desired value using a for loop
        foreach ($datatbs['data']['tbsSolutionContent'] as $content) {
            foreach ($content['stepsLink'] as $link) {
                $htmlContent = $link['html'];
                $ans.=$htmlContent;
                //echo $htmlContent . "\n";
            }
        }
        $rating = textbookrating($contentId); //like and dislike
        if ($ans !== null){
            $answerhtml.='<!DOCTYPE html><html><head> <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1"> <title>NX pro</title> <meta name="description" content=""> <meta name="viewport" content="width=device-width, initial-scale=1"><script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js" type="text/javascript"></script> <script type="text/x-mathjax-config"> MathJax.Hub.Config({ config: ["MMLorHTML.js"], jax: ["input/TeX", "input/MathML", "output/HTML-CSS", "output/NativeMML"], extensions: ["tex2jax.js", "mml2jax.js", "MathMenu.js", "MathZoom.js"], TeX: { extensions: ["AMSmath.js", "AMSsymbols.js", "noErrors.js", "noUndefined.js"] } }); </script><link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"></head><body> <div class="container"> <div id="app"> <div class="container"> <div class="section"> <div class="box" style="word-break: break-all;"> <h1>Question Link</h1> <div class="url">' . $text . '</div> </div> <div class="box"> <div class="content"> <h1>Question</h1> <div class="questionnx">'.$allnumber[2].'</div><br> <div class="rate"> <h3>Expert-verified <img src="https://png.pngtree.com/png-clipart/20230422/original/pngtree-instagram-bule-tick-insta-blue-star-vector-png-image_9074860.png" style="width:27px;"></h3></div> <br> <div class="like-dislike-icons"> <i class="fas fa-thumbs-up fa-2x"> '.$rating[0].'</i>&nbsp&nbsp <i class="fas fa-thumbs-down fa-2x"> '.$rating[1].'</i> </div> </div> </div> <div class="box"> <div class="content"> <h1>Answer</h1> <div class="answernx">' . $ans . ' </div> </div> </div> </div> </div> </div> </div></body></html>';
            echo $answerhtml;
        }
        else{
            echo "This question hasn't been solved yet";
        }
    }
}
?>