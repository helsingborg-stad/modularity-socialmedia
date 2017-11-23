<?php

namespace ModularityTiles;

class Feed
{
    public $grid = array();

    public $tile = array();

    public $url;

    public $title;

    public $content;

    public $image;


    public function __construct($tile)
    {
        $this->setSize($tile['tile_size']);

        if ($tile['link_type'] !== 'image') {
            $this->setTitle($tile);
            $this->setUrl($tile);
            $this->setContent($tile);
        } else {
            $this->setImage($tile);
        }

        //Convert class arrays to string
        $this->grid = implode(' ', $this->grid);
        $this->tile = implode(' ', $this->tile);
    }

    /**
     * Get image that shouild been used and append class
     * @return void
     */

    public function setImage($tile)
    {
        if ($tile['tile_size'] == 'horizontal') {
            $this->image = $this->getResizedImageUrl($tile['custom_image'], array(854, 427));
        } elseif ($tile['tile_size'] == 'vertical') {
            $this->image = $this->getResizedImageUrl($tile['custom_image'], array(427, 854));
        }

        if (is_null($this->image)) {
            $this->tile[] = 'tile-img';
        }
    }

    /**
     * Set content sizes
     * @return void
     * @param $tile A tile object
     */

    public function setContent($tile)
    {
        if ($tile['tile_size'] == 'horizontal' || $tile['tile_size'] == 'vertical') {
            $this->content = $tile['lead'];
            $this->tile[] = 'invert';
            $this->grid['xs'] = 'grid-xs-12';
        }
    }

    /**
     * Set tile size according to settings provided
     * @return void
     * @param $tile A tile object
     */

    public function setSize($size)
    {
        $this->tile[] = 'tile';
        if ($size == 'square') {
            $this->grid['xs'] = 'grid-xs-6';
            $this->grid['lg'] = 'grid-lg-4';
        } elseif ($size == 'horizontal') {
            $this->grid['xs'] = 'grid-xs-12';
            $this->grid['lg'] = 'grid-lg-8';
            $this->tile[] = 'tile-h';
        } elseif ($size == 'vertical') {
            $this->grid['xs'] = 'grid-xs-6';
            $this->grid['lg'] = 'grid-lg-4';
            $this->tile[] = 'tile-v';
        }
    }

    /**
     * Set url from textfield or linked item
     * @return void
     * @param $tile A tile object
     */

    public function setUrl($tile)
    {
        if ($tile['link_type'] == 'internal') {
            $this->url = get_permalink($tile['page']->ID);
        } else {
            $this->url = $tile['link_url'];
        }
    }

    /**
     * Set the title of the tile, get title from link if not set.
     * @return void
     * @param $tile A tile object
     */

    public function setTitle($tile)
    {
        if ($tile['link_type'] == 'internal' && !$tile['title']) {
            $this->title = $tile['page']->post_title;
        } else {
            $this->title = $tile['title'];
        }
    }

    /**
     * Resize image and return url of the resize
     * @return string or null
     * @param array $imageObject standard wordpress image data array (4 items)
     * @param array $size array with width and height of the image that should be returned
     */

    public function getResizedImageUrl($imageObject, $size = array(100, 100))
    {
        if (!isset($imageObject['id'])) {
            return null;
        }

        if (isset($imageObject['id']) && !is_numeric($imageObject['id'])) {
            return null;
        }

        if ($image = wp_get_attachment_image_src($imageObject['id'], $size)) {
            if (is_array($image) && count($image) == 4) {
                return $image[0];
            }
        }

        return null;
    }
}
