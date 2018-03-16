<?php

class Route extends Model
{
    private $id;
    private $originPlace;
    private $originCity;
    private $originCountry;
    private $destinationPlace;
    private $destinationCity;
    private $destinationCountry;
    private $coords;
    private $bcprice;
    private $price;
    private $trans;

    public function __construct($id = null)
    {
        parent::__construct();
        if($id != null){
            $query = 'SELECT Routes.coords, Routes.price, Routes.bc_price, Routes_points.type_id, Places.place_name, Cities.city_name, Countries.country_name, Routes.trans_id FROM `Routes`
                        JOIN Routes_points ON Routes_points.route_id = Routes.route_id
                        JOIN Places ON Routes_points.place_id = Places.place_id
                        JOIN Cities ON Cities.city_id = Places.city_id
                        JOIN Countries ON Countries.country_id = Cities.country_id
                        WHERE Routes.route_id = :id
                        ORDER BY Routes_points.type_id ASC';

            $params = array(':id' => $id);
            $result = self::$db->query($query, $params);

            if(!empty($result)){
                $this->id = $id;
                $this->originPlace = $result[0]['place_name'];
                $this->originCity = $result[0]['city_name'];
                $this->originCountry = $result[0]['country_name'];
                $this->destinationPlace = $result[1]['place_name'];
                $this->destinationCity = $result[1]['city_name'];
                $this->destinationCountry = $result[1]['country_name'];
                $this->coords = $result[0]['coords'];
                $this->price = $result[0]['price'];
                $this->bcprice = $result[0]['bc_price'];
                $this->trans = new Transport($result[0]['trans_id']);
            }
        }
    }

    public function get(){
        return array(
            'id'        =>  $this->id,
            'or_place'  =>  $this->originPlace,
            'or_city'   =>  $this->originCity,
            'or_country'=>  $this->originCountry,
            'des_place'  =>  $this->destinationPlace,
            'des_city'   =>  $this->destinationCity,
            'des_country'=>  $this->destinationCountry,
            'coords'    =>  $this->coords,
            'price'     =>  $this->price,
            'bc_price'     =>  $this->bcprice,
            'transport' =>  $this->trans->get()
        );
    }

    public static function search($or_city, $or_country, $des_city, $des_country){
        $query = "SELECT Des.route_id FROM (
						SELECT Routes.route_id FROM Routes
                        JOIN Routes_points ON Routes_points.route_id = Routes.route_id
                        JOIN Places ON Places.place_id = Routes_points.place_id
                        JOIN Cities ON Cities.city_id = Places.city_id
                        JOIN Countries On Countries.country_id = Cities.city_id 
                        WHERE Cities.city_name = :des_city AND Countries.country_name = :des_country AND Routes_points.type_id = :des_type) AS Des JOIN (
                        SELECT Routes.route_id FROM Routes
                        JOIN Routes_points ON Routes_points.route_id = Routes.route_id
                        JOIN Places ON Places.place_id = Routes_points.place_id
                        JOIN Cities ON Cities.city_id = Places.city_id
                        JOIN Countries On Countries.country_id = Cities.city_id 
                        WHERE Cities.city_name = :or_city AND Countries.country_name = :or_country AND Routes_points.type_id = :or_type) AS Ori ON Ori.route_id = Des.route_id";

        $params = array(
            ':or_city'  =>  $or_city,
            ':or_country'   =>  $or_country,
            ':or_type'  =>  '1',
            ':des_city' =>  $des_city,
            ':des_country'  =>  $des_country,
            ':des_type'  =>  '2'
        );

        self::$db = Database::getInstance();
        $result = self::$db->query($query, $params);

        if(!empty($result)){
            $routes = array();
            foreach ($result as $route){
                array_push($routes, new Route($route['route_id']));
            }

            return $routes;
        }
        return null;
    }

    public static function create($trans, $coords, $origin, $destiation, $price, $bcprice = null){
        if($origin == null || $destiation == null || $price == null)
            return null;

        $createRoute = "INSERT INTO `Routes`(`price`, `coords`, `bc_price`, `trans_id`) VALUES (:price, :coords, :bc_price, :transport)";

        $params = array(
            ':price'    =>  $price,
            ':coords'   =>  $coords,
            ':bc_price' =>  $bcprice,
            ':transport'=>  $trans
        );

        try{
            self::$db = Database::getInstance();
            self::$db->query($createRoute, $params);
            $id = self::$db->getLastId();

            $createPoints = "INSERT INTO `Routes_points`(`route_id`, `place_id`, `type_id`) VALUES (:id, :place, :type)";

            $points_params = array(
                array(
                    ':id'   =>  $id,
                    ':place'    =>  $origin,
                    ':type' =>  '1'
                ),
                array(
                    'id'    =>  $id,
                    ':place'    =>  $destiation,
                    ':type' => '2'
                )
            );

            foreach ($points_params as $point){
                self::$db->query($createPoints, $point);
            }

            return $id;

        }catch(PDOException $e) {
            return null;
        }
    }

    public function delete(){
        $schedules = $this->getSchedule();
        if($schedules != null){
            foreach ($schedules as $schedule){
                $schedule->delete();
            }
        }

        $queryDelPoints = "DELETE Routes_points FROM Routes_points
                    WHERE Routes_points.route_id = :id";

        $queryDelRoute = "DELETE FROM `Routes` WHERE Routes.route_id = :id";

        $params = array(':id'   =>  $this->id);

        self::$db->query($queryDelPoints, $params);
        self::$db->query($queryDelRoute, $params);
    }

    public function getSchedule(){
        $query = "SELECT Schedule.schedule_id FROM Schedule
                    JOIN Routes ON Routes.route_id = Schedule.route_id
                    WHERE Routes.route_id = :id";

        $params = array(':id'   =>  $this->id);
        $result = self::$db->query($query, $params);

        if(!empty($result)){
            $schedules = [];
            foreach ($result as $schedule){
                $objSchedule = new Schedule($schedule['schedule_id']);
                array_push($schedules, $objSchedule);

            }
            return $schedules;
        }
        return null;
    }

    public static function getAllPlaces(){
        $query = "SELECT `place_id`, `place_name`, `city_id` FROM `Places`";

        self::$db = Database::getInstance();
        return self::$db->query($query);
    }

    public static function getAllCities(){
        $query = "SELECT `city_id`, `city_name`, `country_id` FROM `Cities`";

        self::$db = Database::getInstance();
        return self::$db->query($query);
    }

    public static function getAllPCountries(){
        $query = "SELECT `country_id`, `country_name` FROM `Countries`";

        self::$db = Database::getInstance();
        return self::$db->query($query);
    }
}