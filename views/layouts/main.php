<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{title}} </title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <header class="main-header">
        <?php require ROOT_PATH . "/views/partials/header.php" ?>
        <!-- <img src="/images/abscin.jpg" alt="Absolute Cinema" class="header-banner"> -->
    </header>

    <main class="content-wrapper">
        {{content}}
    </main>

    <footer class="main-footer">
        <?php require ROOT_PATH . "/views/partials/footer.php" ?>
    </footer>
</body>

</html>
