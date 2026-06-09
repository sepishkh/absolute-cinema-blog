<!DOCTYPE html>

<!-- TODO: Fix Variable may not be defined warning -->

<?php

require_once "utilz.php";

if(IsLoggedIn()) {
    require_once "sqldb.php";
    require_once "paths.php";

    $sqldb = new SQLDB();
    $sqldb->Connect($DB_PATH);

    $stmt = $sqldb->pdo->prepare("SELECT * 
                                    FROM users
                                    WHERE email=:email");

    $stmt->execute(array(":email" => GetUsername()));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $not_found = false;
    if($user == NULL) {
        $not_found = true;
    } else {
        $posts = $sqldb->pdo->prepare("SELECT *
                                        FROM posts
                                        WHERE author_id=:id");
        $posts->execute(array(":id" => $user['id']));
    }

}

?>

<html>
    <head>
        <title><?=(IsLoggedIn() ? $user['first_name'] : "")?> Profile</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <?php require_once "header.php" ?>
        </header>
        <?php if(!IsLoggedIn()) : ?>
            <h1> Please <a href="login.php">Login</a> first.</h1>
        <?php exit(); ?>
        <?php endif ?>
        <?php if($not_found) : ?>
            <h1> User not found </h1>
        <?php exit(); ?>
        <?php endif ?>

        <div class="header-align">
            <div>
                <h2> <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name'], ENT_HTML5, "UTF-8") ?> </h2>
                <h3> <?= htmlspecialchars($user['email'], ENT_HTML5, "UTF-8") ?></h3>
                <h4> Role: <?= GetRole(htmlspecialchars($user['role'], ENT_HTML5, "UTF-8")) ?> </h4>
            </div>
            <a href="new.php"> 
                <button type="button" href="new.php" class="create-post"> Create Post</button>
            </a>
        </div>

        <?php if($posts == NULL) : ?>
            <h4>No Posts Yet</h4>
        <?php else : ?>
            <main class="blog">
                <?php while(($row = $posts->fetch(PDO::FETCH_ASSOC))) : ?>
                    <article class="article-card">
                        <h2 class="article-title"><?php echo htmlspecialchars($row['title'], ENT_HTML5, "UTF-8") ?></h2>
                        <p class="article-body"><?php echo htmlspecialchars($row['intro'], ENT_HTML5, "UTF-8") ?></p>
                        <p class="author-email"> <?php echo htmlspecialchars($row['email'], ENT_HTML5, "UTF-8") ?></p>
                        <p class="article-date"> <?php 
                            $date = DateTime::createFromFormat("Y-m-d", $row['created_at']);
                            echo htmlspecialchars($date->format("d M Y"), ENT_HTML5, "UTF-8") 
                        ?></p>
                        <p> <a href="view.php?view=<?php echo $row['id'] ?>">Read More</a></p>
                    </article>
                <?php endwhile ?>
        <?php endif ?>
    </body>
</html>

