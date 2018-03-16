<?php
require 'C:/OpenServer/domains/tbooking.com/app/core/Database.php';
require 'C:/OpenServer/domains/tbooking.com/app/core/Model.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/users.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/user.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/passage.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/order.php';
//%progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\modules\php\%phpdriver%\php.ini -q -f %sitedir%\tbooking.com\app\cron\cancelBooking.php

$query = "SELECT `order_id` FROM `Orders` WHERE `tstatus_id` = 1";
$result = Database::getInstance()->query($query);

$currectDate = date('Y-m-d');
$currectTime = date('H:i:s');

foreach ($result as $order){
    $order = new Order($order['order_id']);
    $originDate = $order->get()->origin->date;
    $originTime = $order->get()->origin->time;

    if(strtotime($originDate) < strtotime($currectDate) == false){
        if(strtotime($originDate) == strtotime($currectDate) && strtotime($originTime) < strtotime($currectTime) == false){
            $order->changeStatus(3);
        }
    }else{
        $order->changeStatus(3);
    }
}