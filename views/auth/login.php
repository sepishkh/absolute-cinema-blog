<div class="auth-card">

    <div class="auth-header">
        <h1>Welcome Back</h1>
        <p>Log in to manage your movie reviews and join the community discussion.</p>
    </div>

    <?php if (isset($_GET["status"])) : ?>
        <div class="alert-box alert-danger">
            <div class="alert-icon">⚠</div>
            <div class="alert-content">
                <p class="alert-title">Login Failed</p>
            </div>
        </div>
    <?php endif; ?>
    <form action="<?= Paths::$ROUTE . "?action=login" ?>" method="POST" class="auth-form">
        <div class="form-group">
            <label for="login_email">Email Address <span class="required-asterisk">*</span></label>
            <input type="email" id="login_email" name="email"
                placeholder="you@example.com" required autocomplete="email">
        </div>
        <div class="form-group">
            <div class="label-row">
                <label for="login_password">Password <span class="required-asterisk">*</span></label>
            </div>
            <input type="password" id="login_password" name="password"
                placeholder="Enter your account password" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary btn-block auth-submit-btn">Log In</button>
    </form>

    <div class="auth-footer">
        <p>New to the platform? <a href="<?= Paths::$SIGNUP ?>">Create an account instead</a></p>
    </div>

</div>
