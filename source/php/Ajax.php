<?php

namespace ModularitySocialMedia;

class Ajax
{

    private $metaKey = 'mod_socialmedia_hidden_inlays';

    public function __construct()
    {

        //Capability check
        if (!is_user_logged_in() || !current_user_can('edit_posts')) {
            return false;
        }

        add_action('wp_ajax_mod_socialmedia_toggle_inlay_visibility', array($this, 'toggleHiddenInlay'));
    }

    /**
     * Add or remove to hidden inlay array
     * @param array $feed array items with the feed data
     * @return array $feed sanitized output array
     */
    public function toggleHiddenInlay()
    {

        //Get ajax data
        $currentInlayId     = esc_attr($_POST['inlay_id']);
        $moduleId           = intval($_POST['module_id']);

        //Get prev state
        $hiddenInlays = get_post_meta($moduleId, $this->metaKey, true);

        //Validate that array exists
        if (!is_array($hiddenInlays)) {
            $hiddenInlays = array();
        }

        //Add or remove id
        if (in_array($currentInlayId, $hiddenInlays)) {
            if (($key = array_search($currentInlayId, $hiddenInlays)) !== false) {
                unset($hiddenInlays[$key]);
            }
        } else {
            $hiddenInlays[] = $currentInlayId;
        }

        //Do db update
        $response = update_post_meta($moduleId, $this->metaKey, $hiddenInlays);

        //Return to js
        die(json_encode(array(
            'update_state' => $response,
            'hidden_inlays' => $hiddenInlays,
        )));
    }
}
