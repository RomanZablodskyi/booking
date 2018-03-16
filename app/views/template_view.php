<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$data['title']?></title>
    <script src="/web/scripts/jquery-3.2.1.min.js"></script>
    <link href="/web/styles/datepicker.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/web/styles/styles.css">
    <? if(isset($data['styles']))
        foreach ($data['styles'] as $styles):?>
            <link rel="stylesheet" href="/web/styles/<?=$styles?>">
    <? endforeach;?>
    <link rel="shortcut icon" href="/favicon.ico" type="image/png">
</head>
<body>
    <header>
        <section class="wrapper">
            <a href="/" class="site-logo">
                <img src="/web/images/siteLogo.png" alt="site logo">
            </a>
            <section class="user-actions">
                <? if(isset($_SESSION['user'])): ?>
                    <a href="/user/profile" name="register">Профіль</a>
                    <a href="/user/logout" name="enter">Вийти</a>
                <? else:?>
                    <a href="/user/registration" name="register">Регістрація</a>
                    <a href="/user/auth" name="enter">Увійти</a>
                <? endif;?>
            </section>
        </section>
    </header>

    <? include_once $content_view?>

    <footer>
        <section class="more-info">
            <section class="wrapper">
                <section class="social">
                    <span>Ми в соцмережах</span>
                    <ul class="social-links">
                        <li><a href="https://www.facebook.com/" class="facebook"></a></li>
                        <li><a href="https://twitter.com/" class="twitter"></a></li>
                        <li><a href="#" class="vk"></a></li>
                        <li><a href="#" class="od"></a></li>
                    </ul>
                </section>
                <section class="online-support">
                    <img src="/web/images/supportIcon.png" alt="support icon" class="support-icon">
                    <span id="supportText">Онлайн-підтримка</span>
                    <span id="supportTelephone">+1 (555) 555 - 28 - 28</span>
                    <a href="#">support@tbooking.com</a>
                </section>
            </section>
        </section>
        <section class="company-info">
            <section class="wrapper">
                <a href="/" class="site-logo">
                    <img src="/web/images/siteLogo.png" alt="site logo">
                </a>
                <p>Developed and designed by Roman Zablodskyi, 2017</p>
            </section>
        </section>
    </footer>

    <? if(isset($data['scripts']))
        foreach ($data['scripts'] as $scripts):?>
            <script src="/web/scripts/<?=$scripts?>"></script>
        <? endforeach;?>

    <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEtSf3HH0j0zs5XY9gw2H7c4Q-5OMdJ0M&callback=initMap">
    </script>
</body>
</html>