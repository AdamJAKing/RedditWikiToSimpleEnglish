<?php

namespace WikiToSimple\Controller;

class Wikipedia
{

    function searchForArticle($language, $article)
    {
        $url = "https://" . $language . ".wikipedia.org/w/api.php?action=query&titles=" . $article . "&format=json";
    }

    function createSafeAPIArticleFromLink($originalLink) {
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
}
