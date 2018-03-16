<?php

class Controller_passage extends Controller
{
    public function action_get(){
        if(isset($_GET['id'])) {
            $passageID = $_GET['id'];
            $passage = new Passage($passageID);
            if(!$passage->isExist()){
                echo "Даний маршрут не знайдено";
            }
            else{
                $passageData = $passage->get();

                $schedule = $passageData['schedule'];
                $route = $schedule['route'];
                $transport = $route['transport'];

                $file = '';

                switch ($transport['type']){
                    case 'bus': $file = 'bus_placement_view.php'; break;
                    case 'airplane': $file = 'air_placement_view.php'; break;
                }

                $this->view->render('passage_view.php', 'template_view.php', array(
                    'title'     =>      'Бронювання на маршрут ' . $route['or_city'] . ' - ' . $route['des_city'],
                    'passage'   =>      $passageData,
                    'schedule'  =>      $schedule,
                    'transport' =>      $transport,
                    'route'     =>      $route,
                    'styles'    =>      array('routePageStyles.css'),
                    'scripts'   =>      array('maps/initMap.js', 'maps/loadPoints.js', 'selectingPlaces.js'),
                    'file'      =>      $file,
                    'ordered'   =>      $passage->orderedPlaces(),
                    'id'        =>      $passageID
                ));
            }
        }
        else
            $this->view->render('main_view.php', 'template_view.php');

    }

    public function action_get_coords(){
        if(isset($_POST['id'])){
            $passage = new Passage($_POST['id']);
            $coords = $passage->get()['schedule']['route']['coords'];

            if(is_string($coords))
                echo $coords;
        }
    }

    public function action_search(){
        $origin = explode(',', $this->clearString($_GET['origin']));
        $destination = explode(',', $this->clearString($_GET['destination']));
        $date = $this->clearString($_GET['date']);
        $type = $this->clearString($_GET['tickets-type-selected']);

        $dateArray = explode(' ', $date);
        $day = $dateArray[0];
        $month = '';
        $year = $dateArray[2];

        switch ($dateArray[1]){
            case 'Січня': $month = '01'; break;
            case 'Лютого': $month = '02'; break;
            case 'Березня': $month = '03'; break;
            case 'Квітня': $month = '04'; break;
            case 'Травня': $month = '05'; break;
            case 'Червня': $month = '06'; break;
            case 'Липня': $month = '07'; break;
            case 'Серпня': $month = '08'; break;
            case 'Вересня': $month = '09'; break;
            case 'Жовтня': $month = '10'; break;
            case 'Листопада': $month = '11'; break;
            case 'Грудня': $month = '12'; break;
        }

        $fullDate = $year . '-' . $month . '-' . $day;
        $passages = Passage::search($origin[0], $this->clearString($origin[1]), $destination[0], $this->clearString($destination[1]), $fullDate, $type);

        $this->view->render('passages_list_view.php', 'template_view.php', array(
            'styles'    =>      array('routesListStyles.css'),
            'title'     =>      'Забронювати квиток на маршрут',
            'passages'  =>      $passages,
            'scripts' => array('datepicker.js', 'datepicker.ua-UA.js', 'dateFormat.js')
        ));

    }

    public function action_booking(){
        if(isset($_POST['booked'])){
            $chousen = $this->clearString($_POST['chousen']);
            $chousen = explode(',', $chousen);
            $date = date('Y-m-d');
            $pasId = $this->clearString($_POST['passage']);

            foreach ($chousen as $booked){
                Order::create($date, $booked, $_SESSION['user'], $pasId);
            }
        }

        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
}