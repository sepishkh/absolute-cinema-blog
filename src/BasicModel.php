<?php

require_once "../config/config.php";
require_once Paths::$DBCONNECTION;

class BasicModel {
    protected DBConnection $dbc;
    public function __construct($dbc) {
        $this->dbc = $dbc;
    }
}
