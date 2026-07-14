<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Http\Request;
use AbsCin\Views\View;
use AbsCin\Models\PostsModel;
use AbsCin\Database\DBConnection;
use AbsCin\Utilz;

class HomeController extends BaseController {
    private PostsModel $pm;

    public function __construct(
        private Request $request,
    ) {
        $dbc = DBConnection::GetInstance();
        $this->pm = new PostsModel($dbc);
    }

    public function Index(): Response {
        // TODO: Better redirect instead of auto fixing
        $limit = max(1, (int)($this->request->GetKey("limit") ?? 6));
        $page = max(1, (int)($this->request->GetKey("page") ?? 1));
        $offset = ($page - 1) * $limit;

        $posts = $this->pm->GetPosts(null, [1], null, $limit, $offset);
        $articles = "";
        foreach($posts as $post) {
            $card = new View(
                "partials/post-card", 
                [
                    "category" => Utilz::GetCategory($post["category"]),
                    /* "status_badge_sw" => false, */
                    /* "status_badge" => "", */
                    "thumbnail" => Utilz::GetThumbnail($post["category"]),
                    "id" => $post["id"],
                    "title" => Utilz::Escape($post["title"]),
                    "intro_sw" => true,
                    "intro" => Utilz::Escape($post["intro"]),
                    /* "author_sw" => false, */
                    /* "email" => "", */
                    /* "full_name" => "", */
                    "date" => $post["creation_date"],
                    "date_formatted" => $post["creation_date"],
                    "post_actions_sw" => false,
                ]
            );
            $articles .= $card->Render();
        }
        $get = Utilz::UpdateIfExists($this->request->Get(), "limit", $limit);
        $prev_get = array_replace($get, ["page" => max(1, $page - 1)]);
        $next_get = array_replace($get, ["page" => $page + 1]);
        $response = $this->Execute(
            "layouts/main", 
            [
                "title" => "AbsoluteCinema Blog",
                "content" => (new View(
                    "home/home", 
                    [
                        "next_page" => "?" . http_build_query($next_get),
                        "prev_page" => "?" . http_build_query($prev_get),
                        "posts" => $articles,
                    ]
                ))->Render(),
            ]
        );
        return $response;
    }
}
