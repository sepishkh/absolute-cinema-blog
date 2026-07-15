<div class="auth-card auth-container">
    <div class="auth-header">
        <h1>Join AbsoluteCinema</h1>
        <p>Create an account to start reviewing your favorite movies and TV shows.</p>
    </div>

    <?= $alert_box ?>
    <form action="<?= Paths::$ROUTE . "?action=signup" ?>" method="POST" class="auth-form">

        <div class="form-row-split">
            <div class="form-group">
                <label for="first_name">First Name <span class="required-asterisk">*</span></label>
                <input type="text" id="first_name" name="fname" placeholder="e.g. John" required autocomplete="given-name">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name <span class="optional-label">(Optional)</span></label>
                <input type="text" id="last_name" name="lname" placeholder="e.g. Doe" autocomplete="family-name">
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address <span class="required-asterisk">*</span></label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email">
        </div>

        <div class="form-group">
            <label for="password">Password <span class="required-asterisk">*</span></label>
            <input type="password" id="password" name="password" placeholder="Min. 8 characters" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary btn-block auth-submit-btn">Create Account</button>
    </form>

    <div class="auth-footer">
        <p>Already have an account? <a href="/login">Log In instead</a></p>
    </div>
</div>

