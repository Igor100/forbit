<?php

class Validator
{
    /**
     * @access protected
     * @var array
     */
    protected $_config;

    /**
     * Конструктор
     * @access public
     * @param array $config
     * @param string $setting
     */
    public function __construct($config)
    {
        $this->_config = $config;
    }

    /**
     * Проверка заполненности поля
     * @access public
     * @param string $text
     * @return string
     */
    public function noEmpty($text)
    {
        $text = trim($text);
        if (empty($text))
            return 'Поле не заполнено';
    }

    public function checkSelectField($text)
    {
        if ((trim($text) == 'Выбрать из списка') || (empty($text))) {
            return 'Поле не заполнено';
        }
    }

    /**
     * Проверка логина
     * @access public
     * @param string $login
     * @return string
     */
    public function checkLogin($login)
    {
        $login = trim($login);
        if (empty($login))
            return 'Нет логина';
        if (mb_strlen($login, 'utf-8') < $this->_config['min_login'])
            return 'Логин слишком короткий';
        if (mb_strlen($login, 'utf-8') > $this->_config['max_login'])
            return 'Логин слишком длинный';
    }


    /**
     * Проверка валидности пароля
     * @access public
     * @param string $password
     * @param string $repeat
     * @return string
     */
    public function checkPassword($password, $repeat)
    {
        if (empty($password))
            return 'Нет пароля';

        if (mb_strlen($password, 'utf-8') < $this->_config['min_password'])
            return 'Пароль должен содержать не менее ' . $this->_config['min_password'] . ' символов.';

        if (($password !== $repeat) && ((strlen($password) > 0) || (strlen($repeat) > 0)))
            return 'Пароли не совпадают';
    }

    /**
     * Проверка старого пароля
     * @access public
     * @param string $password
     * @param string $old_password
     * @return string
     */
    public function checkOldPassword($password, $old_password)
    {
        if (Crypt::hash($old_password, $password) !== $password)
            return 'Старый пароль указан неверно';
    }

    /**
     * Проверка валидности пинкода
     * @access public
     * @param string $pincode
     * @return string
     */
    public function checkPincode($pincode)
    {
        if (empty($pincode))
            return 'Нет пинкода';

        if (mb_strlen($pincode, 'utf8') != $this->_config['len_pincode'])
            return 'Невалидный пинкод';
    }

    /**
     * Проверка пинкода
     * @access public
     * @param string $pincode
     * @param string $old_pincode
     * @return string
     */
    public function comparePincode($pincode, $old_pincode)
    {
        if ($pincode !== $old_pincode)
            return 'Пинкод указан неверно';
    }

    /**
     * Проверка Email
     * @access public
     * @param string $email
     * @return string
     */

    public function checkEmail($email)
    {
        if (empty($email))
            return 'Не указан E-mail';
        if (!$this->validateEmail($email))
            return 'Невалидный E-mail';
    }

    /**
     * Проверка валидности Email
     * @access public
     * @param string $email
     * @return bool
     */
    public function validateEmail($email)
    {
        if (function_exists('filter_var'))
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        else
            return preg_match("/^[a-z0-9_\.-]+@([a-z0-9]+\.)+[a-z]{2,6}$/i", $email);
    }

    /**
     * Проверка Webmoney
     * @access public
     * @param string $webmoney
     * @return string
     */
    public function checkWebmoney($webmoney)
    {
        if (empty($webmoney))
            return 'Нет Вебмани';
        if (!preg_match("/^[R]\d{12}$/", $webmoney))   // - только R "/^[RZ]\d{12}$/"
            return 'Невалидный Вебмани';
    }

    /**
     * Проверка на ошибки
     * @access public
     * @param array $errors
     * @return bool
     */
    public function checkErrors($errors)
    {
        $errors = array_filter($errors, function ($v) {
            return (bool)$v;
        });
        return !empty($errors);
    }

    public function checkError($error)
    {
        return !empty($error);
    }

    /** Проверка корректности суммы
     * @param float $price
     * @return float string
     */
    public function checkPrice($price)
    {
        if (empty($price)) return 'Не указана сумма';
        if (!preg_match('/^(?:0|[1-9]\d*)(?:\.\d{2})?$/', $price))
            return 'Не правильная сумма';
        return '';
    }

}
