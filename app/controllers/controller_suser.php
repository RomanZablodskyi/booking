<?php

class Controller_suser extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function action_index()
    {
        if(isset($_SESSION['status'])){
            switch ($_SESSION['status']){
                case 'administrator': $this->view->render('main_admin_view.php', 'administration/template_admin_view.php',
                    array('title' =>  'Сторінка адміністрування'));
                    break;
                case  'saler': $this->view->render('main_saler_view.php', 'administration/template_admin_view.php',
                    array('title' =>  'Підтвердження оплати бронювань'));
            }
        }else{
            if(isset($_POST['s_user_submit'])){
                $name = $_POST['name'];
                $pass = $_POST['password'];

                $check = Superuser::isExist($pass, $name);

                if(is_numeric($check)){
                    $_SESSION['status'] = Superuser::getStatus($check);
                    $_SESSION['id'] = $check;
                    header("Location: ".$_SERVER["REQUEST_URI"]);
                }else
                    header('Location: /suser/');
            }
            else{
                $this->view->render('auth_view.php', 'administration/template_admin_view.php',
                    array(
                        'title' =>  'Сторінка адміністрування'
                    ));
            }
        }

    }

    public function action_logout(){
        unset($_SESSION['status']);
        header('Location: /suser/');
    }

    public function action_create_saler(){
        if(isset($_POST['createSaler']) && $_SESSION['status'] == 'administrator'){
            $login = $_POST['login'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if(Superuser::isExist($password, $login, $email) == false){
                $admin = new Admin($_SESSION['id']);
                $admin->createSaler($email, $login, $password);
            }
            header('Location: /suser/');
        }
    }

    public function action_search_route(){
        if(isset($_GET['searchRoute']) || $_SESSION['status'] == 'administrator'){
            $origin = explode(',', $_GET['origin']);
            $destination = explode(',', $_GET['destination']);

            $search = Route::search($this->clearString($origin[0]), $this->clearString($origin[1]), $this->clearString($destination[0]), $this->clearString($destination[1]));

            $this->view->render('list_view.php', 'administration/template_admin_view.php',
                array('data'    =>  $search,
                      'title'   =>  'Пошук маршруту'
                ));
        }else
            header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    public function action_delete_route(){
        if($_SESSION['status'] == 'administrator' && isset($_GET['id'])){
            $admin = new Admin($_SESSION['id']);
            $admin->delRoute($_GET['id']);
        }

        header("Location: ". $_SERVER['HTTP_REFERER']);
    }

    public function action_create_route(){
        if($_SESSION['status'] == 'administrator'){
            if(isset($_POST['addRoute'])){
                $schedules = [];
                if(isset($_POST['monday'])){
                    if(!empty($_POST['mtime']) && !empty($_POST['mdur'])){
                        array_push($schedules, array(
                            'weekday'   => $_POST['monday'],
                            'time'      => $_POST['mtime'],
                            'travelTime'=> $_POST['mdur']
                        ));
                    }
                }
                if(isset($_POST['tuesday'])){
                    if(!empty($_POST['ttime']) && !empty($_POST['tdur'])){
                        array_push($schedules, array(
                            'weekday'   => $_POST['tuesday'],
                            'time'      => $_POST['ttime'],
                            'travelTime'=> $_POST['tdur']
                        ));
                    }
                }
                if(isset($_POST['wednesday'])){
                    if(!empty($_POST['wtime']) && !empty($_POST['wdur'])){
                        array_push($schedules, array(
                            'weekday'   => $_POST['wednesday'],
                            'time'      => $_POST['wtime'],
                            'travelTime'=> $_POST['wdur']
                        ));
                    }
                }
                if(isset($_POST['thursday'])){
                    if(!empty($_POST['thtime']) && !empty($_POST['thdur'])){
                        array_push($schedules, array(
                            'weekday'   => $_POST['thursday'],
                            'time'      => $_POST['thtime'],
                            'travelTime'=> $_POST['thdur']
                        ));
                    }
                }
                if(isset($_POST['friday'])){
                    if(!empty($_POST['ftime']) && !empty($_POST['fdur'])){
                        array_push($schedules, array(
                            'weekday'   => $_POST['friday'],
                            'time'      => $_POST['ftime'],
                            'travelTime'=> $_POST['fdur']
                        ));
                    }
                }
                if(isset($_POST['saturday'])){
                    if(!empty($_POST['stime']) && !empty($_POST['sdur'])){
                        array_push($schedules, array(
                            'weekday'   => $_POST['saturday'],
                            'time'      => $_POST['stime'],
                            'travelTime'=> $_POST['sdur']
                        ));
                    }
                }
                if(isset($_POST['sunday'])){
                    if(!empty($_POST['sntime']) && !empty($_POST['sndur'])){
                        array_push($schedules, array(
                            'weekday'   => $_POST['sunday'],
                            'time'      => $_POST['sntime'],
                            'travelTime'=> $_POST['sndur']
                        ));
                    }
                }

                $o_place = $_POST['oplaces'];
                $d_place = $_POST['dplaces'];
                $price = $_POST['price'];
                $bc_price = $_POST['bc_price'];
                $trans = $_POST['transport'];
                $coords = $_POST['coords'];

                if($o_place != $d_place && ($o_place != '' && $d_place != '')) {
                    $admin = new Admin($_SESSION['id']);

                    if($admin->addRoute($trans, $coords, $o_place, $d_place, $price, $bc_price, $schedules))
                        $_SESSION['route_status'] = 'Маршрут було успішно додано!';
                    else
                        $_SESSION['route_status'] = 'Помилка при додаванні маршруту!';
                }
                else
                    $_SESSION['route_status'] = 'Невірно вказано точки маршруту!';
                header("Location: ".$_SERVER["REQUEST_URI"]);
            }else {
                $this->view->render('route_view.php', 'administration/template_admin_view.php',
                    array('title' => 'Створення маршуруту',
                        'places' => Route::getAllPlaces(),
                        'cities' => Route::getAllCities(),
                        'countries' => Route::getAllPCountries(),
                        'transports' => Transport::getAllTransport()
                    ));

                unset($_SESSION['route_status']);
            }
        }else
            header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    public function action_search_order(){
        if((isset($_POST['search']) || isset($_SESSION['search'])) && $_SESSION['status'] == 'saler'){

            $_SESSION['search'] = isset($_SESSION['search']) ? $_SESSION['search'] : $_POST['order_number'];

            $orderID = $this->clearString($_SESSION['search']);
            $order = null;

            if(Order::isExist($orderID))
                $order = new Order($orderID);

            $this->view->render('orders_list_view.php', 'administration/template_admin_view.php',
                array(
                    'title' =>  'Знайдене замовлення',
                    'data'  =>  $order
                ));

        }else
            header("Location: ".$_SERVER["REQUEST_URI"]);
    }

    public function action_confirm_pay(){
        if(isset($_POST['submitPay'])  && $_SESSION['status'] == 'saler'){
            Seller::confrimPay($_POST['orderId']);
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}