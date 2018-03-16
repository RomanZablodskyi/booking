<section class="main-content">
    <section class="wrapper">
        <form action="/user/confirm_restore" method="post">
            <? if(isset($data['messages'])): ?>
                <section class="messages">
                    <p><?=$data['messages']?></p>
                </section>
            <? endif; ?>
            <label for="code">Отриманий код підтвердження</label>
            <input type="text" name="code" id="code">
            <input type="submit" name="codeSubmit" value="Підтвердити" style="margin-top: 10px" >
        </form>
    </section>
</section>