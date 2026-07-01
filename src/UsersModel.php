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

    public function InsertUser($fname, $lname, $email, $pass, $role, $creation_date) {
        $cmd = "INSERT INTO users 
            (fname, lname, email, password, role, creation_date) 
            VALUES (:fname, :lname, :email, :pass, :role, :creation_date)";
        $stmt = $this->db->Connect()->prepare($cmd);
        try {
        $stmt->execute([
            ":fname" => $fname,
            ":lname" => $lname,
            ":email" => $email,
            ":pass" => $pass,
            ":role" => $role,
            ":creation_date" => $creation_date,
        ]);
        } catch (PDOException $e) {
            return [$e->getCode(), 0];
        }
        return [$stmt->errorCode(), $this->db->Connect()->lastInsertId()];
    }
}
