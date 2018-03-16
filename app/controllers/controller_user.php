<?php

class Controller_user extends Controller
{
    public function action_registration(){
        if(!isset($_POST['submit'])) {
            $this->view->render('registration_view.php', 'template_view.php', array('title' => 'Регістрація користувача'));
        }else{
            $errors = [];
            $options = array(
                'title'         =>      'Регістрація користувача',
                'messages'      =>      '',
                'styles'        =>      array('formMessagesStyles.css')
            );

            if($_POST['login'] == ''){
                array_push($errors, 'Не введений логін');
            }
            if($_POST['email'] == ''){
                array_push($errors, 'Не введена електронна пошта');
            }
            if($_POST['pass'] == ''){
                array_push($errors, 'Не введений пароль');
            }

            if(count($errors) == 0){
                $login = $this->clearString($_POST['login']);
                $email = $this->clearString($_POST['email']);
                $pass = $this->clearString($_POST['pass']);

                $check = User::isExist($pass, $login, $email);

                if($check != false){
                    array_push($errors, 'Логін або пошта вже використовуються');
                    $options['messages'] = $errors;
                    $this->view->render('registration_view.php', 'template_view.php', $options);
                }
                else{
                    User::create($login, $email, $pass);

                    if(isset($_SESSION['user']))
                        unset($_SESSION['user']);
                    $_SESSION['user'] = User::isExist($pass, $login, $email);

                    header('Location:/');
                }
            }
            else{
                $options['messages'] = $errors;
                $this->view->render('registration_view.php', 'template_view.php', $options);
            }
        }
    }

    public function action_auth(){
        if(!isset($_POST['submit'])) {
            $this->view->render('auth_view.php', 'template_view.php', array('title' => 'Авторизація користувача'));
        }else {
            $errors = [];
            $options = array(
                'title'         =>      'Авторизація користувача',
                'messages'      =>      '',
                'styles'        =>      array('formMessagesStyles.css')
            );

            if($_POST['name'] == ''){
                array_push($errors, 'Не введений логін або електронна пошта');
            }
            if($_POST['pass'] == ''){
                array_push($errors, 'Не введений пароль');
            }

            if(count($errors) == 0){

                $name = $this->clearString($_POST['name']);
                $pass = $this->clearString($_POST['pass']);;

                $check = User::isExist($pass, $name);

                if(is_numeric($check)){

                    if(isset($_SESSION['user']))
                        unset($_SESSION['user']);
                    $_SESSION['user'] = $check;

                    header('Location:/');
                }else{
                    array_push($errors, 'Дані користувача не знайдено');
                    $options['messages'] = $errors;
                    $this->view->render('auth_view.php', 'template_view.php', $options);
                }
            }else{
                $options['messages'] = $errors;
                $this->view->render('auth_view.php', 'template_view.php', $options);
        }
        }
    }

    public function action_logout(){
        unset($_SESSION['user']);
        header('Location:/');
    }


    public function action_restore_pass(){
        if(!isset($_POST['submit'])) {
            $this->view->render('restore_view.php', 'template_view.php', array(
                'title' => 'Зміна паролю'
            ));
        }else{
            $options = array(
                'title' => 'Зміна паролю',
                'messages' => '',
                'styles' => array('formMessagesStyles.css')
            );

            if ($_POST['pass'] == $_POST['pass_confirm']) {
                $email = $this->clearString($_POST['email']);

                $uniqueCode = $this->generateCode(6);
                $_SESSION['confirmCode'] = $uniqueCode;
                $_SESSION['userEmail'] = $this->clearString($_POST['email']);
                $_SESSION['userPassword'] = $this->clearString($_POST['pass']);

                if (User::isExist('', $email) != false) {
                    $to = "<" . $email . ">";

                    $subject = "Зміна паролю";

                    $message = '<p style="font: 14px Arial, serif; color: #000000; ">Ваш код для зміни паролю: <span style="text-decoration: underline; font-weight: bold">' . $uniqueCode .'</span></p>';

                    $headers = "Content-type: text/html; charset=windows-1251 \r\n";
                    $headers .= "From: <support@tbooking.com>\r\n";
                    $headers .= "Reply-To: reply-to@tbooking.com\r\n";

                    mail($to, $subject, $message, $headers);
                    header('Location: /user/confirm_restore');

                } else {
                    $options['messages'] = 'Невірна електронна пошта';
                    $this->view->render('restore_view.php', 'template_view.php', $options);
                }
            } else {
                $options['messages'] = 'Невірне підтвердження паролю';
                $this->view->render('restore_view.php', 'template_view.php', $options);
            }
        }
    }

    private function generateCode($length){
        $stringCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $resultString = '';

        if(!is_int($length))
            return null;

        for($i = 0; $i < $length; $i++){
            $resultString .= $stringCharacters[mt_rand(0, strlen($stringCharacters) - 1)];
        }

        return $resultString;
    }

    public function action_confirm_restore()
    {
        if(!isset($_POST['codeSubmit'])) {
            $this->view->render('code_confirm_view.php', 'template_view.php', array(
                    'title' => 'Підтведження паролю'
                )
            );
        }else{
            $code = $this->clearString($_POST['code']);
            $options = array(
                'title' => 'Зміна паролю',
                'messages' => '',
                'styles' => array('formMessagesStyles.css')
            );

            if($code == $_SESSION['confirmCode']){

                $_SESSION['user'] = User::getIdByEmail($_SESSION['userEmail']);
                $user = new User($_SESSION['user']);
                $user->changePassword($_SESSION['userPassword']);

                unset($_SESSION['userEmail']);
                unset($_SESSION['userPassword']);

                header('Location: /');
            }else{
                $options['messages'] = 'Невірний код підтвердження';
                $this->view->render('code_confirm_view.php', 'template_view.php', $options);
            }
        }
    }

    public function action_profile(){
        if(isset($_SESSION['user'])) {
            $user = new User($_SESSION['user']);
            $this->view->render('profile_view.php', 'template_view.php', array(
                'title'     =>      'Профіль користувача',
                'styles'    =>      array('profilePageStyles.css'),
                'orders'    =>      $user->getOrders(),
                'userData'  =>      $user->get()
            ));
        }else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}