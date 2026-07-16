<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Models\PostsModel;
use AbsCin\Models\UsersModel;
use AbsCin\Views\View;
use AbsCin\Utilz;

class ProfileController extends BaseController {
    private PostsModel $pm;
    private UsersModel $um;

    protected function Init() {
        $this->pm = new PostsModel($this->dbc);
        $this->um = new UsersModel($this->dbc);
    }

    public function Profile(): Response {
        /* <?php if (!IsLoggedIn()) : ?> */
        /*     <h1> Please <a href="<?= Paths::$LOGIN ?>">Login</a> first.</h1> */
        /*     <?php exit() ?> */
        /* <?php endif ?> */
        if (IsLoggedIn()) {
            $user = $this->um->GetUserByEmail(GetUsername())->fetch();
            $appr = $_POST["appr"] ?? 1;
            $posts = $this->pm->GetPosts(null, [$appr], $user["id"]);
            if ($user["role"] > 0) {
                $appr_admin = $_POST["appr_admin"] ?? 1;
                $panel = $this->pm->GetPosts(null, [$appr_admin]);
            }
        } else {
            return new Response("404");
        }
        $my_posts_html = "";
        foreach($my_posts as $post) {
            $card = new View(
                "partials/post-card",
                [
                    "intro_sw" => true,
                    "author_sw" => true,
                    "status_badge_sw" => true,
                    "post_actions_sw" => true,
                    "id" => $post["id"],
                    "category" => Utilz::GetCategory($post["category"]),
                    "status_badge" => Utilz::GetApproval($post["approval"]),
                    "status_badge_css" => $post["approval"],
                    "thumbnail" => Utilz::GetThumbnail($post["category"]),
                    "title" => Utilz::Escape($post["title"]),
                    "intro" => Utilz::Escape($post["intro"]),
                    "email" => Utilz::Escape($post["email"]),
                    "full_name" => Utilz::FullName($post["fname"], $post["lname"]),
                    "date" => $post["creation_date"],
                    "date_formatted" => Utilz::FormatDate($post["creation_date"]),
                ]
            );
            $my_posts_html .= $card->Render();
        }
        $view = new View(
            "layouts/main",
            [
                "title" => (IsLoggedIn() ? $user["fname"] . " " : ""),
                "content" => (new View(
                    "profile/profile",
                    [
                        "user" => [
                            "avatar" => substr($user["fname"], 0, 1),
                            "full_name" => Utilz::FullName($user["fname"], $user["lname"]),
                            "email" => $user["email"],
                            "role" => Utilz::GetRole($user["role"]),
                            "appr" => "",
                            "my_posts" => $my_posts_html,
                        ],
                        "my_posts_grid" => $my_posts_grid,
                        "user_posts_grid" => "",
                    ]
                ))->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }
}
