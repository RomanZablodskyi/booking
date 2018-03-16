<?php

class Routing
{
    private static $ctrl_name = 'Main';
    private static $method_name = 'index';

    public static function run()
    {
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        self::$ctrl_name = !empty($routes[1]) ? $routes[1] : self::$ctrl_name;
        self::$method_name = !empty($routes[2]) ? $routes[2] : self::$method_name;

        if(strstr(self::$method_name, '?') == true) {
            $params = explode('?', self::$method_name);
            self::$method_name = $params[0];
        }

        $controller_name = 'Controller_'.self::$ctrl_name;
        $action_name = 'action_'.self::$method_name;

        $controller_file = strtolower($controller_name).'.php';
        $path_to_controller = "app/controllers/";

        if(is_dir($path_to_controller)) {

            $controller_path = $path_to_controller . $controller_file;
            if (file_exists($controller_path)) {
                include $path_to_controller . $controller_file;
            }
            else{
                Routing::Error404();
            }
        }
        else
        {
            Routing::Error404();
        }

        $controller = new $controller_name;
        $action = $action_name;

        if(method_exists($controller, $action))
        {
            $controller->$action();
        }
        else
        {
            Routing::Error404();
        }

    }

    public static function Error404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }

}