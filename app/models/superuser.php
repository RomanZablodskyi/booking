<?php

class Superuser extends Users
{
    protected static $table = 'Superuser';
    protected $status;

    public function __construct($id)
    {
        parent::__construct($id);
    }

    public static function getStatus($id){
        if($id != null){
            $query = "SELECT Superuser_status.status_name FROM Superuser
                      JOIN Superuser_status on Superuser.status_id = Superuser_status.ustatus_id
                      WHERE Superuser.user_id = :id";

            $params = array(":id"   =>  $id);
            $result = self::$db->query($query, $params);

            if(empty($result))
                return null;

            return $result[0]['status_name'];
        }

        return null;
    }
}