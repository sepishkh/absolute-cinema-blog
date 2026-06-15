<!DOCTYPE html>

<!-- TODO: Fix Variable may not be defined warning -->

<?php

require_once "utilz.php";

if(IsLoggedIn()) {
    require_once "sqldb.php";
    require_once "paths.php";

    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);

    $stmt = $sqldb->pdo->prepare("SELECT * 
                                    FROM users
                                    WHERE email=:email");

    $stmt->execute(array(":email" => GetUsername()));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $not_found = false;
    if($user == NULL) {
        $not_found = true;
    } else {
        $approval = 1;
        if(isset($_POST["approval_select"])) {
            $approval = $_POST["approval_select"];
        }
        $posts = $sqldb->pdo->prepare("SELECT *
                                        FROM posts
                                        WHERE author_id=:id AND approval=:approval");
        $posts->execute(array(
            ":id" => $user["id"],
            ":approval" => $approval
        ));
        if($user["role"] > 0) {
            $approval_panel = 1;
            if(isset($_POST["approval_select_panel"])) {
                $approval_panel = $_POST["approval_select_panel"];
            }
            $panel = $sqldb->pdo->prepare("SELECT
                                        posts.id AS post_id,
                                        posts.title, 
                                        posts.intro, 
                                        posts.created_at,
                                        posts.approval,
                                        users.first_name,
                                        users.last_name,
                                        users.email
                                        FROM posts
                                        INNER JOIN users ON posts.author_id = users.id
                                        WHERE approval=:approval");
            $panel->execute([":approval" => $approval_panel]);
        }
    }
}

?>

<html>
    <head>
        <title><?=(IsLoggedIn() ? $user["first_name"] : "")?> Profile</title>
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
                <h2> <?= htmlspecialchars($user["first_name"] . " " . $user["last_name"], ENT_HTML5, "UTF-8") ?> </h2>
                <h3> <?= htmlspecialchars($user["email"], ENT_HTML5, "UTF-8") ?></h3>
                <h4> Role: <?= GetRole(htmlspecialchars($user["role"], ENT_HTML5, "UTF-8")) ?> </h4>
            </div>
            <a href="new.php"> 
                <button type="button" href="new.php" class="create-post"> Create Post</button>
            </a>
        </div>

        <?php if($posts == NULL) : ?>
            <h4>No Posts Yet</h4>
        <?php else : ?>
            <h2>My Posts</h2>
            <form action="profile.php" method="POST">
                <select name="approval_select" required>
                    <option value="" disabled selected>Select...</option>
                    <option value="-1">Disapproved</option>
                    <option value="0">Pending</option>
                    <option value="1">Approved</option>
                </select>
                <button type="submit">Submit Choice</button>
            </form>
            <div class="blog">
                <?php while(($row = $posts->fetch(PDO::FETCH_ASSOC))) : ?>
                    <article class="article-card">
                        <h2 class="article-title"><?php echo htmlspecialchars($row["title"], ENT_HTML5, "UTF-8") ?></h2>
                        <p class="article-body"><?php echo htmlspecialchars($row["intro"], ENT_HTML5, "UTF-8") ?></p>
                        <p class="article-date"> <?php 
                            $date = DateTime::createFromFormat("Y-m-d", $row["created_at"]);
                            echo htmlspecialchars($date->format("d M Y"), ENT_HTML5, "UTF-8") 
                        ?></p>
                        <p> <a href="view.php?view=<?php echo $row["id"] ?>">Read More</a></p>
                        <p> <span class="<?= strtolower(GetApproval($row["approval"])) ?>"><?= GetApproval($row["approval"]) ?></span></p>
                    </article>
                <?php endwhile ?>
            </div>
            <br>
            <?php if($user["role"] > 0) : ?>
                <h2>Admin Panel</h2>
                <form action="profile.php" method="POST">
                    <select name="approval_select_panel" required>
                        <option value="" disabled selected>Select...</option>
                        <option value="-1">Disapproved</option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
                    </select>
                    <button type="submit">Submit Choice</button>
                </form>
                <div class="blog">
                    <?php while(($row = $panel->fetch(PDO::FETCH_ASSOC))) : ?>
                        <article class="article-card">
                            <h2 class="article-title"><?php echo htmlspecialchars($row["title"], ENT_HTML5, "UTF-8") ?></h2>
                            <p class="article-body"><?php echo htmlspecialchars($row["intro"], ENT_HTML5, "UTF-8") ?></p>
                            <p class="article-author"> <?php echo htmlspecialchars($row['first_name'], ENT_HTML5, "UTF-8") .' '. htmlspecialchars($row['last_name'], ENT_HTML5, "UTF-8")?></p>
                            <p class="author-email"> <?php echo htmlspecialchars($row["email"], ENT_HTML5, "UTF-8") ?></p>
                            <p class="article-date"> <?php 
                                $date = DateTime::createFromFormat("Y-m-d", $row["created_at"]);
                                echo htmlspecialchars($date->format("d M Y"), ENT_HTML5, "UTF-8") 
                            ?></p>
                            <p> <a href="view.php?view=<?php echo $row["post_id"] ?>">Read More</a></p>
                            <p> <span class="<?= strtolower(GetApproval($row["approval"])) ?>"><?= GetApproval($row["approval"]) ?></span></p>
                        </article>
                    <?php endwhile ?>
                </div>
                <p></p>
            <?php endif ?>
        <?php endif ?>
    </body>
</html>