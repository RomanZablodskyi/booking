<?php

class User extends Users
{
    protected static $table = 'Users';

    public function __construct($id)
    {
        parent::__construct($id);
    }

    public function get(){
        return array(
            'login' =>  $this->login,
            'email' =>  $this->email
        );
    }

    public function getOrders(){
        $orders = [];
        $query = "SELECT Orders.order_id FROM `Orders`
                        JOIN Users ON Users.user_id = Orders.user_id
                        WHERE Orders.user_id = :id";

        $params = array(':id'   =>  $this->id);

        $res = self::$db->query($query, $params);
        foreach ($res as $order){
            $obj = new Order($order['order_id']);
            array_push($orders, $obj);
        }

        return $orders;
    }

    public static function create($login, $email, $pass){
        $query = "INSERT INTO " . self::$table . " (user_login, user_email, user_pass) VALUES (:login, :email, :pass)";

        $params = array(
            ':login' => $login,
            ':email' => $email,
            ':pass' => password_hash($pass, PASSWORD_BCRYPT)
        );

        self::$db = Database::getInstance();
        self::$db->query($query, $params);

        mkdir('xml/orders/' . $login);
    }

    public function changePassword($passString){
        if($passString != '') {
            $query = "UPDATE " . self::$table . " SET `user_pass` = :pass WHERE `user_id` = :id";
            $params = array(
                ':pass' => password_hash($passString, PASSWORD_BCRYPT),
                ':id' => $this->id
            );

            self::$db->query($query, $params);
        }
    }

}