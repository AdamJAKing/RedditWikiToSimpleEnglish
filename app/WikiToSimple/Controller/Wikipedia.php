<?php

namespace WikiToSimple\Controller;

/**
 * Class Wikipedia
 * @package WikiToSimple\Controller
 *
 * A simple helper/utility class that provides integration for Wikipedia based tasks
 */

final class Wikipedia
{

    static function searchForArticle($language, $article)
    {
        $url = "https://" . $language . ".wikipedia.org/w/api.php?action=query&titles=" . $article . "&format=json";
        $urlContents = file_get_contents($url);

        $urlJson = json_decode($urlContents, true);

        if (isset($urlJson['query']['pages']['-1'])) {
            echo "Page doesn't exist\n";

            return false;
        }

        return true;
    }

    /**
     * @param $originalLink A Link to a Wikipedia article
     * @return Returns a safe API article name on success or null on failure
     */
    static function createSafeAPIArticleFromLink($originalLink) {
        if (stripos($originalLink, "/wiki/") !== false) {

            $articleNameStart = stripos($originalLink, "/wiki/") + 6;

            // Gets just the article name and Strips off the # link operator
            $articleRawName = substr($originalLink, $articleNameStart);

            if (strpos($articleRawName, "#") !== false) {
                $hashPos = strpos($articleRawName, "#");

               $articleRawName =  substr($articleRawName, 0, $hashPos);
            }

            $articleRemoveUnderscores =  str_ireplace("_", " ", $articleRawName);
            $safeArticleName =  str_ireplace(" ", "%20", ucwords($articleRemoveUnderscores));

            return $safeArticleName;
        }
        return;
    }

    /**
     * @param $url URL containing original language
     * @param $oldLang Old language to replace
     * @param $newLang New language to append
     * @return New url on success or null on failure
     */

    static function changeArticleUrLLanguage($url, $oldLang, $newLang) {

        $langStart = stripos($url, $oldLang);

        if ($langStart !== false) {
            $newUrl = substr_replace($url, $newLang, $langStart, strlen($oldLang));

            return $newUrl;
        }

        return;
    }
}
