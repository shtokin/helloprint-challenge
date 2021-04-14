<?php

$brokerUrl = 'http://webserver-broker:8080/message';
$createResponse = executePost($brokerUrl, ['message' => 'Hi']);

if ($createResponse['token']) {
    try {
        $result = waitForReady($createResponse['token']);
        echo $result . PHP_EOL;
    } catch (Exception $ex) {
        echo $ex->getMessage() . PHP_EOL;
    }
}

function waitForReady(string $token)
{
    $brokerWaitUrl = 'http://webserver-broker:8080/check-message';
    $startTime = microtime(true);
    while (true) {
        usleep(50);
        $response = executePost($brokerWaitUrl, ['token' => $token]);
        $endTime = microtime(true);
        if ($endTime - $startTime > 1) {
            echo 'Time:' . ($endTime - $startTime) . PHP_EOL;
            throw new Exception('no response');
        }

        if (!empty($response['finalMessage'])) {
            echo 'Time:' . ($endTime - $startTime) . PHP_EOL;
            return $response['finalMessage'];
        }
    }
}

function executePost(string $url, array $params) :array
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        $errorMsg = curl_error($ch);
        echo $errorMsg . PHP_EOL;
    }

    curl_close($ch);
    $output = json_decode($output, true);
    return $output;
}
