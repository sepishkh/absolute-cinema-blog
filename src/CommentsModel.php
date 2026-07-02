<?php

require "../config/config.php";
require Paths::$BASIC_MODEL;

class CommentsModel extends BasicModel {
    public function GetComments($post_id, $appr_list) {
        $appr = "(" . implode(",", $appr_list) . ")";
        $cmd = "SELECT
            comments.id AS cid,
            comments.post_id AS pid,
            comments.author_id AS aid,
            comments.body,
            comments.creation_date,
            comments.approval,
            users.id AS uid,
            users.fname,
            users.lname,
            users.email
            FROM comments
            INNER JOIN users ON users.id = author_id
            WHERE comments.approval IN $appr AND post_id=:post_id
            ORDER BY comments.creation_date DESC";
        $stmt = $this->dbc->Connect()->prepare($cmd);
        $stmt->execute([
            ":post_id" => $post_id,
        ]);
        return $stmt;
    }
}
