<?php

namespace ModularitySocialMedia\Network;

class Instagram extends \ModularitySocialMedia\Controller
{

    private $username;
    private $hashtag;
    private $accessToken = "1406045013.3a81a9f.7c505432dfd3455ba8e16af5a892b4f7"; //Hijacked without shame from another opensource social media plugin

    public function __construct($username = "", $hashtag = "")
    {
        parent::__construct();

        $this->username = $username;
        $this->hashtag = $hashtag;
    }

    /**
     * Format response to promise
     */

    public function formatResponse($response, $type = 'user')
    {

        $result = array();

        if (isset($response->posts) && !empty($response->posts)) {

            foreach ($response->posts as $item) {

                if ($type == "user") {

                    $result[] = array(
                        'user_name' => "@" . $this->username,
                        'timestamp' => $item->taken_at_timestamp,
                        'content' => $item->edge_media_to_caption->edges[0]->node->text,

                        'image_large' => $item->thumbnail_resources[4]->src,
                        'image_small' => $item->thumbnail_resources[0]->src,

                        'number_of_likes' => $item->edge_media_preview_like->count,
                        'network_source' => 'https://www.instagram.com/p/'.$item->shortcode.'/',
                        'network_name' => 'instagram',

                        'link' => "",
                        'link_title' => "",
                        'link_content' => "",
                        'link_og_image' => "",
                    );

                }

                if ($type = "hashtag") {

                    $result[] = array(
                        'user_name' => "#" . $this->hashtag,
                        'timestamp' => $item->taken_at_timestamp,
                        'content' => $item->edge_media_to_caption->edges[0]->node->text,

                        'image_large' => $item->thumbnail_resources[4]->src,
                        'image_small' => $item->thumbnail_resources[0]->src,

                        'number_of_likes' => $item->edge_liked_by->count,
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
     * Request the posts
     */

    public function getHashtag($hashtag = null)
    {

        //Fallback to init hashtag
        if (empty($hashtag)) {
            $this->registerError("Not a valid hashtag");
            return false;
        }

        //Set to gobal
        $this->hashtag = $hashtag;

        //Call
        $data = $this->curl->request('GET', 'https://igapi.ga/explore/tags/'. $hashtag .'/media/?count=20'); // Using a proxy for better stability

        //Parse
        if (json_decode($data)) {
            return $this->formatResponse(json_decode($data), 'hashtag');
        }

        //Error return
        return false;

    }


    public function getUser($username = null)
    {

        //Fallback to init username
        if (empty($username)) {
            $this->registerError("Not a valid username");
            return false;
        }

        //Set to gobal
        $this->username = $username;

        //Call
        $data = $this->curl->request('GET', 'https://igapi.ga/' .$username. '/media?count=20'); // Using a proxy for better stability

        //Parse
        if (json_decode($data)) {
            return $this->formatResponse(json_decode($data), 'user');
        }

        //Error return
        return false;
    }

}
