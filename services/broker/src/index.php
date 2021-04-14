<?php

require_once 'broker.php';


if ($_SERVER["REQUEST_URI"] === '/message' && isset($_REQUEST['message'])) {
    $token = createMessage($_REQUEST['message']);
    produceTopic($token, $_REQUEST['message'], 'TopicA');
    echo json_encode(['token' => $token]);
}

if ($_SERVER["REQUEST_URI"] === '/message-a') {
    produceTopic($_REQUEST['token'], $_REQUEST['message'], 'TopicB');
}

if ($_SERVER["REQUEST_URI"] === '/message-b') {
    updateMessage($_REQUEST['token'], $_REQUEST['message']);
}

if ($_SERVER["REQUEST_URI"] === '/check-message' && isset($_REQUEST['token'])) {
    $result = checkMessage($_REQUEST['token']);
    echo json_encode($result);
}

if ($_SERVER["REQUEST_URI"] === '/phpinfo') {
    phpinfo();
}
