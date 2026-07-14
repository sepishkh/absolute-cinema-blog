<?php

namespace AbsCin\Models;

class CommentsModel extends BaseModel {
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
        return $stmt->fetchAll;
    }

    public function SetApproval($id, $appr) {
        $cmd = "UPDATE comments
            SET approval=:appr
            WHERE id=:id";
        $stmt = $this->dbc->Connect()->prepare($cmd);
        $stmt->execute([
            ":appr" => (int)$appr,
            ":id" => (int)$id
        ]);
    }

    public function InsertComment($body, $post_id, $author_id, $creation_date, $appr) {
        $cmd = "INSERT INTO comments
            (post_id, author_id, body, creation_date, approval)
            VALUES (:pid, :aid, :body, :creation_date, :approval)";
        $stmt = $this->dbc->Connect()->prepare($cmd);
        $stmt->execute([
            ":pid" => $post_id,
            ":aid" => $author_id,
            ":body" => $body,
            ":creation_date" => $creation_date,
            ":approval" => $appr,
        ]);
        return [$stmt->errorCode(), $this->dbc->Connect()->lastInsertId()];
    }
}
