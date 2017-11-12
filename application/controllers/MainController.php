<?php

use core\Controller;
use application\models\user;
use SimpleLogger\File;

class MainController extends Controller
{
    /**
     *
     */
    public function actionIndex()
    {
        if (isset($_SESSION['udata'])) {
            $user = new User();
            $log = new File('log.txt');
            $ammount = iniPOST('payout', 0);
            if (($ammount > 0) && ($ammount < $_SESSION['udata']['balance'])) {
               if ($user->payout($ammount, $_SESSION['udata']['id'])) {
                   $log->log('1', 'Списано: '.$ammount);
                   absRedirect('/');
               } else
                   $log->log('1', 'Списать не удалось: '.$ammount);
            } else {
                $error = $user->validator->checkPrice($ammount);
                if($error != '')
                    $log->log('1', $error);
                else
                     $log->log('1', 'Недостаточно средств. Заказано: '.$ammount.' баланс: '.$_SESSION['udata']['balance']);
            }
            $this->render('index', ['data' => $user->find($_SESSION['udata']['id'])]);
        } else absRedirect('main/login');
    }

    /**
     *
     */
    public function actionLogin()
    {
        $login = iniPOST('login', '');
        if (!empty($login)) {
            $user = new User();
            $result = $user->getUserData([
                'username' => $login,
                'password_hash' => iniPOST('password')
            ]);
            if(is_array($result)) {
                $_SESSION['udata'] = $result;
                if(isset($_SESSION['udata'])) {
                    session_write_close();
                    absRedirect('main/index');
                    return;
                }
            }
        } else {
            if (isset($_SESSION['udata'])) {
                session_write_close();
                absRedirect('main/index');
                return;
            }
        }
        $this->render('login');
    }

    public static function actionLogout()
    {
        session_destroy();
        session_unset();
        absRedirect('/');
    }
}

