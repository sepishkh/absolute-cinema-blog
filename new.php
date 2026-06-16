<!DOCTYPE html>

<!-- TODO: Fix intro being required rule -->

<?php 

$id = isset($_GET["edit"]) ? $_GET["edit"] : NULL;
if($id) {
    require_once "paths.php";
    require_once "sqldb.php";
    $sqldb = new SQLDB();
    $sqldb->StartDBConnection($DB_PATH, $SCHEMA_PATH);
    $stmt = $sqldb->pdo->prepare("SELECT * 
                                    FROM posts
                                    WHERE id=:id");
    $stmt->execute([":id" => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if($post == NULL) {
        require "404.php";
        exit;
    }
}

?>
 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $id ? "Edit" : "New" ?> Post</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header class="main-header">
            <?php require_once "header.php" ?>
        </header>
        <?php if(!IsLoggedIn()) : ?>
            <h1> Please <a href="login.php">Login</a> first.</h1>
        <?php exit(); ?>
        <?php endif ?>
        <p></p>
        <main class="content-wrapper">
            <div class="editor-container">
                
                <div class="editor-header">
                    <h1 class="page-title"><?= $id ? "Edit" : "Write a new" ?> Review</h1>
                    <p class="editor-subtitle">Share your thoughts on recent movies or television series with the community.</p>
                </div>
                <?php if (isset($_GET["status"])) : ?>
                    <div class="alert-box alert-danger">
                        <div class="alert-icon">⚠</div>
                        <div class="alert-content">
                            <p class="alert-title">Review Submission Failed</p>
                            <ul class="alert-list">
                                <li>Error Creating Post</li>
                            </ul>
                        </div>
                    </div>
                <?php endif ?>
                <form action="process-new.php<?= $id ? "?edit=$id" : "" ?>" method="POST" class="editor-form">
                    
                    <div class="form-group">
                        <label for="post_title">Review Title <span class="required-asterisk">*</span></label>
                        <input type="text" id="post_title" name="title" placeholder="e.g., The Batman (2022): A Gritty, Neo-Noir Masterpiece" value="<?= $id ? $post['title'] : '' ?>" required>
                    </div>

                    <div class="form-row-split">
                        <div class="form-group">
                            <label for="post_category">Category <span class="required-asterisk">*</span></label>
                            <div class="select-wrapper">
                                <select id="post_category" name="category_id" required>
                                    <option value="" disabled selected>Select a category...</option>
                                    <option value="0" <?= ($id && $post["category"] == 0) ? "selected" : "" ?>>Movie</option>
                                    <option value="1" <?= ($id && $post["category"] == 1) ? "selected" : "" ?>>TV Show</option>
                                    <option value="2" <?= ($id && $post["category"] == 2) ? "selected" : "" ?>>Theatre</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="post_intro">Short Intro <span class="required-asterisk">*</span></label>
                            <input type="text" id="post_intro" name="intro" placeholder="A brief one or two-sentence hook for the homepage card..." value="<?= $id ? $post['intro'] : '' ?>" required maxlength="200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="post_body">Article Body <span class="required-asterisk">*</span></label>
                        <textarea id="post_body" name="body" rows="15" placeholder="Write your full review breakdown here. Analyze the plot, cinematography, performances, and overall score..." required><?= $id ? $post['body'] : '' ?></textarea>
                    </div>

                    <div class="editor-actions">
                        <a href="profile.php" class="btn btn-secondary">Cancel & Discard</a>
                        <button type="submit" class="btn btn-primary publish-btn">Submit Review for Approval</button>
                    </div>

                </form>
            </div>
        </main>
        <footer class="main-footer">
            <?php require_once "footer.php" ?>
        </footer>
    </body>
</html>