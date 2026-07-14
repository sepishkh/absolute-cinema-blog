<?php

namespace AbsCin\Models;

use AbsCin\Database\DBConnection;

class BaseModel {
    public function __construct(
        protected DBConnection $dbc,
    ) {}
}
