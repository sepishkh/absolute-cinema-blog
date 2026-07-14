<?php

namespace AbsCin\Models;

use PDOException;

class UsersModel extends BaseModel {
    public function GetUserByEmail($email) {
        $cmd = "SELECT *
            FROM users
            WHERE email=:email";
        $user_stmt = $this->dbc->Connect()->prepare($cmd);
        $user_stmt->execute([
            ":email" => $email,
        ]);
        return $user_stmt->fetchAll();
    }

    public function GetUserById($id) {
        $cmd = "SELECT *
            FROM users
            WHERE id=:id";
        $user_stmt = $this->dbc->Connect()->prepare($cmd);
        $user_stmt->execute([
            ":id" => $id,
        ]);
        return $user_stmt->fetchAll();
    }

    public function InsertUser($fname, $lname, $email, $pass, $role, $creation_date) {
        $cmd = "INSERT INTO users 
            (fname, lname, email, password, role, creation_date) 
            VALUES (:fname, :lname, :email, :pass, :role, :creation_date)";
        $stmt = $this->dbc->Connect()->prepare($cmd);
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
        return [$stmt->errorCode(), $this->dbc->Connect()->lastInsertId()];
    }
}
