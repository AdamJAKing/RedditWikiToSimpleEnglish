<?php

use WikiToSimple\Controller\Reddit;
use WikiToSimple\Config;
use WikiToSimple\Includes\Database;

require_once("vendor/autoload.php");

require_once("app/WikiToSimple/Includes/Settings.inc");

Database::getInstance()->connect();

$reddit = new Reddit();
$isConnected = $reddit->connect();


if ($isConnected) {
    $threads = $reddit->getTopThreadsInfo();

    foreach ($threads as $thread) {
        $threadFullName = $thread['name'];

        var_dump($thread['url']);
    }
}
