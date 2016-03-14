<?php

use WikiToSimple\Controller\Wikipedia;

class WikipediaTest extends PHPUnit_Framework_TestCase{

    function testSafeApiArticleNameConversion()
    {

        $inputData = array(
            "https://en.wikipedia.org/wiki/T453_dawf#" => "T453%20Dawf",
            "https://en.wikipedia.org/wiki/" => null,
            "https://en.wikipedia.org/wiki/World_News_Is_Awesome" => "World%20News%20Is%20Awesome",
            4 => null,
            "454" . 5 . "¢" => null
        );

        foreach ($inputData as $link => $expected) {
            $result = Wikipedia::createSafeAPIArticleFromLink($link);
            $this->assertEquals($result, $expected);
        }
    }

    function testGetLinkFromText()
    {
        $inputData = array(
            "https://www.en.wikipedia.org/wiki/Test_dawf#" => array("https://www.en.wikipedia.org/wiki/Test_dawf#"),
            "https://www.en.wikipedia.org/wiki/Tests*_dawf#" => array("https://www.en.wikipedia.org/wiki/Tests"),
            "www.en.wikipedia.org/wiki/Tests*_dawf#" => array(),
            "https://en.wikipedia.org/wiki/Tests423_dawf#" => array("https://en.wikipedia.org/wiki/Tests423_dawf#"),
            "https://www.en.wikipedia.org/wiki/T\$ests*_dawf#" => array("https://www.en.wikipedia.org/wiki/T")
        );

         foreach ($inputData as $message => $expected) {

            $result = Wikipedia::getLinkFromText($message);

            $this->assertEquals(array($expected), $result);
        }
    }
}
