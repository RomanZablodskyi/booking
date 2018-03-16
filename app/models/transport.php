<?php

class Transport extends Model
{
    private $id;
    private $name;
    private $placement;
    private $carrier;
    private $type;

    public function __construct($id)
    {
        parent::__construct();
        if($id != null){
            $query = 'SELECT `trans_name`, `trans_placement`, Carriers.carrier_name, Transport_type.type_name FROM `Transport`
                        JOIN Transport_type ON Transport_type.ttype_id = Transport.ttype_id
                        JOIN Carriers ON Carriers.carrier_id = Transport.carrier_id
                        WHERE  `trans_id` = :id';

            $params = array(':id' => $id);
            $result = self::$db->query($query, $params);

            if(!empty($result)){
                $this->id = $id;
                $this->name = $result[0]['trans_name'];
                $this->type = $result[0]['type_name'];
                $this->placement = $this->parseXML($result[0]['trans_placement'], $this->type);
                $this->carrier = $result[0]['carrier_name'];
            }
        }
    }

    private function loadXML($fileName){
        $urlToXML = 'xml/placements/' . $fileName;
        $file_type = new SplFileInfo($urlToXML);

        if(file_exists($urlToXML) && $file_type->getExtension() == 'xml')
            $xmlObj = simplexml_load_file($urlToXML);
        else
            return null;

        return $xmlObj;
    }

    private function arrayProc($array){
        $result = [];

        foreach ($array as $rows) {
            $row = [];
            foreach ($rows as $place)
                if ($place != 'empty')
                    array_push($row, intval($place));
                else
                    array_push($row, null);

            array_push($result, array_reverse($row));
        }

        return $result;
    }

    private function parseXML($fileName, $transType){
        $xmlFile = $this->loadXML($fileName);
        $arr = [];

        if($xmlFile != null) {

            switch ($transType){
                case 'bus': {
                    $arr = $this->arrayProc($xmlFile);
                };
                    break;
                case 'airplane': {
                    $business = [];
                    $econom = [];

                    foreach ($xmlFile->business as $bsn)
                        array_push($business, $this->arrayProc($bsn));

                    foreach ($xmlFile->econom as $eco)
                        array_push($econom, $this->arrayProc($eco));

                    array_push($arr, $business);
                    array_push($arr, $econom);
                };
                    break;
            }

        }else
            return null;

        return $arr;
    }

    public function get(){
        return array(
            'transport'             =>      $this->name,
            'placement'             =>      $this->placement,
            'type'                  =>      $this->type,
            'carrier'               =>      $this->carrier,
        );
    }

    public static function getAllTransport(){
        $query = "SELECT `trans_id`, `trans_name`, `ttype_id` FROM `Transport`";

        self::$db = Database::getInstance();
        return self::$db->query($query);
    }
}