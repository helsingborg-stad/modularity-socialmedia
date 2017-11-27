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
        $this->curl = new \Modularity\Helper\Curl(true, 60);
    }

    /**
     * Register error in log
     * @param string $errorMessage A written error.
     * @return void
     */

    public function registerError($errorMessage)
    {
        $trace = debug_backtrace();
        if (isset($trace[1])) {
            $errorInClass = $trace[1]['class'];
            $errorInFunction = $trace[1]['function'];
        } else {
            $errorInClass = "";
            $errorInFunction = "";
        }

        error_log($errorMessage . 'in' . $errorInClass . '->' . $errorInFunction);
    }

    /**
     * Format a unix timestamp to a human friendly format
     * @param string $unixtime The timestamp in unixtime format
     * @return string BHumean readable time
     */

    public function readableTimeStamp($unixtime)
    {
        return human_time_diff($unixtime, current_time('timestamp'));
    }
}
