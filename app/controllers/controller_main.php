<?php

class Controller_Main extends Controller
{
    public function action_index()
    {
       $this->view->render('main_view.php', 'template_view.php',
            array(  'title' => 'Головна сторінка',
                    'scripts' => array('datepicker.js', 'datepicker.ua-UA.js', 'slider.js', 'dateFormat.js')
            ));
    }
}