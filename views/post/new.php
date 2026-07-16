<div class="content-wrapper">
    <div class="editor-container">

        <div class="editor-header">
            <h1 class="page-title">Edit Review</h1>
            <p class="editor-subtitle">Share your thoughts on recent movies or television series with the community.</p>
        </div>

        <?= $alert_box ?>

        <form action="" method="POST" class="editor-form">

            <div class="form-group">
                <label for="post_title">Review Title <span class="required-asterisk">*</span></label>
                <input type="text" name="title" placeholder="e.g., The Batman (2022): A Gritty, Neo-Noir Masterpiece" value="<?= $post["title"] ?>" required>
            </div>

            <div class="form-row-split">
                <div class="form-group">
                    <label for="post_category">Category <span class="required-asterisk">*</span></label>
                    <div class="select-wrapper">
                        <select id="post_category" name="category_id" required>
                            <option value="" disabled selected>Select a category...</option>
                            <option value="0" <?= ($category === 0) ? "selected" : "" ?>>Movie</option>
                            <option value="1" <?= ($category === 1) ? "selected" : "" ?>>TV Show</option>
                            <option value="2" <?= ($category === 2) ? "selected" : "" ?>>Theatre</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="post_intro">Short Intro <span class="required-asterisk">*</span></label>
                    <input type="text" id="post_intro" name="intro" placeholder="A brief one or two-sentence hook for the homepage card..." value="<?= $post["intro"] ?>" required maxlength="200">
                </div>
            </div>

            <div class="form-group">
                <label for="post_body">Article Body <span class="required-asterisk">*</span></label>
                <textarea id="post_body" name="body" rows="15" placeholder="Write your full review breakdown here. Analyze the plot, cinematography, performances, and overall score..." required><?= $post["body"] ?></textarea>
            </div>

            <div class="editor-actions">
                <a href="/profile" class="btn btn-secondary">Cancel & Discard</a>
                <button type="submit" class="btn btn-primary publish-btn">Submit Review for Approval</button>
            </div>

        </form>
    </div>
</div>
