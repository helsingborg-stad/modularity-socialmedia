<?php

namespace ModularitySocialMedia;

class Controller
{
    public $args = array();

    protected $feedData = array();
    protected $markup = '';

    public $curl;

    public function __construct()
    {
        $this->curl = new \Modularity\Helper\Curl();
    }

    public function registerError($errorMessage)
    {
        error_log($errorMessage);
    }

    public function readableTimeStamp($unixtime)
    {
        return human_time_diff($unixtime, current_time('timestamp'));
    }
}
