<!DOCTYPE html>

<!-- TODO: Fix intro being required rule -->
 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>New Post</title>
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
                    <h1 class="page-title">Write a New Review</h1>
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
                <form action="process-new.php" method="POST" class="editor-form">
                    
                    <div class="form-group">
                        <label for="post_title">Review Title <span class="required-asterisk">*</span></label>
                        <input type="text" id="post_title" name="title" placeholder="e.g., The Batman (2022): A Gritty, Neo-Noir Masterpiece" required>
                    </div>

                    <div class="form-row-split">
                        <div class="form-group">
                            <label for="post_category">Category <span class="required-asterisk">*</span></label>
                            <div class="select-wrapper">
                                <select id="post_category" name="category_id" required>
                                    <option value="" disabled selected>Select a category...</option>
                                    <option value="0">Movie</option>
                                    <option value="1">TV Show</option>
                                    <option value="2">Theatre</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="post_intro">Short Intro <span class="required-asterisk">*</span></label>
                            <input type="text" id="post_intro" name="intro" placeholder="A brief one or two-sentence hook for the homepage card..." required maxlength="200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="post_body">Article Body <span class="required-asterisk">*</span></label>
                        <textarea id="post_body" name="body" rows="15" placeholder="Write your full review breakdown here. Analyze the plot, cinematography, performances, and overall score..." required></textarea>
                    </div>

                    <div class="editor-actions">
                        <a href="profile.php" class="btn btn-secondary">Cancel & Discard</a>
                        <button type="submit" class="btn btn-primary publish-btn">Submit Review for Approval</button>
                    </div>

                </form>
            </div>
        </main>
    </body>
</html>