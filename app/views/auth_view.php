<section class="main-content">
    <section class="wrapper">
        <form action="/user/auth" method="post">
            <? if(isset($data['messages'])): ?>
                <section class="messages">
                    <p>Неможливо здійснити авторизацію. Можливі причини:</p>
                    <ul>
                        <? foreach ($data['messages'] as $errors):?>
                            <li><?=$errors?></li>
                        <? endforeach;?>
                    </ul>
                </section>
            <? endif; ?>
            <label for="name">Логін / Електронна пошта</label>
            <input type="text" name="name" id="name">
            <label for="pass">Пароль</label>
            <input type="password" name="pass" id="pass">
            <a href="/user/restore_pass">Відновити пароль</a>
            <input type="submit" name="submit" value="Увійти">
        </form>
    </section>
</section>