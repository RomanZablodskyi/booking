<section class="main-content">
    <section class="wrapper">
        <span>Логін: <?=$data['userData']['login']?></span>
        <span>Пошта: <?=$data['userData']['email']?></span>
        <table class="orders" cellspacing="0">
            <tr>
                <td>Номер замов.</td>
                <td>Відправлення</td>
                <td>Прибуття</td>
                <td>Дата бронювання</td>
                <td>Місце</td>
                <td>Статус</td>
                <td>Тип</td>
            </tr>

            <? foreach ($data['orders'] as $orders):?>
                <tr>
                    <? $route = $orders->get()['data'] ?>
                    <td><?=$orders->get()['id']?></td>
                    <td><?=$route->origin->city . ', ' . $route->origin->country?></td>
                    <td><?=$route->destination->city . ', ' . $route->destination->country?></td>
                    <td><?=$orders->get()['date']?></td>
                    <td><?=$orders->get()['place']?></td>

                    <? switch ($orders->get()['status']){
                        case '1': $status = 'Заброньований'; break;
                        case '2': $status = 'Оплачений'; break;
                        case '3': $status = 'Недійсний'; break;
                    }
                    ?>
                    <td><?=$status?></td>

                    <? switch ($route->transportType){
                        case 'airplane': $transport = 'Літак'; break;
                        case 'bus': $transport = 'Автобус'; break;
                    }
                    ?>
                    <td><?=$transport?></td>
                </tr>
            <? endforeach; ?>
        </table>
    </section>
</section>