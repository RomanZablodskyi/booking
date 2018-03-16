<section class="main-content">
    <section class="wrapper">
        <form action="/user/restore_pass" method="post">
            <? if(isset($data['messages'])): ?>
                <section class="messages">
                    <p><?=$data['messages']?></p>
                </section>
            <? endif; ?>
            <label for="email">Електронна пошта</label>
            <input type="text" name="email" id="email">
            <label for="pass">Пароль</label>
            <input type="password" name="pass" id="pass">
            <label for="pass_confirm">Підтвердження паролю</label>
            <input type="password" name="pass_confirm" id="pass_confirm">
            <input type="submit" name="submit" value="Змінити пароль" style="margin-top: 10px">
        </form>
    </section>
</section>