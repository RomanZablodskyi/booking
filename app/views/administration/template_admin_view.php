<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$data['title']?></title>
    <script src="/web/scripts/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="/web/styles/astyles.css">
    <link rel="shortcut icon" href="/favicon.ico" type="image/png">
    <script src="/web/scripts/jquery-3.2.1.min.js"></script>
</head>
<body>
    <? if (isset($_SESSION['status'])):?>
        <a href="/suser/logout">Вихід</a>
    <? endif?>
    <? require_once $content_view ?>
</body>
</html>