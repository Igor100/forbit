<?php

namespace core;

use PDO;

class Model
{
    private static $_pdo = null;

    /**
     * Model constructor.
     */
    private function __construct()
    {
        try {
            self::$_pdo = new PDO(
                'mysql:host=' . CONFIG_DBSERVER . ';port=' . CONFIG_DBPORT . ';dbname=' . CONFIG_DATABASE,
                CONFIG_DBUSER, CONFIG_DBPASSWORD, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]
            );
        } catch (\PDOException $e) {
            print $e->getMessage();
            die();
        }
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * @return Model|null|PDO
     */
    public static function getInstance()
    {
        if (self::$_pdo != null) {
            return self::$_pdo;
        }
        return new self;
    }

    // a proxy to native PDO methods
    public function __call($method, $args)
    {
        return call_user_func_array(array(self::$_pdo, $method), $args);
    }

    // a helper function to run prepared statements smoothly
    /**
     * @param $sql
     * @param array $args
     * @return \PDOStatement
     */
    public function run($sql, $args = [])
    {
        $stmt = self::$_pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    /* несколько запросов в транзакции
     * @param array $sql_args
     * [
     *    0 => ['sql' => 'insert ...', 'args' => [] ]
     *    1 => ['sql' => 'update ...', 'args' => ['id',] ]
     * ]
     */
    public function saveWithTrans($sql_args)
    {
        try {
            self::$_pdo->beginTransaction();
            foreach ($sql_args as $sql_arg) {
                $stmt = self::$_pdo->prepare($sql_arg['sql']);
                $stmt->execute($sql_arg['args']);
            }
            return self::$_pdo->commit();
        } catch (\Exception $e) {
            self::$_pdo->rollBack();
        }
    }

    /** гкнкерация условий запроса
     * @param array $data
     * @param string $glue
     * @return string
     */
    private static function combine($data = array(), $glue = ' AND ')
    {
        if (!isset($data)) return '1 = 1';
        return implode($glue, array_map(function ($k, $v) {
            if ($v === '0000-00-00 00:00:00') {
                $v = 'NULL';
            } elseif (!is_numeric($v)) {
                $v = "'$v'";
            }
            return "`$k` = $v";
        }, array_keys($data), $data));
    }

    /** генерация полей выборки запроса
     * @param  mixed $fieldNames
     */
    protected function createFieldNames($fieldNames)
    {
        if (is_string($fieldNames)) {
            if ($fieldNames != '*') {
                $fieldNames = str_replace('`', '', $fieldNames);
                $fieldNames = '`' . $fieldNames . '`';
            }
        } elseif (is_array($fieldNames)) {
            foreach ($fieldNames as &$fieldName) {
                $fieldName = str_replace('`', '', $fieldName);
                $fieldName = '`' . $fieldName . '`';
            }
            $fieldNames = implode(',', $fieldNames);
        }
        return $fieldNames;
    }

    /** выборка по полю id
     * @param integer $id
     * @param mixed $fieldNames
     * @return mixed
     */
    public function find($id, $fieldNames = '*')
    {
        $fieldNames = $this->createFieldNames($fieldNames);
        return $this->run(
            'SELECT ' . $fieldNames . ' FROM ' . get_called_class()::$TABLE_NAME . ' WHERE id = ?', [$id])->fetch();
    }

    /**
     * @param mixed $fieldNames
     * @return array
     */
    public function findAll($fieldNames = '*')
    {
        $fieldNames = $this->createFieldNames($fieldNames);
        return $this->run('SELECT ' . $fieldNames . ' FROM ' . get_called_class()::$TABLE_NAME, [])->fetchAll();
    }

    /**
     * @param array $conditions
     * @param mixed $fieldNames
     * @return string
     */
    public function findByAttrs($conditions = [], $fieldNames = '*')
    {
        $fieldNames = $this->createFieldNames($fieldNames);
        return $this->run(
            ' SELECT ' . $fieldNames .
            ' FROM `' . get_called_class()::$TABLE_NAME .
            '` WHERE ' . self::combine($conditions), [])->fetch();
    }

}