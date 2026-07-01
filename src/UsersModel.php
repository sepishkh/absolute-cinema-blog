<?php

require_once "../config/config.php";
require_once Paths::$SQLDB;
require_once Paths::$BASIC_MODEL;

class UsersModel extends BasicModel {
    public function GetUserByEmail($email) {
        $cmd = "SELECT *
            FROM users
            WHERE email=:email";
        $user_stmt = $this->db->Connect()->prepare($cmd);
        $user_stmt->execute([
            ":email" => $email,
        ]);
        return $user_stmt;
    }
}
