<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Models\PostsModel;
use AbsCin\Models\UsersModel;
use AbsCin\Models\CommentsModel;
use AbsCin\Views\View;
use AbsCin\Utilz;

class PostController extends BaseController{
    private PostsModel $pm;
    private UsersModel $um;
    private CommentsModel $cm;

    protected function Init() {
        $this->pm = new PostsModel($this->dbc);
        $this->um = new UsersModel($this->dbc);
        $this->cm = new CommentsModel($this->dbc);
    }

    public function View(): Response {
        $post_id = (int)$this->request->GetKey("id");
        $post = $this->pm->GetPosts($post_id)[0];
        $author = $this->um->GetUserById($post["author_id"])[0];
        $user = $this->um->GetUserByEmail(GetUsername())[0];

        // TODO: Implement Error Checking
        if (
            $author["email"] != GetUsername()
                && $post["approval"] != 1
                && $user["role"] == 0
        ) {
            // TODO: 404
            exit("404");
        }

        /* if ( */
        /*     $_GET["delete"] === "true" */ 
        /*         && ($user["role"] > 0 */ 
        /*             || $post["email"] == GetUsername())) { */
        /*     $pm->HidePost($post_id); */
        /*     header("Location: " . Paths::$INDEX); */
        /*     exit(); */
        /* } */

        /* if (NotEmpty($_GET["approved"]) && $user["role"] > 0) { */
        /*     $pm->SetApproval((int)$post_id, (int)$_GET["approved"]); */
        /* } */

        $cmnts = $this->cm->GetComments($post_id, ($user["role"] > 0 ? [0, 1] : [1]));
        $cmnts_html = "";
        foreach($cmnts as $cmnt) {
            $cmnts_html .= (new View(
                "partials/comment-card",
                [
                    "avatar" => substr($cmnt["fname"], 0, 1), 
                    "full_name" => Utilz::FullName($cmnt["fname"], $user["lname"]),
                    "date_formatted" => Utilz::FormatDate($cmnt["creation_date"]),
                    "body" => $cmnt["body"],
                    "id" => $cmnt["cid"],
                    "post_id" => $post_id,
                    "user_role" => $user["role"],
                    "approval" => $cmnt["approval"],
                ]
            ))->Render();
        }

        $view = new View(
            "layouts/main",
            [
                "title" => Utilz::Escape($post["title"]),
                "content" => (new View(
                    "post/view", 
                    [
                        "post" => [
                            "id" => $post_id,
                            "category" => Utilz::GetCategory($post["category"]),
                            "title" => Utilz::Escape($post["title"]),
                            "intro" => Utilz::Escape($post["intro"]), 
                            "date" => $post["creation_date"],
                            "date_formatted" => Utilz::FormatDate($post["creation_date"]),
                            "body" => Utilz::Escape($post["body"]),
                            "thumbnail" => Utilz::GetThumbnail($post["category"]),
                        ],
                        "author" => [
                            "avatar" => substr($author["fname"], 0, 1),
                            "full_name" => Utilz::FullName($author["fname"], $author["lname"]),
                            "email" => $author["email"],
                        ],
                        "user" => [
                            "id" => $user["id"],
                            "role" => $user["role"],
                            "avatar" => substr($user["fname"], 0, 1),
                            "logged_in" => IsLoggedIn(),
                        ],
                        "comments" => $cmnts_html, 
                    ])
                )->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }

    public function New(): Response {
        $status = $this->request->GetKey("status");
        $alert_info = match($status) {
            default => ["danger", "⚠", "Review Submission Failed", "Error Creating Post"],
        };
        $view = new View(
            "layouts/main",
            [
                "title" => "New Post",
                "content" => (new View(
                    "post/new",
                    [
                        "alert_box" => ($status == null) ? "" : (new View(
                            "partials/alert-box",
                            [
                                "result" => $alert_info[0],
                                "icon" => $alert_info[1],
                                "status" => $alert_info[2],
                                "message" => $alert_info[3],
                            ]
                        ))->Render(),
                    ]
                ))->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }

    public function Edit(): Response {
        $status = $this->request->GetKey("status");
        $alert_info = match($status) {
            default => ["danger", "⚠", "Review Submission Failed", "Error Creating Post"],
        };
        $post_id = (int)$this->request->GetKey("id");
        $post = $this->pm->GetPosts($post_id)[0];
        $view = new View(
            "layouts/main",
            [
                "title" => "New Post",
                "content" => (new View(
                    "post/new",
                    [
                        "alert_box" => ($status == null) ? "" : (new View(
                            "partials/alert-box",
                            [
                                "result" => $alert_info[0],
                                "icon" => $alert_info[1],
                                "status" => $alert_info[2],
                                "message" => $alert_info[3],
                            ]
                        ))->Render(),
                        "post" => [
                            "title" => Utilz::Escape($post["title"]),
                            "intro" => Utilz::Escape($post["intro"]), 
                            "body" => Utilz::Escape($post["body"]),
                            "category" => $post["category"],
                            /* "id" => $post_id, */
                            /* "date" => $post["creation_date"], */
                            /* "date_formatted" => Utilz::FormatDate($post["creation_date"]), */
                            /* "thumbnail" => Utilz::GetThumbnail($post["category"]), */
                        ],
                    ]
                ))->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }

    public function Categories(): Response {

    }
}
