<?php

require_once "../config/config.php";
require_once Paths::$SQLDB;
require_once Paths::$BASIC_MODEL;

class PostsModel extends BasicModel{
    public function GetPosts($id = null, $appr_list = [-1, 0, 1], $author_id = null, $limit = null, $offset = null) {
        if($id != null) $id = "=" . $id;
        $appr = "(" . implode(",", $appr_list) . ")";
        $pagin = "";
        if($author_id !== null) $author_id = "=" . $author_id;
        if($limit !== null && $offset !== null) $pagin = "LIMIT $limit OFFSET $offset";
        $cmd = "SELECT *
            FROM posts
            WHERE hidden IS NULL AND id$id AND approval IN $appr AND author_id$author_id
            $pagin";
        /* var_dump($cmd); */
        $stmt = $this->db->Connect()->prepare($cmd);
        $stmt->execute();
        return $stmt;
    }

    public function SetApproval($id, $appr) {
        $cmd = "UPDATE posts
            SET approval=:appr
            WHERE id=:id";
        $stmt = $this->db->Connect()->prepare($cmd);
        $stmt->execute([
            ":appr" => $appr,
            ":id" => $id,
        ]);
    }

    public function HidePost($id) {
        $cmd = "UPDATE posts
            SET hidden=1
            WHERE id=:id";
        $stmt = $this->db->Connect()->prepare($cmd);
        $stmt->execute([
            ":id" => $id,
        ]);
    }
    public function InsertPost($title, $intro, $body, $author_id, $creation_date, $appr, $category_id) {
        $cmd = "INSERT 
            INTO posts
            (title, intro, body, author_id, creation_date, approval, category)
            VALUES (:title, :intro, :body, :author_id, :creation_date, :approval, :category_id)";
        $stmt = $this->db->Connect()->prepare($cmd);
        try {
            $stmt->execute([
                ":title" => $title,
                ":intro" => $intro,
                ":body" => $body,
                ":author_id" => $author_id,
                ":creation_date" => $creation_date,
                ":approval" => $appr,
                ":category_id" => $category_id,
            ]);
        } catch (PDOException $e) {
            return [$e->getCode(), 0];
        }
        return [$stmt->errorCode(), $this->db->Connect()->lastInsertId()];
    }

    public function UpdatePost($id, $title, $intro, $body, $category_id) {
        $cmd = "UPDATE posts
            SET title=:title,
                intro=:intro,
                body=:body,
                category=:category
            WHERE id=:id";
        $stmt = $this->db->Connect()->prepare($cmd);
        try {
            $stmt->execute([
                ":title" => $title,
                ":intro" => $intro,
                ":body" => $body,
                ":category" => $category_id,
                ":id" => $id,
            ]);
        } catch (PDOException $e) {
            return $e->getCode();
        }
        return $stmt->errorCode();
    }
}























