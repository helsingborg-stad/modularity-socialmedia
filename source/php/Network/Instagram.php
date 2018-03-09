<?php

namespace ModularitySocialMedia\Network;

class Instagram extends \ModularitySocialMedia\Controller
{

    private $username = "";
    private $profileCache = array();
    private $apiDomain = '';

    public function __construct()
    {
        parent::__construct();
        $this->apiDomain = date('W')%2==0 ? 'hbg-instagram-proxy-second.herokuapp.com' : 'hbg-instagram-proxy.herokuapp.com';
    }

    /**
     * Format the response accoring to promised value
     * @param stdObject $response The response value from the service formatted as std object
     * @param string $type The type of feed provided (user/hashtag)
     * @param string $origin The origin of the feteced posts (can be any but normally a hashtag name or account)
     * @return array $feed formatted array with feed data
     */

    private function formatResponse($response, $type = 'user', $origin = "")
    {
        $result = array();

        if (isset($response->posts) && !empty($response->posts)) {
            foreach ($response->posts as $item) {
                $profile = null;

                if ($type == "user") {
                    $profile = $this->getProfile($this->username);

                    $result[] = array(
                        'id' => $item->id,
                        'user_name' => "@" . $origin,
                        'profile_pic' => $profile['profilepic'],
                        'timestamp' => $item->taken_at_timestamp,
                        'timestamp_readable' => $this->readableTimeStamp($item->taken_at_timestamp),
                        'content' => wp_trim_words(str_replace("#", " #", $item->edge_media_to_caption->edges[0]->node->text), 40, "..."),

                        'image_large' => $item->thumbnail_resources[4]->src,
                        'image_small' => $item->thumbnail_resources[0]->src,

                        'number_of_likes' => (isset($item->edge_liked_by) && isset($item->edge_liked_by->count) ? ($item->edge_liked_by->count ? $item->edge_liked_by->count : 0) : 0),
                        'network_source' => 'https://www.instagram.com/p/'.$item->shortcode.'/',
                        'network_name' => 'instagram',

                        'link' => "",
                        'link_title' => "",
                        'link_content' => "",
                        'link_og_image' => "",
                    );
                }

                if ($type == "hashtag") {
                    $result[] = array(
                        'id' => $item->id,
                        'user_name' => "#" . $origin,
                        'timestamp' => $item->taken_at_timestamp,
                        'timestamp_readable' => $this->readableTimeStamp($item->taken_at_timestamp),
                        'content' => wp_trim_words(str_replace("#", " #", $item->edge_media_to_caption->edges[0]->node->text), 40, "..."),

                        'image_large' => $item->thumbnail_resources[4]->src,
                        'image_small' => $item->thumbnail_resources[0]->src,

                        'number_of_likes' => ($item->edge_liked_by->count ? $item->edge_liked_by->count : 0),
                        'network_source' => 'https://www.instagram.com/p/'.$item->shortcode.'/',
                        'network_name' => 'instagram',

                        'link' => "",
                        'link_title' => "",
                        'link_content' => "",
                        'link_og_image' => "",
                    );
                }
            }
        }

        return $result;
    }

    /**
     * Request the posts by hashtag
     * @param string $hashtag The hashtag to fetch feed from
     * @return array/bool The data fetched from the service api or false if none
     */

    public function getHashtag($hashtag = null)
    {

        //Fallback to init hashtag
        if (empty($hashtag)) {
            $this->registerError("Not a valid hashtag");
            return false;
        }

        //Set to gobal
        if (empty($this->username)) {
            $this->username = $hashtag;
        }

        //Call
        $data = $this->curl->request('GET', 'https://' . $this->apiDomain . '/explore/tags/'. $hashtag .'/media/?count=20'); // Using a proxy for better stability

        //Parse
        if (json_decode($data)) {
            return $this->formatResponse(json_decode($data), 'hashtag', $hashtag);
        }

        //Error return
        return false;
    }

    /**
     * Request the posts by username
     * @param string $username The username to fetch feed from
     * @return array/bool The data fetched from the service api or false if none
     */

    public function getUser($username = null)
    {

        //Fallback to init username
        if (empty($username)) {
            $this->registerError("Not a valid username");
            return false;
        }

        //Set to gobal
        if (empty($this->username)) {
            $this->username = $username;
        }

        //Call
        $data = $this->curl->request('GET', 'https://' . $this->apiDomain . '/' .$username. '/media?count=20'); // Using a proxy for better stability

        //Parse
        if (json_decode($data)) {
            return $this->formatResponse(json_decode($data), 'user', $username);
        }

        //Error return
        return false;
    }

    /**
     * Request the user profile
     * @param string $username The usernanme to fetch profile of
     * @return array/bool The data fetched from the service api or false if none
     */

    public function getProfile($username)
    {
        //Fallback to init username
        if (empty($username)) {
            $this->registerError("Not a valid username");
            return false;
        }

        //Get from cache
        if (array_key_exists($username, $this->profileCache)) {
            return $this->profileCache[$username];
        }

        //Call
        $data = $this->curl->request('GET', 'https://' . $this->apiDomain . '/' . $username . '/?__a=1'); // Using a proxy for better stability

        //Parse
        if ($data = json_decode($data)) {
            return $this->profileCache[$username] = array(
                'username' => $data->user->username,
                'profilepic' => $data->user->profile_pic_url,
            );
        }

        //Error return
        return false;
    }
}
