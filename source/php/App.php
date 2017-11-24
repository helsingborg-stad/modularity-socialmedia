<?php

namespace ModularitySocialMedia;

class App extends \Modularity\Module
{
    public $slug = 'socialmedia';
    public $supports = array();

    public $feedArgs;


    public function init()
    {
        $this->nameSingular = __("Social Media", 'modularity');
        $this->namePlural = __("Sociala Media", 'modularity');
        $this->description = __("Outputs a social media feed from desired usernames or hashtags (facebook, instagram, twitter, linkedin). The feed can combine multiple sources in a single feed.", 'modularity');
    }

    public function data() : array
    {
        $avabile_feeds = get_field('social_media_feeds', $this->ID);

        $result = array();

        if (!empty($avabile_feeds) && is_array($avabile_feeds)) {
            foreach ($avabile_feeds as $feed) {
                switch ($feed['acf_fc_layout']) {
                    case 'facebook':

                            $facebook = new Network\Facebook($feed['mod_socialmedia_fb_app_id'], $feed['mod_socialmedia_fb_app_secret']);
                            $result = $result + $facebook->getUser($feed['mod_socialmedia_fb_username']);

                        break;

                    case 'instagram':

                        if ($feed['mod_socialmedia_in_type'] == "user") {
                            $instagram = new Network\Instagram();
                            $result = $result + $instagram->getUser($feed['mod_socialmedia_in_username']);
                        }

                        if ($feed['mod_socialmedia_in_type'] == "hashtag") {
                            $instagram = new Network\Instagram();
                            $result = $result + $instagram->getHashtag($feed['mod_socialmedia_in_hashtag']);
                        }

                        break;
                }
            }
        }



        //Remove duplicate posts
        $data['feed'] = $this->removeDuplicates($result);

        //Only get n first items
        $data['feed'] = array_slice($data, 0, get_field('mod_social_items', $this->ID));

        //Sort by publish date
        usort($data['feed'], function ($a, $b) {
            return (int) $b['timestamp'] - (int) $a['timestamp'];
        });

        //Add translation strings
        $data['translations'] = array(
            'likes' => __("likes", 'modularity-socialmedia'),
            'posted' => __("posted on", 'modularity-socialmedia'),
            'ago' => __("ago", 'modularity-socialmedia')
        );

        //Add classes
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array(), $this->post_type, $this->args));

        return $data;
    }

    public function removeDuplicates($feed)
    {
        $sanitized= array();

        if (is_array($feed) && !empty($feed)) {
            foreach ($feed as $item) {
                if (!array_key_exists($item['id'], $sanitized)) {
                    $sanitized[$item['id']] = $item;
                }
            }

            return $sanitized;
        }

        return $feed;
    }

    public function template() : string
    {
        return "feed.blade.php";
    }

    /**
     * Available "magic" methods for modules:
     * init()            What to do on initialization
     * data()            Use to send data to view (return array)
     * style()           Enqueue style only when module is used on page
     * script            Enqueue script only when module is used on page
     * adminEnqueue()    Enqueue scripts for the module edit/add page in admin
     * template()        Return the view template (blade) the module should use when displayed
     */
}
