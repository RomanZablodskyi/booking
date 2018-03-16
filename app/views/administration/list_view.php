<a href="/suser">&larr; Назад</a>
<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>Місце відправлення</td>
            <td>Місце прибуття</td>
            <td>Ціна</td>
            <td>Транспорт</td>
            <td>Перевізник</td>
            <td>Тип</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    <? if($data['data'] != null):?>
        <? foreach ($data['data'] as $route):?>
        <tr>
            <td><?=$route->get()['id']?></td>
            <td><?=$route->get()['or_place'] . ', ' . $route->get()['or_city'] . ', ' . $route->get()['or_country']?></td>
            <td><?=$route->get()['des_place'] . ', ' . $route->get()['des_city'] . ', ' . $route->get()['des_country']?></td>

            <? if ( $route->get()['transport']['type'] == 'airplane'):?>
                <td><?=$route->get()['price'] . ' ' . $route->get()['bc_price']?></td>
            <? else:?>
                <td><?=$route->get()['price']?></td>
            <? endif;?>

            <td><?=$route->get()['transport']['transport']?></td>
            <td><?=$route->get()['transport']['carrier']?></td>
            <td><?=$route->get()['transport']['type']?></td>
            <td><a href="/suser/delete_route?id=<?=$route->get()['id']?>">Видалити</a></td>
        </tr>
        <? endforeach;?>
    <? endif ?>
    </tbody>
</table>