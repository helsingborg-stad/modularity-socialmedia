<?php

namespace ModularityTiles\Module;

class Tiles extends \Modularity\Module
{
    public $slug = 'tiles';
    public $supports = array();

    public function init()
    {
        $this->nameSingular = __("Tiles", 'modularity-tiles');
        $this->namePlural = __("Tiles", 'modularity-tiles');
        $this->description = __("Display a tile-style post/page grid", 'modularity-tiles');
    }

    public function data() : array
    {
        $data = array();

        $data['tiles'] = $this->getTiles();

        //Send to view
        return $data;
    }

    /**
     * Create tiles from db array
     * @return array or null
     */
    public function getTiles()
    {
        $return = array();

        if ($tiles = get_field('modularity-tiles', $this->ID)) {
            if (!is_array($tiles)) {
                return null;
            }

            if (empty($tiles)) {
                return null;
            }

            foreach ($tiles as $tile) {
                $return[] = new \ModularityTiles\Tile($tile);
            }

            return $return;
        }

        return null;
    }

    public function template() : string
    {
        return "tiles.blade.php";
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
