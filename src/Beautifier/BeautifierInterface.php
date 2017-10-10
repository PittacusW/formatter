<?php

namespace Contal\Beautifier;

interface BeautifierInterface {
    /**
    * Process the file(s) or string
    */
    public function process();
    /**
    * Show on screen the output
    */
    public function show();
    /**
    * Get the output on a string
    * @return string
    */
    public function get();
    /**
    * Save the output to a file
    * @param string path to file
    */
    public function save($sFile = null);
}
