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
     * @param string $originalLink A Link to a Wikipedia article
     * @return string safe API article name name on success or null on failure
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
     * @param $url string $url containing original language
     * @param $oldLang string $old language to replace
     * @param $newLang string $new language to append
     * @return string of the new url on success or null on failure
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
