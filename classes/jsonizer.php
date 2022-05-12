<?php
/**
 *Helper functions for transmitting database infomation via JSON from the SLC
 * MySQL database back to the desktop app.
 *
 * @author JN
 */

// NOTE:  "Completed" is status code 30   !!!!    Some SQL will have to be updated if that ever changes!


class Jsonizer {

    private $link;
    public function Jsonizer($link) {
        $this->link = $link;
    }
     
    
}