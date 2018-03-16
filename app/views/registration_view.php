<section class="main-content">
    <section class="wrapper">
        <form action="/user/registration" method="post">
            <? if(isset($data['messages'])): ?>
                <section class="messages">
                    <p>Неможливо здійснити реєстрацію. Можливі причини:</p>
                    <ul>
                        <? foreach ($data['messages'] as $errors):?>
                            <li><?=$errors?></li>
                        <? endforeach;?>
                    </ul>
                </section>
            <? endif; ?>
            <label for="login">Логін</label>
            <input type="text" name="login" id="login">
            <label for="email">Електронна пошта</label>
            <input type="email" name="email" id="email">
            <label for="pass">Пароль</label>
            <input type="password" name="pass" id="pass">
            <input type="submit" name="submit" value="Реєстрація" style="margin-top: 10px" >
        </form>
    </section>
</section>