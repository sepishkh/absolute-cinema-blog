<!DOCTYPE html>

<!-- TODO: Validate Email and Password as text -->

<?php

require_once "utilz.php";

$email = $_POST['email'];
$pass = $_POST['password'];
$submit = $_POST['submit'];

$logged_in = Login($email, $pass);

?>

<html>

<head>
    <title> Login </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <?php require_once "header.php" ?>
    </header>

    <h2> Login </h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_HTML5, "UTF-8")?>" method="post">
        Email: 
            <input type="email" name="email">
            <?php if(IsError($email, $submit)) : ?>
                <span class="error">*Email address is required</span>
            <?php endif ?>
            <p></p>
        Password: 
            <input type="password" name="password">
            <?php if(IsError($pass, $submit)) : ?>
                <span class="error">*Password is required</span>
            <?php endif ?>
            <p></p>
        <input type="submit" name="submit"><br>
        <?php if($submit) : ?>
            <?php if($logged_in) : ?>
                <span class="success">Login Successful</span>
            <?php else : ?>
                <span class="error">Login Failed</span>
            <?php endif ?>
        <?php endif ?>
    </form>

</body>

</html>