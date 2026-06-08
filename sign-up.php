<!DOCTYPE html>

<?php

require_once "common.php";

$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$email = $_POST['email'];
$pass = $_POST['password'];
$submit = $_POST['submit'];
$signed = NULL;

if($submit) {
    Logout();
    $signed = Signup($fname, $lname, $email, $pass);
}

?>

<html>

<head>
    <title> Sign Up </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <?php require "header.php" ?>
    </header>

    <h2> Sign up </h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_HTML5, "UTF-8") ?>" method="post">
        First Name:
            <input type="text" name="first_name">
            <?php if (IsError($fname, $submit)) : ?>
                <span class="error">*First Name is required</span>
            <?php endif ?>
            <p></p>
        Last Name:
            <input type="text" name="last_name">
            <span>Optional</span><p></p>
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
            <?php switch($signed) :
                case NULL: ?>
                    <span class="success">Sign up Successful. Go to <a href="login.php">Login</a> page</span>
            <?php   break;
                case false:  ?>
                    <span class="error">Sign up Failed</span>
            <?php   break;
                case 23000: ?>
                    <span class="error">Email already registered</span>
            <?php   break;
                default: ?>
                    <span class="error">Erorr</span>
            <?php endswitch ?>
        <?php endif ?>
    </form>

</body>

</html>