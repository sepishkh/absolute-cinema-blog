<div class="auth-card auth-container">

    <div class="auth-header">
        <h1>Welcome Back</h1>
        <p>Log in to manage your movie reviews and join the community discussion.</p>
    </div>

    <?= $alert_box ?>

    <form method="POST" class="auth-form">
        <div class="form-group">
            <label for="login_email">Email Address <span class="required-asterisk">*</span></label>
            <input type="email" id="login_email" name="email"
                placeholder="you@example.com" autocomplete="email">
        </div>
        <div class="form-group">
            <div class="label-row">
                <label for="login_password">Password <span class="required-asterisk">*</span></label>
            </div>
            <input type="password" id="login_password" name="password"
                placeholder="Enter your account password" autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary btn-block auth-submit-btn">Log In</button>
    </form>

    <div class="auth-footer">
        <p>New to the platform? <a href="/signup">Create an account instead</a></p>
    </div>

</div>
