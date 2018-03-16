<section class="main-content">
    <section class="wrapper">
        <section class="route-data">

            <section class="route-origin">
                <h2>Відправлення</h2>
                <section>Дата: <span><?=$data['passage']['originDate']?></span></section>
                <section>Час : <span><?=$data['schedule']['time']?></span></section>
                <section>Місце: <span><?=$data['route']['or_place'] .  ', ' . $data['route']['or_city'] . ', ' . $data['route']['or_country']?></span></section>
            </section>

            <section class="route-destination">
                <h2>Прибуття</h2>
                <section>Дата: <span><?=$data['passage']['destinationDate']?></span></section>
                <section>Час : <span><?=$data['passage']['destinationTime']?></span></section>
                <section>Місце: <span><?=$data['route']['des_place'] . ', ' .$data['route']['des_city'] . ', ' . $data['route']['des_country']?></span></section>
            </section>


            <section class="route-other">
                <section>Перевізник: <span><?=$data['transport']['carrier']?></span></section>
                <section>Транспорт: <span><?=$data['transport']['transport']?></span></section>
                <? if($data['transport']['type'] == 'airplane'):?>
                    <section>Ціна за місце бізнес класу: <span><?=$data['route']['bc_price']?></span> грн</section>
                <? endif;?>
                <section>Ціна: <span><?=$data['route']['price']?></span> грн</section>
            </section>
        </section>

        <section class="placement-legend">
            <? if($data['transport']['type'] == 'airplane'):?>
                <section>
                    <span class="fplace"></span>
                    <span class="description">Вільне місце першого класу</span>
                </section>
            <? endif;?>
            <section>
                <span class="place"></span>
                <span class="description">Вільне місце</span>
            </section>
            <section>
                <span class="place chousen"></span>
                <span class="description">Обране місце</span>
            </section>
            <section>
                <span class="place booked"></span>
                <span class="description">Заброньоване місце</span>
            </section>
        </section>
        <section class="placement">
            <? require_once $data['file']; ?>
        </section>
        <? if(isset($_SESSION['user'])):?>
            <form name="booking" action="/passage/booking" method="post">
                <input type="hidden" name="chousen" value="">
                <input type="hidden" name="passage" value="<?=$data['id']?>">
                <input type="submit" name="booked" value="Забронювати">
            </form>
        <? else:?>
            <p class="not_auth">Для бронювання місця необхідно авторизуватися!</p>
        <? endif;?>

        <section id="map"></section>
    </section>
</section>