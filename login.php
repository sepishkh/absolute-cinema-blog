<!DOCTYPE html>

<!-- TODO: Validate Email and Password as text -->

<?php

require_once "common.php";

$email_required_error = "Email address is required";
$password_required_error = "Password is required";

$email = $_POST['email'];
$pass = $_POST['password'];
$logged_in = Login($email, $pass);

?>

<html>

<head>
    <title> Login </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <?php require "header.php" ?>
    </header>

    <h2> Login </h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_HTML5, "UTF-8")?>" method="post">
        Email: 
            <input type="email" name="email">
            <span class="error">* <?php echo $email_required_error ?></span><br><br>
        Password: 
            <input type="password" name="password">
            <span class="error">* <?php echo $password_required_error ?></span><br><br>
        <input type="submit"><br>
        <?php if($logged_in) : ?>
            <span class="success"> Login Success </span><br>
        <?php else : ?>
            <span class="error">Login Failed</span><br>
        <?php endif ?>
    </form>

</body>

</html>