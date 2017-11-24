<?php

namespace ModularitySocialMedia\Network;

class Instagram extends \ModularitySocialMedia\Controller
{

    public $username = "";
    private $profileCache = array();

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Format response to promise
     */

    public function formatResponse($response, $type = 'user', $origin = "")
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
                        'content' => wp_trim_words($item->edge_media_to_caption->edges[0]->node->text, 40, "..."),

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

                if ($type == "hashtag") {

                    $result[] = $i = array(
                        'id' => $item->id,
                        'user_name' => "#" . $origin,
                        'timestamp' => $item->taken_at_timestamp,
                        'timestamp_readable' => $this->readableTimeStamp($item->taken_at_timestamp),
                        'content' => wp_trim_words($item->edge_media_to_caption->edges[0]->node->text, 40, "..."),

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
        $data = $this->curl->request('GET', 'https://igapi.ga/explore/tags/'. $hashtag .'/media/?count=20'); // Using a proxy for better stability

        //Parse
        if (json_decode($data)) {
            return $this->formatResponse(json_decode($data), 'hashtag', $hashtag);
        }

        //Error return
        return false;

    }

    /**
     * Request the posts by username
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
        $data = $this->curl->request('GET', 'https://igapi.ga/' .$username. '/media?count=20'); // Using a proxy for better stability

        //Parse
        if (json_decode($data)) {
            return $this->formatResponse(json_decode($data), 'user', $username);
        }

        //Error return
        return false;
    }

    /**
     * Request the user profile information
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
        $data = $this->curl->request('GET', 'https://igpi.ga/' . $username . '/?__a=1'); // Using a proxy for better stability

        //Parse
        if ($data = json_decode($data)) {
            return $this->profileCache[$data->username] = array(
                'username' => $data->user->username,
                'profilepic' => $data->user->profile_pic_url,
            );
        }

        //Error return
        return false;
    }
}
