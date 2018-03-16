<?php
require 'C:/OpenServer/domains/tbooking.com/app/core/Database.php';
//%progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\modules\php\%phpdriver%\php.ini -q -f %sitedir%\tbooking.com\app\cron\generateNewPassages.php

$currectDayweek = date('N');
$currectDate = date('Y-m-d');

$query = "SELECT `schedule_id`, `time`, `travelTime` FROM `Schedule` WHERE `weekday` = :weekday";
$params = array(":weekday" =>  $currectDayweek);
$result = Database::getInstance()->query($query, $params);

foreach ($result as $schedule){
    $id = $schedule['schedule_id'];

    $destinationDate = date('Y-m-d', strtotime($schedule['time']) + strtotime($schedule['travelTime']) - strtotime("00:00:00"));
    $destinationTime = date('H:i:s', strtotime($schedule['time']) + strtotime($schedule['travelTime']) - strtotime("00:00:00"));

    $query1 = "INSERT INTO `Passage`(`originDate`, `destinationDate`, `destinationTime`, `schedule_id`) VALUES (:odate, :ddate, :dtime, :id)";
    $params1 = array(
        ':odate'    =>  $currectDate,
        ':ddate'    =>  $destinationDate,
        ':dtime'    =>  $destinationTime,
        ':id'       =>  $id
    );

    Database::getInstance()->query($query1, $params1);
}


//%progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\modules\php\%phpdriver%\php.ini -q -f %sitedir%\tbooking.com\app\script.php