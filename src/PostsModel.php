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
        $posts_stmt = $this->db->Connect()->prepare($cmd);
        $posts_stmt->execute();
        return $posts_stmt;
    }
}
