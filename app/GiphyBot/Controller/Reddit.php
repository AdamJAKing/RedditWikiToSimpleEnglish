<?php

namespace GiphyBot\Controller;

use GiphyBot\Config\RedditAPI;

class Reddit
{
    private $username;
    private $password;
    private $clientId;
    private $clientSecret;

    private $accessToken;

    const USER_AGENT = "WikipediaToSimpleEnglish-Bot created by AdamKSoftware";

    function __construct($username, $password, $clientId, $clientSecret)
    {
        $this->username = $username;
        $this->password = $password;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    private function generateOAuthCurlRequest($url, $curlOpts = null)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("authorization: bearer " . $this->accessToken));
        curl_setopt($curl, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


        if ($curlOpts) {
            foreach ($curlOpts as $key => $value) {
                curl_setopt($curl, $key, $value);
            }
        }

        $response = curl_exec($curl);

        return json_decode($response, true);
    }

    function connect()
    {

        $fields = array(
            "grant_type" => "password",
            "username" => $this->username,
            "password" => $this->password
        );

        $finalFields = http_build_query($fields);

        $curl = curl_init(RedditAPI::REDDIT_ACCESS_TOKEN_URL);
        curl_setopt($curl, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($curl, CURLOPT_USERPWD, $this->clientId . ":" . $this->clientSecret);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $finalFields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['access_token'])) {
            $this->accessToken = $jsonResult['access_token'];

            $isUserAuth = $this->exchangeAccessToken($this->accessToken);

            if ($isUserAuth) {
                echo "Successfully connected to reddit\n";

                return true;
            }

            echo "Failed to connect to reddit but an access token was generated. Is reddit down?\n";

            return;
        }

        echo "Access denied, check your reddit settings\n";

        return;
    }

    function exchangeAccessToken($token)
    {

        $curlJsonResponse = $this->generateOAuthCurlRequest(RedditAPI::REDDIT_TOKEN_EXCHANGE_URL);

        if ($curlJsonResponse['name']) {
            return true;
        }

        return true;
    }

    function getTopThreadsInfo()
    {

        echo "Getting the top 100 posts on reddit...\n";

        $curlJsonResponse = $this->generateOAuthCurlRequest(RedditAPI::REDDIT_TOP_POSTS_URL . "?limit=100");

        $threadsInfo = array();

        echo "Getting all URL's...\n";

        foreach ($curlJsonResponse['data']['children'] as $key => $value) {
            $threadInfo = array ();

            $threadInfo["kind"] = $value['kind'];

            foreach ($value['data'] as $dataKey => $dataValue) {

                $threadInfo[$dataKey] = $dataValue;
            }

            $threadsInfo[] = $threadInfo;
        }
        return $threadsInfo;

    }

    function postCommentToThread($fullname, $message)
    {

        $curlOpts = array(
            CURLOPT_POSTFIELDS => array("api_type" => "json", "thing_id" => $fullname, "text" => $message )
        );

        $curlJsonResponse = $this->generateOAuthCurlRequest(RedditAPI::REDDIT_POST_COMMENT, $curlOpts);

        if ($curlJsonResponse['json']['errors']) {
            echo "Post to thread failed!\n";

            return;
        }

        return $curlJsonResponse;
    }
}
