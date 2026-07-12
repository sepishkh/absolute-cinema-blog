<?php

namespace AbsCin\Models;

use AbsCin\Database\DBConnection;

class BaseModel {
    protected DBConnection $dbc;
    public function __construct($dbc) {
        $this->dbc = $dbc;
    }
}