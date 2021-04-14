<?php
const KAFKA_PARTITION = 0;

$conf = new RdKafka\Conf();

$conf->set('log_level', (string) LOG_DEBUG);

$consumer = new RdKafka\Consumer($conf);
$consumer->addBrokers('kafka');

$consumerTopicName = 'TopicB';
$topic = $consumer->newTopic($consumerTopicName);

$topic->consumeStart(KAFKA_PARTITION, RD_KAFKA_OFFSET_BEGINNING);

while (true) {
    $msg = $topic->consume(KAFKA_PARTITION, 1500);
    if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
        continue;
    } elseif ($msg->err) {
        echo $msg->errstr() . PHP_EOL;
        break;
    } else {
        //echo $msg->payload . PHP_EOL;
        handlePayload($msg->payload);
    }
}

function handlePayload(string $payload)
{
    $data = json_decode($payload, true);
    if (empty($data['token']) || empty($data['message'])) {
        $errorMessage = 'Invalid data: ' . print_r($data, true);
        echo $errorMessage . PHP_EOL;
    }

    $messageText = $data['message'] . ' Bye';
    sendToBroker($data['token'], $messageText);
}

function sendToBroker(string $token, string $messageText)
{
    $params = ['token' => $token, 'message' => $messageText];
    $url = 'http://webserver-broker:8080/message-b';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);

    if (curl_errno($ch)) {
        $errorMsg = curl_error($ch);
        echo $errorMsg;
    }

    curl_close($ch);
}