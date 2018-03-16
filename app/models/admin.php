<?php


class Admin extends Superuser
{
    public function __construct($id)
    {
        parent::__construct($id);
    }

    public function createSaler($email, $login, $pass){
        if(Superuser::isExist($pass, $login, $email) != false){
            return null;
        }
        else{
            $query = "INSERT INTO " . self::$table . " (user_login, user_email, user_pass, status_id) VALUES (:login, :email, :pass, :status)";

            $params = array(
                ':login' => $login,
                ':email' => $email,
                ':pass' => password_hash($pass, PASSWORD_BCRYPT),
                ':status'   =>  '3'
            );

            self::$db = Database::getInstance();
            self::$db->query($query, $params);
        }
    }

    public function addRoute($trans, $coords, $o_place, $d_place, $price, $bc_price,$schedules){
        $routeID = Route::create($trans, $coords, $o_place, $d_place, $price, $bc_price);
        if ($routeID != null && is_numeric($routeID)) {
            foreach ($schedules as $schedule) {
                Schedule::create($schedule['time'], $schedule['weekday'], $schedule['travelTime'], $routeID);
            }
            return true;
        }
        return false;
    }

    public function delRoute($id){
        $route = new Route($id);
        $route->delete();
    }
}