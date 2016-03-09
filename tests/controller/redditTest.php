<?php

use GiphyBot\Controller\Reddit;

class RedditTest extends PHPUnit_Framework_TestCase {

    private $reddit;

    function setUp ()
    {
        $this->reddit = new Reddit([], "sdsa", [], "");

    }

    function testRedditConnection()
    {
        $response = $this->reddit->connect();

        $this->assertEquals(null, $response);
    }
}
