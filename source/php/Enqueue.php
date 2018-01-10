<?php

namespace ModularitySocialMedia;

class Enqueue
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));

    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {

        //Capability check
        if (!is_user_logged_in() || !current_user_can('edit_posts')) {
            return false;
        }

        wp_enqueue_script('modularity-socialmedia-js', MODULARITYSOCIALMEDIA_URL . '/dist/js/modularity-socialmedia.min.js', false);
    }
}
