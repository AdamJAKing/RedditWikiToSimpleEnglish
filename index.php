<?php

use WikiToSimple\Controller\Reddit;
use WikiToSimple\Config;
use WikiToSimple\Includes\Database;
use WikiToSimple\Controller\Wikipedia;

require_once("vendor/autoload.php");

require_once("app/WikiToSimple/Includes/Settings.inc");

Database::getInstance()->connect();

$reddit = new Reddit();
$isConnected = $reddit->connect();


if ($isConnected) {
    $threads = $reddit->getTopThreadsInfo();

    $wikiLinks = array();

    $wikipedia = new Wikipedia();

    foreach ($threads as $thread) {
        $threadFullName = $thread['name'];

        if (stripos($thread['url'], "en.wikipedia.org") !== false ) {
            $wikiLinks[] = $thread['url'];
        }
    }

    foreach ($wikiLinks as $wikiLink) {
        $article = $wikipedia->createSafeAPIArticleFromLink($wikiLink);
    }
}
