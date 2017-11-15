<?php

namespace application\models;

use core\model;

class User extends Model
{
    protected static $db;
    public static $TABLE_NAME = 'user';

    public $validator;
    // правило будет использовано при регистрации
    private $rules = [
        'table' => 'user', // Таблица регистрации
        'min_login' => 4,      // Минимальная длина логина
        'max_login' => 45,     // Максимальная длина логина
        'min_password' => 1,      // Минимальная длина пароля
    ];
    public $errors;

    /**
     * User constructor.
     */
    public function __construct()
    {
        self::$db = $this->getInstance();
        $this->validator = new \Validator($this->rules);
    }

    /**
     * валидация
     * @param array $data
     * @return bool
     */
    public function validate($data)
    {
        $errors = [
            1 => $this->validator->checkLogin($data['email']),
            2 => $this->validator->checkPassword($data['password'], $data['repeat']),
            3 => $this->validator->checkEmail($data['email']),
        ];

        if (false === $this->validator->checkErrors($errors))
            return true;
        else {
            $this->errors = $errors;
            return false;
        }
    }

    /**
     * выплата
     * @param $amount
     * @param $id_user
     * @return bool
     */
    public function payout($amount, $id_user)
    {
        $sql_with_args = [
            0 => [
                'sql' => 'update ' . self::$TABLE_NAME . ' set balance = balance - ? where id = ?',
                'args' => [$amount, $id_user]
            ],
        ];
        return $this->saveWithTrans($sql_with_args);
    }

    /**
     * @param $conditionals
     * @return string
     */
    public function getUserData($conditionals)
    {
        return $this->findByAttrs($conditionals);
    }
}