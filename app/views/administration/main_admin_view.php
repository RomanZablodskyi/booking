<a href="/suser/create_route">Створити маршрут</a>
<h2 class="theme">Форма адміністрації</h2>
<form action="/suser/create_saler" method="post" class="admin_form">
    <label for="login">Логін</label>
    <input type="text" name="login" id="login">
    <label for="email">Електронна пошта</label>
    <input type="text" name="email" id="email">
    <label for="password">Пароль</label>
    <input type="password" name="password" id="password">
    <input type="submit" name="createSaler" value="Додати продавця">
</form>

<form action="/suser/search_route" method="get" class="admin_form">
    <label for="origin">Відправлення</label>
    <input type="text" name="origin" id="origin">
    <label for="destination">Прибуття</label>
    <input type="text" name="destination" id="destination">
    <input type="submit" name="searchRoute" value="Пошук маршруту">
</form>
