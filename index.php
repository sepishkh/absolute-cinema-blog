<!DOCTYPE html>

<!-- TODO: Robust Login/Logout system -->
<!-- TODO: Proper Error Handling -->
<!-- TODO: Better UI -->
<!-- TODO: Think about UX -->

<?php

require_once "utilz.php";

if(isset($_GET["logout"])) {
    Logout();
}

require_once "paths.php";
require_once "sqldb.php";

$sqldb = new SQLDB();
$sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

$res = $sqldb->pdo->query("SELECT COUNT(*) FROM posts");
$post_count = $res->fetchColumn();

$res = $sqldb->pdo->query("SELECT
                                posts.id AS post_id,
                                posts.title, 
                                posts.intro, 
                                posts.body, 
                                posts.created_at, 
                                users.first_name,
                                users.last_name,
                                users.email
                            FROM posts
                            INNER JOIN users ON posts.author_id = users.id
                            WHERE approval=1");

?>


<html>

<head>
    <title> Absolute Cinema </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <?php require_once 'header.php' ?>
        <img src="/abscin.jpg" alt="Absolute Cinema" class="header-banner">
    </header>

    <main class="blog">
        <?php while(($row = $res->fetch(PDO::FETCH_ASSOC))) : ?>
            <article class="article-card">
                <h2 class="article-title"><?php echo htmlspecialchars($row['title'], ENT_HTML5, "UTF-8") ?></h2>
                <p class="article-body"><?php echo htmlspecialchars($row['intro'], ENT_HTML5, "UTF-8") ?></p>
                <p class="article-author"> <?php echo htmlspecialchars($row['first_name'], ENT_HTML5, "UTF-8") .' '. htmlspecialchars($row['last_name'], ENT_HTML5, "UTF-8")?></p>
                <p class="author-email"> <?php echo htmlspecialchars($row['email'], ENT_HTML5, "UTF-8") ?></p>
                <p class="article-date"> <?php 
                    $date = DateTime::createFromFormat("Y-m-d", $row['created_at']);
                    echo htmlspecialchars($date->format("d M Y"), ENT_HTML5, "UTF-8") 
                ?></p>
                <p> <a href="view.php?view=<?php echo $row['post_id'] ?>">Read More</a></p>
            </article>
        <?php endwhile ?>

        <h3>Categories</h3>
        <ul>
            <li>Movies</li>
            <li>TV Shows</li>
            <li>Upcoming</li>
        </ul>
    </main>
</body>

</html>