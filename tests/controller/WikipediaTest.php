<?php

use WikiToSimple\Controller\Wikipedia;

class WikipediaTest extends PHPUnit_Framework_TestCase{

    /** @var Wikipedia wikipedia */
    private $wikipedia;

    function setUp()
    {
        $this->wikipedia = new Wikipedia();
    }

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
            $result = $this->wikipedia->createSafeAPIArticleFromLink($link);
            $this->assertEquals($result, $expected);
        }
    }
}
