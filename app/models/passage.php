<?php

class Passage extends Model
{
    private $id;
    private $originDate;
    private $destinationDate;
    private $destinationTime;
    private $schedule;

    public function __construct($id)
    {
        parent::__construct();

        if($id != null){
            $query = 'SELECT `originDate`, `destinationDate`, `destinationTime`, `schedule_id` FROM `Passage` WHERE `passage_id` = :id';

            $params = array(':id' => $id);
            $result = self::$db->query($query, $params);

            if(!empty($result)){
                $this->id = $id;
                $this->originDate = $result[0]['originDate'];
                $this->destinationDate = $result[0]['destinationDate'];
                $this->destinationTime = $result[0]['destinationTime'];
                $this->schedule = new Schedule($result[0]['schedule_id']);
            }
        }
    }

    public function isExist(){
        if($this->id != null)
            return true;

        return false;
    }

    public function get(){
        return array(
            'id'                =>  $this->id,
            'originDate'        =>  $this->originDate,
            'destinationDate'   =>  $this->destinationDate,
            'destinationTime'   =>  $this->destinationTime,
            'schedule'          =>  $this->schedule->get()
        );
    }

    public function orderedPlaces(){
        $result = [];
        $query = "SELECT Orders.place FROM Orders
                JOIN Passage ON Passage.passage_id = Orders.passage
                WHERE Orders.passage = :id";

        $params = array(
            ':id'   =>  $this->id
        );

        $res = self::$db->query($query, $params);

        foreach ($res as $resElem)
            array_push($result, $resElem['place']);

        return $result;
    }

    public static function search($orCity, $orCountry, $desCity, $desCountry, $date, $type){

        switch ($type){
            case 'airplane': $rtype = 'airplane'; break;
            case 'bus': $rtype = 'bus'; break;
            case 'all': $rtype = '%'; break;
            default: $rtype = '%'; break;
        }

        $query = "SELECT Des.passage_id FROM (
						SELECT Passage.passage_id FROM Passage
    					JOIN Schedule ON Passage.schedule_id = Schedule.schedule_id
    					JOIN Routes ON Routes.route_id = Schedule.route_id
                        JOIN Routes_points ON Routes_points.route_id = Routes.route_id
                        JOIN Places ON Places.place_id = Routes_points.place_id
                        JOIN Cities ON Cities.city_id = Places.city_id
                        JOIN Countries On Countries.country_id = Cities.city_id 
                        WHERE Cities.city_name = :des_city AND Countries.country_name = :des_country AND Routes_points.type_id = :des_id AND Passage.originDate = :date) AS Des JOIN (
                        SELECT Passage.passage_id FROM Passage
    					JOIN Schedule ON Passage.schedule_id = Schedule.schedule_id
    					JOIN Routes ON Routes.route_id = Schedule.route_id
    					JOIN Transport ON Routes.trans_id = Transport.trans_id
    					JOIN Transport_type ON Transport_type.ttype_id = Transport.ttype_id
                        JOIN Routes_points ON Routes_points.route_id = Routes.route_id
                        JOIN Places ON Places.place_id = Routes_points.place_id
                        JOIN Cities ON Cities.city_id = Places.city_id
                        JOIN Countries On Countries.country_id = Cities.city_id 
                        WHERE Cities.city_name = :or_city AND Countries.country_name = :or_country AND Routes_points.type_id = :or_id AND Transport_type.type_name LIKE :type) AS Ori ON Ori.passage_id = Des.passage_id";

        $params = array(
            ':or_city'      =>  $orCity,
            ':or_country'   =>  $orCountry,
            ':or_id'        =>  '1',
            ':date'         =>  $date,
            ':des_city'     =>  $desCity,
            ':des_country'  =>  $desCountry,
            ':des_id'       =>  '2',
            ':type'         =>  $rtype
        );

        self::$db = !isset(self::$db) ? Database::getInstance() : self::$db;
        $result = self::$db->query($query, $params);

        if(empty($result))
            return null;
        else{
            $resultArr = [];
            foreach ($result as $passageID){
                $passage = new Passage($passageID['passage_id']);
                array_push($resultArr, $passage);
            }
            return $resultArr;
        }
    }

    public static function create($or_date, $des_date, $des_time, $schedule){
        $query = "INSERT INTO `Passage`(`originDate`, `destinationDate`, `destinationTime`, `schedule_id`) VALUES (:or_date, :des_date, :des_time, :schedule)";

        $params = array(
            ':or_date'  =>  $or_date,
            ':des_date' =>  $des_date,
            ':des_time' =>  $des_time,
            ':schedule' =>  $schedule
        );

        self::$db = Database::getInstance();
        self::$db->query($query, $params);
    }

    public function delete(){
        $query = "DELETE FROM `Passage` WHERE `passage_id` = :id";

        $params = array(':id'   =>  $this->id);

        self::$db->query($query, $params);
    }
}