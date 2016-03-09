<?php

use WikiToSimple\Controller\Reddit;
use WikiToSimple\Config;

require_once("vendor/autoload.php");

require_once("app/WikiToSimple/Config/Settings.php");

$reddit = new Reddit($settings['username'], $settings['password'], $settings['clientId'], $settings['clientSecret']);
$isConnected = $reddit->connect();


if ($isConnected) {
    $threads = $reddit->getTopThreadsInfo();

    foreach ($threads as $thread) {
        $threadFullName = $thread['name'];

        var_dump($thread['url']);
    }
}
