<?php

class Order extends Model
{
    private $id;
    private $date;
    private $place;
    private $passage;
    private $status;
    private $user;
    private $file;

    public function __construct($id)
    {
        parent::__construct();

        if($id != null){
            $query = 'SELECT `date`, `place`, `passage`, `tstatus_id`, `orderFile`, `user_id` FROM `Orders` WHERE `order_id` = :id';

            $params = array(':id' => $id);
            $result = self::$db->query($query, $params);

            if(!empty($result)){
                $this->id = $id;
                $this->date = $result[0]['date'];
                $this->place = $result[0]['place'];
                $this->passage = new Passage($result[0]['passage_id']);
                $this->status = $result[0]['tstatus_id'];
                $this->user = new User($result[0]['user_id']);
                $this->file = $result[0]['orderFile'];
            }
        }
    }

    public static function create($date, $place, $userID, $passageID){
        $datePattern = '/^(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))$/';

        if(!preg_match($datePattern, $date))
            $bookingDate = date('Y-m-d');
        else
            $bookingDate = $date;

        $passage = new Passage($passageID);
        $user = new User($userID);
        $ordered = $passage->orderedPlaces();

        if(in_array($place, $ordered) == false) {

            $fileName = md5(uniqid(rand(), true)) . '.xml';
            self::createXML($passage->get(), $user->get()['login'], $fileName);

            $query = "INSERT INTO `Orders`(`orderFile`, `place`, `date`, `passage`, `user_id`, `tstatus_id`) VALUES (:fileName, :place, :date, :passage, :user, :status)";

            $params = array(
                ':fileName' => $fileName,
                ':place' => $place,
                ':date' => $bookingDate,
                ':passage' => $passageID,
                ':user' => $userID,
                ':status' => '1'
            );

            self::$db = Database::getInstance();
            self::$db->query($query, $params);
        }
    }

    private function loadXML(){
        $urlToXML = 'xml/orders/' . $this->user->get()['login'] . '/' . $this->file;
        $file_type = new SplFileInfo($urlToXML);
        if(file_exists($urlToXML) && $file_type->getExtension() == 'xml')
            $xmlObj = simplexml_load_file($urlToXML);
        else
            $xmlObj = null;

        return $xmlObj;
    }

    private static function createXML($passage, $userLogin, $fileName){

        $schedule = $passage['schedule'];
        $route = $schedule['route'];
        $transport = $route['transport'];

        $dom = new domDocument("1.0", "utf-8");
        $root = $dom->createElement("order");
        $dom->appendChild($root);

        $origin = $dom->createElement("origin");
        $or_place = $dom->createElement("place", $route['or_place']);
        $or_city = $dom->createElement("city", $route['or_city']);
        $or_country = $dom->createElement("country", $route['or_country']);
        $or_date = $dom->createElement("date", $passage['originDate']);
        $or_time = $dom->createElement("time", $schedule['time']);
        $origin->appendChild($or_place);
        $origin->appendChild($or_city);
        $origin->appendChild($or_country);
        $origin->appendChild($or_date);
        $origin->appendChild($or_time);

        $destination = $dom->createElement("destination");
        $des_place = $dom->createElement("place", $route['des_place']);
        $des_city = $dom->createElement("city", $route['des_city']);
        $des_country = $dom->createElement("country", $route['des_country']);
        $des_date = $dom->createElement("date", $passage['destinationDate']);
        $des_time = $dom->createElement("time", $passage['destinationTime']);
        $destination->appendChild($des_place);
        $destination->appendChild($des_city);
        $destination->appendChild($des_country);
        $destination->appendChild($des_date);
        $destination->appendChild($des_time);

        $price = $dom->createElement("price", $route['price']);
        $trans = $dom->createElement("transportName", $transport['transport']);
        $carrier = $dom->createElement("transportCarrier", $transport['carrier']);
        $type = $dom->createElement("transportType", $transport['type']);

        $root->appendChild($origin);
        $root->appendChild($destination);
        $root->appendChild($price);
        $root->appendChild($trans);
        $root->appendChild($carrier);
        $root->appendChild($type);

        $dom->save('xml/orders/' . $userLogin . '/' . $fileName);
    }

    public function get(){
        return array(
            'id'        =>      $this->id,
            'date'      =>      $this->date,
            'place'     =>      $this->place,
            'status'    =>      $this->status,
            'data'      =>      $this->loadXML()
        );
    }

    public function changeStatus($statusID){
        $query = "UPDATE `Orders` SET `tstatus_id`= :status WHERE `order_id` = :id";

        $params = array(
            ":id"       =>      $this->id,
            ":status"   =>      $statusID
        );

        self::$db->query($query, $params);
    }

    public static function isExist($id){
        $query = "SELECT order_id FROM `Orders` WHERE Orders.order_id = :id";

        $params = array(":id"   =>  $id);
        $result = Database::getInstance()->query($query, $params);

        if(empty($result))
            return false;

        return true;
    }
}