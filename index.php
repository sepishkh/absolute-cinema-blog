<!DOCTYPE html>

<!-- TODO: Robust Login/Logout system -->
<!-- TODO: Proper Error Handling -->

<?php

require_once "utilz.php";

if (isset($_GET["logout"])) {
    Logout();
}

require_once "paths.php";
require_once "sqldb.php";
$sqldb = new SQLDB();
$sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

$page_count = 8;
$page_num = 1;
if (isset($_GET["page"])) {
    $page_num = max((int)$_GET["page"], 1);
}
$offset = ($page_num - 1) * $page_count;

$res = $sqldb->pdo->prepare("SELECT
                                posts.id AS post_id,
                                posts.title, 
                                posts.intro, 
                                posts.body, 
                                posts.creation_date,
                                posts.category,
                                users.fname,
                                users.lname,
                                users.email
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE approval=1 AND hidden IS NULL
                            ORDER BY posts.creation_date DESC
                            LIMIT :limit OFFSET :offset");

$res->execute([
    ":limit" => $page_count,
    ":offset" => $offset
])

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Absolute Cinema </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="main-header">
        <?php require_once "header.php" ?>
        <!-- <img src="/abscin.jpg" alt="Absolute Cinema" class="header-banner"> -->
    </header>

    <main class="content-wrapper">
        <h1 class="page-title">Latest Reviews</h1>
        <div class="reviews-grid">
            <?php while (($post = $res->fetch(PDO::FETCH_ASSOC))) : ?>
                <?php
                $TEMPLATE_VALUES = [
                    "CATEGORY" => GetCategory($post["category"]),
                    "THUMBNAIL" => GetThumbnail($post["category"]),
                    "ID" => $post["post_id"],
                    "TITLE" => Escape($post["title"]),
                    "INTRO" => Escape($post["intro"]),
                    "EMAIL" => Escape($post["email"]),
                    "FULL_NAME" => FullName($post["fname"], $post["lname"]),
                    "DATE" => $post["creation_date"],
                    "DATE_FORMATTED" => FormatDate($post["creation_date"]),
                    "AUTHOR_SW" => true,
                    "INTRO_SW" => true
                ];
                require "post-card-template.php";
                ?>
            <?php endwhile ?>
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
        <?php require_once "footer.php" ?>
    </footer>
</body>

</html>