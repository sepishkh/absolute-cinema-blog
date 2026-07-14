<h1 class="page-title">Latest Reviews</h1>
<div class="reviews-grid">
    <?= $posts ?>
</div>
<nav class="pagination-container" aria-label="Review Page Navigation">
    <a href="/home<?= $prev_page ?>" class="page-nav-btn">
        <span class="btn-arrow">&larr;</span> Previous
    </a>
    <a href="/home<?= $next_page ?>" class="page-nav-btn">
        Next <span class="btn-arrow">&rarr;</span>
    </a>
</nav>
