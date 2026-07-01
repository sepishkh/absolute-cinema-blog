<?php

require_once "../config/config.php";
require_once Paths::$SQLDB;

class BasicModel {
    protected SQLDB $db;
    public function __construct($sqldb) {
        $this->db = $sqldb;
    }
}
