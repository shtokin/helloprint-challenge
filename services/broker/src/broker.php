<?php

require_once 'config.php';

const KAFKA_PARTITION = 0;

function createMessage(string $message): string
{
    $token = generateToken();

    try {
        $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';user=' . DB_USER
            . ';password=' . DB_PASSWORD;
        $conn = new PDO($dsn);

        $sql = "INSERT INTO request(token, message) VALUES(?, ?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$token, $message]);

    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }

    return $token;
}


function produceTopic(string $token, string $messageText, string $topicName)
{
    $conf = new RdKafka\Conf();
    //$conf->set('debug', 'all');

    $producer = new RdKafka\Producer($conf);

    $producer->setLogLevel(LOG_DEBUG);
    $producer->addBrokers('kafka');

    $topic = $producer->newTopic($topicName);

    $message = json_encode(['token' => $token, 'message' => $messageText]);
    $topic->produce(KAFKA_PARTITION, 0, $message);

    $result = $producer->flush(5000);

    if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
        throw new \RuntimeException('Was unable to flush, messages might be lost!');
    }

}

function generateToken(): string
{
    return md5(microtime() . rand(1,9999));
}

function updateMessage(string $token, string $message)
{
    try {
        $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';user=' . DB_USER
            . ';password=' . DB_PASSWORD;
        $conn = new PDO($dsn);

        $sql = "UPDATE request SET message = ?, is_ready = true WHERE token = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$message, $token]);
        $statement->rowCount();

    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}

function checkMessage(string $token): array
{
    try {
        $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';user=' . DB_USER
            . ';password=' . DB_PASSWORD;
        $conn = new PDO($dsn);

        $sql = "SELECT message FROM request WHERE token = ? AND is_ready = true";
        $statement = $conn->prepare($sql);
        $statement->execute([$token]);
        $result = $statement->fetch();
        if ($result) {
            return ['finalMessage' => $result['message']];
        }

        return ['error' => 'Token not found'];

    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}