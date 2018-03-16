<?php

class Controller_404 extends Controller
{
    public function action_index(){
        $this->view->render('404_page_view.php', 'template_view.php', array(
            'title'    =>   'Сторінка не знайдена'
        ));
    }
}