<section class="main-content">
    <section class="wrapper">
        <form action="/passage/search" method="get">
            <input type="text" name="origin" value="">
            <input type="text" name="destination" value="">
            <input type="text" name="date">
            <select name="tickets-type-selected">
                <option value="airplane">Літак</option>
                <option value="bus">Автобус</option>
                <option value="all">Будь-який</option>
            </select>
            <input type="submit" name="search" value="Розклад і ціни">
        </form>
        <ul class="found-routes">
            <? if (!empty($data['passages'])):?>
                <? foreach ($data['passages'] as $passage):?>
                    <? $passageData = $passage->get();
                        $scheduleData = $passageData['schedule'];
                        $routeData = $scheduleData['route'];
                        $transportData = $routeData['transport'];
                    ?>
                    <li>
                        <section class="route-info">
                            <section class="origin-info">
                                <section class="date-time">
                                    <span class="time"><?=$scheduleData['time']?></span>
                                    <span class="date"><?=$passageData['originDate']?></span>
                                </section>
                                <span class="origin-place"><?=$routeData['or_place'] . ', ' . $routeData['or_city'] . ', ' . $routeData['or_country']?></span>
                            </section>
                            <section class="destination-info">
                                <section class="date-time">
                                    <span class="time"><?=$passageData['destinationTime']?></span>
                                    <span class="date"><?=$passageData['destinationDate']?></span>
                                </section>
                                <span class="destination-place"><?=$routeData['des_place'] . ', ' . $routeData['des_city'] . ', ' . $routeData['des_country']?></span>
                            </section>
                            <span class="route-duration">
                                <span>Тривалість рейсу: </span>
                                <span><?=$scheduleData['travelTime']?></span>
                            </span>
                            <section class="price">

                                <? if ($transportData['type'] == 'airplane'):?>
                                    <span class="route-cost">Бізнес-клас: <?=$routeData['bc_price']?> грн</span>
                                <? endif;?>

                                <span class="route-cost">Звичайний: <?=$routeData['price']?> грн</span>

                            </section>
                        </section>
                        <section class="separator"></section>
                        <section class="other">
                            <? switch ($transportData['type']){
                                case 'airplane': $type = 'Літак'; break;
                                case 'bus': $type = 'Автобус'; break;
                            }?>
                            <span class="transport-type">Тип: <span class="type-name"><?=$type?></span></span>
                            <span class="transport-name">Транспорт: <?=$transportData['transport']?></span>
                            <span class="transport-carrier">Перевізник: <?=$transportData['carrier']?></span>
                            <a href="/passage/get?id=<?=$passageData['id']?>">Деталі</a>
                        </section>
                    </li>
                <? endforeach;?>
            <? endif;?>
        </ul>
    </section>
</section>





