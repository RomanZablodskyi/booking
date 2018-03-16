<?php

class View
{
    public function __construct(){

    }

    public function render($content_view, $template_view, $data = null){
        include 'app/views/'. $template_view;
    }

    protected function convertDate($dateString){
        $dateArray = explode('-', $dateString);

        $day = $dateArray[2];
        $month = '';

        switch ($dateArray[1]){
            case '01': $month = 'Січня'; break;
            case '02': $month = 'Лютого'; break;
            case '03': $month = 'Березня'; break;
            case '04': $month = 'Квітня'; break;
            case '05': $month = 'Травня'; break;
            case '06': $month = 'Червня'; break;
            case '07': $month = 'Липня'; break;
            case '08': $month = 'Серпня'; break;
            case '09': $month = 'Вересня'; break;
            case '10': $month = 'Жовтня'; break;
            case '11': $month = 'Листопада'; break;
            case '12': $month = 'Грудня'; break;
        }

        return $day . ' ' . $month;
    }

    protected function convertTime($timeString){
        return substr($timeString, 0, -3);
    }
}