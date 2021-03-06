<?php

namespace WikiToSimple\Controller;

use WikiToSimple\Config\RedditAPI;

class Reddit
{
    private $username = REDDIT_USER;
    private $password = REDDIT_PASSWORD;
    private $clientId = REDDIT_CLIENT_ID;
    private $clientSecret = REDDIT_CLIENT_SECRET;

    private $accessToken;

    const USER_AGENT = "WikipediaToSimpleEnglish-Bot created by AdamKSoftware";

    private function generateOAuthCurlRequest($url, $curlOpts = null)
    {
        $curl = curl_init($url);

        // If the accessToken exists, then we must be authorised and we should use this header
        if ($this->accessToken) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("authorization: bearer " . $this->accessToken));
        }

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

        $curl = curl_init(RedditAPI::REDDIT_ACCESS_TOKEN_URL);
        curl_setopt($curl, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt($curl, CURLOPT_USERPWD, $this->clientId . ":" . $this->clientSecret);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
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

        $curlJsonResponse = $this->generateOAuthCurlRequest(RedditAPI::REDDIT_URL . "?limit=100");

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

    //TODO Allow this function to search 3+ comments deep from each original reply
    function getCommentsFromThread($sub, $postId)
    {
        $curlJsonResponse = $this->generateOAuthCurlRequest(RedditAPI::REDDIT_URL . "/r/" . $sub . "/comments/" . $postId . ".json?limit=500");

        $comments = array();

        foreach ($curlJsonResponse as $responseKey => $responseValue) {

            if ($responseKey != 0) {

                foreach ($responseValue['data']['children'] as $commentsKey => $commentsValue) {

                    foreach ($commentsValue['data'] as $commentKey => $commentValue) {

                        if ($commentKey == "body") {
                            $comments[] = $commentValue;
                        }
                    }
                }
            }
        }
        return $comments;
    }
}
