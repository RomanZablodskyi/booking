<?php

class Controller
{
    public $model;
    public $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function action_index()
    {

    }

    protected function clearString($str){
        $str = stripslashes($str);
        $str = trim($str);
        $str = htmlspecialchars($str);

        return $str;
    }
}