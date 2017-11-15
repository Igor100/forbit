<?php

use core\Controller;
use application\models\user;
use SimpleLogger\File;

class MainController extends Controller
{
    /**
     * управление и рендер баланса
     */
    public function actionIndex()
    {
        if (isset($_SESSION['udata'])) {
            $user = new User();
            $log = new File('log.txt');
            $ammount = helper::iniPOST('payout', 0);
            $error = $user->validator->checkPrice($ammount);
            if ($error == '') {
                if ($ammount < $_SESSION['udata']['balance']) {
                    if ($user->payout($ammount, $_SESSION['udata']['id'])) {
                        session_start(); // extra session_start
                        $_SESSION['udata']['balance'] = $_SESSION['udata']['balance'] - $ammount;
                        session_write_close(); // extra session_close
                        $log->log('1', 'Списано: ' . $ammount);
                    } else
                        $log->log('1', 'Списать не удалось: ' . $ammount);
                } else
                    $log->log('1', 'Недостаточно средств. Заказано: ' . $ammount . ' баланс: ' . $_SESSION['udata']['balance']);
            } else
                $log->log('1', $error);

            $this->render('index', ['data' => $user->find($_SESSION['udata']['id'])]);
        } else helper::absRedirect('main/login');
    }

    /**
     * авторизация
     */
    public function actionLogin()
    {
        $login = helper::iniPOST('login', '');
        if (!empty($login)) {
            if (!isset($_SESSION['udata'])) {
                $user = new User();
                $result = $user->getUserData([
                    'username' => $login,
                    'password_hash' => helper::iniPOST('password')
                ]);
                if (is_array($result))
                    $_SESSION['udata'] = $result;
            }
            if (isset($_SESSION['udata'])) {
                session_write_close();
                helper::absRedirect('main/index');
                return;
            }
        }
        $this->render('login');
    }

    /**
     * выход
     */
    public static function actionLogout()
    {
        session_destroy();
        session_unset();
        helper::absRedirect('/');
    }
}

