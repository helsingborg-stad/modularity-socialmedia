<?php

namespace ModularitySocialMedia\Network;

class Facebook extends \ModularitySocialMedia\Controller
{

    private $appId;
    private $appSecret;
    private $username;
    private $accessToken = null;

    public function __construct($appId, $appSecret, $username = null)
    {

        //Construct parent
        parent::__construct();

        //Validate app id
        if (is_numeric($appId)) {
            $this->appId = $appId;
        } else {
            $this->registerError("Not a valid app id.");
            return false;
        }

        //Validate app secret
        if (!empty($appSecret)) {
            $this->appSecret = $appSecret;
        } else {
            $this->registerError("Not a valid app secret.");
            return false;
        }

        //Validate app username
        if (!empty($appId)) {
            $this->username = $username;
        } else {
            $this->registerError("Not a valid username.");
            return false;
        }

        //Create access token
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Format the response accoring to promised value
     * @param stdObject $response The response value from the service formatted as std object
     * @param string $type The type of feed provided (user/hashtag)
     * @return array $feed formatted array with feed data
     */

    private function formatResponse($response, $type = 'user')
    {

        $result = array();

        if (!empty($response) && is_array($response)) {

            foreach ($response as $item) {

                $result[] = array(
                    'id' => $item->object_id,
                    'user_name' => $item->from->name,
                    'profile_pic' => null,
                    'timestamp' => strtotime($item->created_time),
                    'timestamp_readable' => $this->readableTimeStamp(strtotime($item->created_time)),
                    'content' => wp_trim_words($item->message, 40, "..."),

                    'image_large' => (isset($item->full_picture) && !empty($item->full_picture) ? $item->full_picture : null),
                    'image_small' => (isset($item->picture) && !empty($item->picture) ? $item->picture : null),

                    'number_of_likes' => count($item->likes->data),
                    'network_source' => $item->link,
                    'network_name' => 'facebook',

                    'link' => "",
                    'link_title' => "",
                    'link_content' => "",
                    'link_og_image' => "",
                );

            }

        }

        return $result;
    }

    /**
     * Request the posts by username
     * @param string $username The username to fetch feed from
     * @return array/bool The data fetched from the service api or false if none
     */

    public function getUser($username = null)
    {
        //Fallback to init username
        if (is_null($username)) {
            $username = $this->username;
        }

        //Endpoint
        $endpoint = 'https://graph.facebook.com/' . $username . '/posts';

        //Feed
        $feed = $this->curl->request('GET', $endpoint, $t = array(
            'access_token' => $this->accessToken,
            'fields'       => implode(", ", array(
                'from',
                'full_picture',
                'picture',
                'message',
                'created_time',
                'object_id',
                'link',
                'name',
                'caption',
                'description',
                'icon',
                'type',
                'status_type',
                'likes'
            ))
        ));

        //Parse
        if (isset(json_decode($feed)->data) && !empty(json_decode($feed)->data)) {
            return $this->formatResponse(json_decode($feed)->data, 'user');
        }

        //Error return
        return false;
    }

    /**
     * Request a access token based on provided details.
     * @return string The access token.
     */

    private function getAccessToken()
    {

        //Setup access data
        $data = array(
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->appId,
            'client_secret' => $this->appSecret
        );

        //Call endpoint
        $token = $this->curl->request('GET', 'https://graph.facebook.com/oauth/access_token', $data);

        //Return error
        if (strpos($token, 'error') !== false) {
            error_log("Modularity error facebook" . implode(" | ", json_decode($token)));
            return false;
        }

        //Decode token
        if (isset(json_decode($token)->access_token) && !empty(json_decode($token)->access_token)) {
            return json_decode($token)->access_token;
        }

        return false;
    }
}
