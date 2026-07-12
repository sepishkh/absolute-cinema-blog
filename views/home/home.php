<h1 class="page-title">Latest Reviews</h1>
<div class="reviews-grid">
    <?php /* while (($post = $TEMPLATE["posts"]->fetch())) :
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
    endwhile */?>
</div>
<nav class="pagination-container" aria-label="Review Page Navigation">
    <a href="?page={{prev_page}}" class="page-nav-btn">
        <span class="btn-arrow">&larr;</span> Previous
    </a>
    <a href="?page={{next_page}}" class="page-nav-btn">
        Next <span class="btn-arrow">&rarr;</span>
    </a>
</nav>
