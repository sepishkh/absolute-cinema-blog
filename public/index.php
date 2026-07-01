<!DOCTYPE html>

<?php

require_once "../config/config.php";
require_once Paths::$POSTS_MODEL;
require_once Paths::$UTILZ;

if (isset($_GET["logout"])) Logout();

$per_page = 6;
$page_num = (int)($_GET["page"] ?? 1);
$offset = ($page_num - 1) * $per_page;

$sqldb = $GLOBALS["Sqldb"];
$pm = new PostsModel($sqldb);
$posts = $pm->GetPosts(null, [1], null, $per_page, $offset);

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Absolute Cinema </title>
    <link rel="stylesheet" href="<?= Paths::$CSS ?>">
</head>

<body>
    <header class="main-header">
        <?php require_once Paths::$HEADER ?>
        <!-- <img src="/abscin.jpg" alt="Absolute Cinema" class="header-banner"> -->
    </header>

    <main class="content-wrapper">
        <h1 class="page-title">Latest Reviews</h1>
        <div class="reviews-grid">
            <?php while (($post = $posts->fetch())) :
                $TEMPLATE_VALUES = [
                    "CATEGORY" => GetCategory($post["category"]),
                    "THUMBNAIL" => GetThumbnail($post["category"]),
                    "ID" => $post["id"],
                    "TITLE" => Escape($post["title"]),
                    "INTRO" => Escape($post["intro"]),
                    "EMAIL" => Escape($post["email"]),
                    "FULL_NAME" => FullName($post["fname"], $post["lname"]),
                    "DATE" => $post["creation_date"],
                    "DATE_FORMATTED" => FormatDate($post["creation_date"]),
                    "AUTHOR_SW" => false,
                    "INTRO_SW" => true
                ];
                require Paths::$POST_CARD_TEMPLATE;
            endwhile ?>
        </div>
        <nav class="pagination-container" aria-label="Review Page Navigation">
            <a href="?page=<?= max($page_num - 1, 1) ?>" class="page-nav-btn">
                <span class="btn-arrow">&larr;</span> Previous
            </a>
            <a href="?page=<?= $page_num + 1 ?>" class="page-nav-btn">
                Next <span class="btn-arrow">&rarr;</span>
            </a>
        </nav>
    </main>
    <footer class="main-footer">
        <?php require_once Paths::$FOOTER ?>
    </footer>
</body>

</html>
