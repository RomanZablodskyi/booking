<?php

class Schedule extends Model
{
    private $id;
    private $time;
    private $weekday;
    private $travelTime;
    private $route;

    public function __construct($id)
    {
        parent::__construct();
        if($id != null){
            $query = 'SELECT `weekday`, `time`, `travelTime`, `route_id` FROM `Schedule`
                        WHERE  `schedule_id` = :id';

            $params = array(':id' => $id);
            $result = self::$db->query($query, $params);

            if(!empty($result)){
                $this->id = $id;
                $this->weekday = $result[0]['weekday'];
                $this->time = $result[0]['time'];
                $this->travelTime = $result[0]['travelTime'];
                $this->route = new Route($result[0]['route_id']);
            }
        }
    }

    public static function create($time, $weekday, $travelTime, $routeID){
        $query = "INSERT INTO `Schedule`(`weekday`, `time`, `travelTime`, `route_id`) VALUES (:weekday, :time, :traveltime, :route)";

        $params = array(
            ':weekday'  =>  $weekday,
            ':time'     =>  $time,
            'traveltime'=>  $travelTime,
            ':route'    =>  $routeID
        );

        self::$db = Database::getInstance();
        self::$db->query($query, $params);
    }

    public function delete(){
        $passages = $this->getPassages();

        if($passages != null){
            foreach ($passages as $passage){
                $passage->delete();
            }
        }

        $query = "DELETE Schedule, Passage FROM Schedule, Passage 
                  WHERE Passage.schedule_id = Schedule.schedule_id AND Schedule.schedule_id = :id";
        $params = array(':id'  =>  $this->id);

        self::$db->query($query, $params);
    }

    public function get(){
        return array(
            'time'      =>  $this->time,
            'weekday'   =>  $this->weekday,
            'travelTime'=>  $this->travelTime,
            'route'     =>  $this->route->get()
        );
    }

    public function getPassages(){
        $query = "SELECT Passage.passage_id FROM Passage
                    JOIN Schedule ON Schedule.schedule_id = Passage.schedule_id
                    WHERE Schedule.schedule_id = :id";

        $params = array(':id'   =>  $this->id);
        $result = self::$db->query($query, $params);

        if(!empty($result)){
            $passages = [];
            foreach ($result as $passage){
                $objPassage = new Schedule($passage['passage_id']);
                array_push($passages, $objPassage);

            }
            return $passages;
        }
        return null;
    }
}