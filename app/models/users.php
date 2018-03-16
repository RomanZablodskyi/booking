<?php

abstract class Users extends Model
{
    protected $id;
    protected $login;
    protected $email;
    protected static $table = '';

    public function __construct($id)
    {
        parent::__construct();

        if($id != null){
            $query = "SELECT user_login, user_email FROM " . static::$table . " WHERE user_id = :id";

            $params = array(':id' => $id);
            $result = self::$db->query($query, $params);

            if(!empty($result)){
                $this->id = $id;
                $this->login = $result[0]['user_login'];
                $this->email = $result[0]['user_email'];
            }
        }
    }

    public function getID(){
        return $this->id;
    }

    public static function getIdByEmail($email){
        $query = "SELECT user_id FROM " . static::$table . " WHERE user_email = :email";

        $params = array(
            ':email'    =>  $email
        );

        self::$db = Database::getInstance();
        $result = self::$db->query($query, $params);

        return $result[0]['user_id'];
    }

    public static function isExist($pass, $field, $sfield = null){
        $email = '';
        $login = '';
        $query = "SELECT user_id, user_pass FROM " . static::$table . " WHERE user_login = :login OR user_email = :email";

        if($sfield == null){
            $email = $field;
            $login = $field;
        }else{
            $pattern = '/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u';

            if(preg_match($pattern, $field)){
                $email = $field;
                $login = $sfield;
            }else{
                $email = $sfield;
                $login = $field;
            }
        }

        $params = array(
            ':login'    =>  $login,
            ':email'    =>  $email
        );

        self::$db = Database::getInstance();
        $result = self::$db->query($query, $params);

        if(!empty($result)) {
            foreach ($result as $user) {
                if (password_verify($pass, $user['user_pass'])){
                    return $user['user_id'];
                }
            }

            return true;
        }

        return false;
    }
}