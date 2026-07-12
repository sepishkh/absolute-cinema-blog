<div class="auth-card">
    <div class="auth-header">
        <h1>Join AbsoluteCinema</h1>
        <p>Create an account to start reviewing your favorite movies and TV shows.</p>
    </div>

    <?php if (isset($_GET["status"])) : ?>
        <?php if ($_GET["status"] === "success") : ?>
            <div class="alert-box alert-success">
                <div class="alert-icon">✔</div>
                <div class="alert-content">
                    <p class="alert-title">Success!</p>
                    <p>Your account has been created. Go to <a href="<?= Paths::$LOGIN ?>">Login</a> page.</p>
                </div>
            </div>
        <?php elseif ($_GET["status"] == "23000") : ?>
            <div class="alert-box alert-danger">
                <div class="alert-icon">⚠</div>
                <div class="alert-content">
                    <p class="alert-title">Registration Failed</p>
                    <ul class="alert-list">
                        <li>Email Already registered</li>
                    </ul>
                </div>
            </div>
        <?php else : ?>
            <div class="alert-box alert-danger">
                <div class="alert-icon">⚠</div>
                <div class="alert-content">
                    <p class="alert-title">Registration Failed</p>
                    <ul class="alert-list">
                        <li>Error</li>
                    </ul>
                </div>
            </div>
        <?php endif ?>
    <?php endif ?>
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
        <p>Already have an account? <a href="<?= Paths::$LOGIN ?>">Log In instead</a></p>
    </div>
</div>
