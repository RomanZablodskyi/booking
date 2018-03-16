<?php

require 'C:/OpenServer/domains/tbooking.com/app/core/Database.php';
require 'C:/OpenServer/domains/tbooking.com/app/core/Model.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/transport.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/route.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/schedule.php';
require 'C:/OpenServer/domains/tbooking.com/app/models/passage.php';
//%progdir%\modules\php\%phpdriver%\php-win.exe -c %progdir%\modules\php\%phpdriver%\php.ini -q -f %sitedir%\tbooking.com\app\cron\deletePassage.php


$currectDate = date('Y-m-d');
$currectTime = date('H:i:s');

$query = "SELECT Passage.passage_id, Passage.originDate, Schedule.time FROM Passage
        JOIN Schedule ON Schedule.schedule_id = Passage.schedule_id";
$result = Database::getInstance()->query($query);

foreach ($result as $passage){
    $pas = new Passage($passage['passage_id']);

    if(strtotime($passage['originDate']) < strtotime($currectDate) == false){
        if(strtotime($passage['originDate']) == strtotime($currectDate) && strtotime($passage['time']) < strtotime($currectTime) == true){
            $pas->delete();
        }
    }else{
        $pas->delete();
    }
}