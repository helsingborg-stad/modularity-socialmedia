<?php

namespace ModularitySocialMedia\Network;

class Instagram extends \ModularitySocialMedia\Controller
{

    private $cache = null;
    private $baseUrl = 'https://instagram.com/';
    private $username;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Request the posts by username
     * @param string $username The username to fetch feed from
     * @return array/bool The data fetched from the service api or false if none
     */

    public function getUser($username)
    {
        if (is_null($this->cache)) {
            $this->cache = $this->parseMarkup(file_get_contents($this->baseUrl . $username));
        }

        $response = array();

        if (isset($this->cache->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges) && $items = $this->cache->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges) {
            if (!empty($items) && is_array($items)) {
                foreach ($items as $item) {

                    $item = $item->node;

                    $response[] = array(
                      'id' => $item->id . "-instagram",
                      'user_name' => $this->getProfile()['name'],
                      'profile_pic' => $this->getProfile()['profilepic'],
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
            }
        }

        return $response;
    }

    /**
     * Request the user profile
     * @param string $username The usernanme to fetch profile of
     * @return array/bool The data fetched from the service api or false if none
     */

    private function getProfile() : array
    {
        if (isset($this->cache->entry_data->ProfilePage[0]->graphql->user)) {
            $user = $this->cache->entry_data->ProfilePage[0]->graphql->user;
            return array(
                'name' => $user->full_name,
                'user_name' => $user->username,
                'profilepic_sd' => $user->profile_pic_url,
                'profilepic' => $user->profile_pic_url_hd,
                'biography' => $user->biography,
            );
        }
        return array();
    }

    /**
    * Parse data recived
    * @param  $markup Raw data from webpage
    * @return Array with raw feed data
    */
    public function parseMarkup($markup)
    {
        //Define what to get
        $startTag = '<script type="text/javascript">window._sharedData = ';
        $endTag   = ';</script>';

        //Match string with reguklar exp
        $hasMatch = preg_match(
                      "#" . preg_quote($startTag, "#")
                      . '(.*?)'
                      . preg_quote($endTag, "#")
                      . "#"
                      . 's', $markup, $matches);

        //Return matches (if valid json)
        if ($hasMatch && isset($matches[0])) {
            $matches = str_replace($startTag, "", $matches[0]);
            $matches = str_replace($endTag, "", $matches);

            return json_decode($matches);
        }

        //Nothing found, return false.
        return false;
    }
}
