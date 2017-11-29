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
        $this->ttl = 3600;
    }

    /**
     * Allocate view with data
     * @return array $data Data sent to the view
     */

    public function data() : array
    {
        $avabile_feeds = get_field('social_media_feeds', $this->ID);

        $data['feed'] = array();

        if (!empty($avabile_feeds) && is_array($avabile_feeds)) {
            foreach ($avabile_feeds as $feed) {
                $result = false;

                switch ($feed['acf_fc_layout']) {
                    case 'facebook':

                            $facebook = new Network\Facebook($feed['mod_socialmedia_fb_app_id'], $feed['mod_socialmedia_fb_app_secret']);
                            if($result = $facebook->getUser($feed['mod_socialmedia_fb_username'])) {
                                $data['feed'] = $data['feed'] + $result;
                            }

                        break;

                    case 'instagram':

                        if ($feed['mod_socialmedia_in_type'] == "user") {
                            $instagram = new Network\Instagram();
                            if ($result = $instagram->getUser($feed['mod_socialmedia_in_username'])) {
                                $data['feed'] = $data['feed'] + $result;
                            }
                        }

                        if ($feed['mod_socialmedia_in_type'] == "hashtag") {
                            $instagram = new Network\Instagram();
                            if ($result = $instagram->getHashtag($feed['mod_socialmedia_in_hashtag'])) {
                                $data['feed'] = $data['feed'] + $result;
                            }
                        }

                        break;
                }
            }
        }

        //Remove duplicate posts
        $data['feed'] = $this->removeDuplicates($data['feed']);

        //Sort by publish date
        $data['feed'] = $this->sortByTimestamp($data['feed']);

        //Only get n first items
        $data['feed'] = $this->truncateFeed($data['feed']);

        //Add translation strings
        $data['translations'] = array(
            'likes' => __("likes", 'modularity-socialmedia'),
            'posted' => __("posted on", 'modularity-socialmedia'),
            'ago' => __("ago", 'modularity-socialmedia'),
            'noposts' => __("We could not retrive any social media posts at this time.", 'modularity-socialmedia'),
        );

        //Number of columns
        $data['columns'] = $this->getNumberOfColumnsClass();

        //Button stuff
        $data['link'] = get_field('mod_social_link', $this->ID);
        $data['linkTarget'] = get_field('mod_social_link_url', $this->ID);
        $data['linkLabel'] = get_field('mod_social_link_text', $this->ID);

        //Add classes
        $data['classes'] = implode(' ', apply_filters('Modularity/Module/Classes', array(), $this->post_type, $this->args));

        //Generate a section id
        $data['sectionID'] = sanitize_title($this->post_title);

        return $data;
    }

    /**
     * Get a class for item with by column count
     * @return string a class indicating how many columns that should be shown
     */

    public function getNumberOfColumnsClass()
    {
        $numberOfColumns = get_field('mod_social_columns', $this->ID);

        //Retain to allowed values
        if (!in_array($numberOfColumns, array(1, 2, 3, 4))) {
            $numberOfColumns = 3;
        }

        //Calculate number of colums
        if (is_numeric($numberOfColumns)) {
            return "grid-xs-12 grid-sm-" . (12 / $numberOfColumns);
        }
        return 'grid-xs-12 grid-sm-4';
    }

    /**
     * Truncate feed according to max items
     * @param array $feed array items with the feed data
     * @return array $feed truncated feed
     */

    public function truncateFeed($feed)
    {
        $limit = is_numeric(get_field('mod_social_items', $this->ID)) ? get_field('mod_social_items', $this->ID) : 10;
        return array_slice($feed, 0, $limit);
    }

    /**
     * Sort data by timestamp
     * @param array $feed array items with the feed data
     * @return array $feed sanitized output array
     */

    public function sortByTimestamp($feed)
    {
        usort($feed, function ($a, $b) {
            return (int) $b['timestamp'] - (int) $a['timestamp'];
        });

        return $feed;
    }

    /**
     * Remove duplicate items by media type, compares id of the post.
     * @param array $feed array items with the feed data
     * @return array $feed sanitized output array
     */

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
