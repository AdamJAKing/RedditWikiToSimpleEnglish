<?php

use GiphyBot\Controller\Reddit;
use GiphyBot\Config;

require_once("vendor/autoload.php");

require_once("app/GiphyBot/Config/Settings.php");

$reddit = new Reddit($settings['username'], $settings['password'], $settings['clientId'], $settings['clientSecret']);
$isConnected = $reddit->connect();


if ($isConnected) {
    $threads = $reddit->getTopThreadsInfo();

    foreach ($threads as $thread) {
        $threadFullName = $thread['name'];

        var_dump($thread['url']);
    }
}
