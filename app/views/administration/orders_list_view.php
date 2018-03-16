<a href="/suser/">Повернутися назад</a>
<table>
    <thead>
        <tr>
            <td>Номер замовлення</td>
            <td>Дата</td>
            <td>Місце</td>
            <td>Статус замовлення</td>
            <td>Підтвердження оплати</td>
        </tr>
    </thead>
    <tbody>
    <? if($data['data'] != null):?>
        <? $orderDate = $data['data']->get()?>
        <tr>
            <td><?=$orderDate['id']?></td>
            <td><?=$orderDate['date']?></td>
            <td><?=$orderDate['place']?></td>
            <? switch ($orderDate['status']){
                case '1': $status = 'Заброньований'; break;
                case '2': $status = 'Оплачений'; break;
                case '3': $status = 'Недійсний'; break;
            }?>
            <td><?=$status?></td>
            <td>
                <? if($orderDate['status'] == 1):?>
                <form action="/suser/confirm_pay" method="post">
                    <input type="hidden" name="orderId" value="<?=$orderDate['id']?>">
                    <input type="submit" name="submitPay" value="Підтвердити оплату">
                </form>
                <? endif; ?>
            </td>
        </tr>
    <? endif;?>
    </tbody>
</table>