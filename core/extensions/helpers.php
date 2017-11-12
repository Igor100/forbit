<?php

class Calendar
{
    public function period_filter($period)
    {
        $date = array();

        if($period == 'all')
        {
            $date['from'] = '';
            $date['to'] = '';
        }
        elseif($period == 'today')
        {
            $date['from'] = date('Y-m-d');
            $date['to']   = date('Y-m-d');
        }
        elseif($period == 'yesterday')
        {
            $date['from'] = date('Y-m-d', time() - 3600 * 24);
            $date['to']   = date('Y-m-d', time() - 3600 * 24);
        }
        elseif($period == 'previous_week' && date('w') == 1)
        {
            $date['from'] = date('Y-m-d', strtotime("Monday") - 3600 * 24 * 7);
            $date['to']   = date('Y-m-d', strtotime("last Sunday"));
        }
        elseif($period == 'previous_week')
        {
            $date['from'] = date('Y-m-d', strtotime("last Monday") - 3600 * 24 * 7);
            $date['to']   = date('Y-m-d', strtotime("last Monday") - 3600 * 24 );
        }
        elseif($period == 'current_week' && date('w') == 1)
        {
            $date['from'] = date('Y-m-d');
            $date['to']   = date('Y-m-d');
        }
        elseif($period == 'current_week')
        {
            $date['from'] = date('Y-m-d', strtotime("last Monday"));
            $date['to']   = date('Y-m-d');
        }
        elseif($period == 'previous_month')
        {
            $date['from'] = date('Y-m-d', time() - 3600 * 24 * (date('d') - 1 + date("t", time() - intval(date("j")) * 86400 + 1)));
            $date['to']   = date('Y-m-d', time() - 3600 * 24 * date('d'));
        }
        elseif($period == 'current_month')
        {
            $date['from'] = date('Y-m-d', time() - 3600 * 24 * (date('d') - 1));
            $date['to']   = date('Y-m-d');
        }
        elseif($period == 'current_year')
        {
            $date['from'] = date('Y') . '-01-01';
            $date['to']   = date('Y-m-d');
        }

        return $date;
    }
}

if (!function_exists('array_column')) {
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }

}


/**
 * Инициализация GET.
 * @access public
 * @param string $key
 * @param string $default
 * @return string
 */
function iniGET($key, $default = '')
{
    return (!empty($_GET[$key])) ? $_GET[$key] : $default;
}

/**
 * Инициализация POST.
 * @access public
 * @param string $val
 * @param string $default
 * @return string
 */
function iniPOST($key, $default = '')
{
    return (isset($_POST[$key]) && $_POST[$key] !== '')
        ? $_POST[$key] : $default;
}

/**
 * Обработка переменных для вывода в поток
 * @access public
 * @param string|array $data
 * @return string|array
 */
if (!function_exists('htmlChars'))
{
    function htmlChars($data)
    {
        if(is_array($data))
            $data = array_map("htmlChars", $data);
        else
            $data = htmlspecialchars($data);

        return $data;
    }
}

/**
 * Разбор системных сообщений
 * @access public
 * @param string $info
 * @param string $separator
 * @return string
 */
function prepareInfo($info = '', $separator = '<br>')
{
    if(is_array($info))
        return implode($separator, $info);
    elseif(!empty($info))
        return $info;
    else
        return '&nbsp;';
}

/**
 * Формирование ссылок.
 * @access public
 * @param array $arg
 * @return string
 */
function href()
{
    $arg = func_get_args();

    $host = defined('ADMIN') ? '/admin/' : '/';

    if(empty($arg[0]))
        return $host;

    $arg   = is_array($arg[0]) ? $arg[0] : $arg;
    $param = '';

    foreach($arg as $var)
    {
        $param .= $var .'&';
    }

    return $host .'?'. rtrim($param, '&');
}

/**
 * Активация ссылок
 * @access public
 * @param string $return
 * @param string|array $param
 * @param bool|string|int $default
 * @return string
 */
function activeLink($param, $return, $default = false)
{
    $value = iniGET($param);

    if($default && $value === '')
        return 'class="active"';

    if(is_array($return) && in_array($value, $return))
        return 'class="active"';

    return ($value === $return) ? 'class="active"' : NULL;
}

/**
 * Активный класс
 * @access public
 * @param string $param
 * @param string $return
 * @return string
 */
function activeClass($param, $return)
{
    return ($param == $return) ? 'class="active"' : NULL;
}

/**
 * Возврат чекбоксов
 * @access public
 * @param string|int $id
 * @param string|int $return
 * @return string
 */
function returnCheck($id, $return)
{
    return ($id == $return)? 'checked="checked"' : NULL;
}

/**
 * Возврат селектов
 * @access public
 * @param string|int $id
 * @param string|int $return
 * @return string
 */
function returnSelect($id, $return)
{
    return ($id == $return)? 'selected="selected"' : NULL;
}

/**
 * Перенаправление
 * @access public
 * @param array $arg
 * @return void
 */
function reDirect()
{
    $arg = func_get_args();
    $arg = !empty($arg) && is_array($arg[0]) ? $arg[0] : $arg;
    if(!empty($arg))
        header('location: '. href($arg));
    else
        header('location: '. str_replace("/index.php", "", $_SERVER['HTTP_REFERER']));
    exit();
}
function absRedirect($url) {
    header('location: '. $url);
    exit();
}

/**
 * Сохраняет данные для передачи между страницами
 * @param string $name
 * @param string $text
 */
function setFlashData($name, $value)
{
    if (empty($_SESSION['flashData']))
        $_SESSION['flashData'] = array();

    $_SESSION['flashData'][$name] = $value;
}

/**
 * Возвращает данные, сохранённые для передачи между страницами
 * @param string $name
 * @param mixed $default
 * @return string
 */
function getFlashData($name, $default = '', $clear = true)
{
    if(empty($_SESSION['flashData'][$name]))
        return $default;

    $data = $_SESSION['flashData'][$name];

    if ($clear)
        unset($_SESSION['flashData'][$name]);

    return $data;
}

/**
 * Форматирование даты
 * @param string $date
 * @param string $time
 * @param string $sep
 * @param bool   $ms
 * @return string
 */
function dateForm($date, $time = false, $sep = ' ', $ms = true)
{
    global $month_string;

    $day = substr($date,8,2);
    if($ms)
        $month = $month_string[substr($date,5,2)];
    else
        $month = substr($date,5,2);

    $year = substr($date,0,4);

    if($time)
        $time = ' ' . substr($date,11);

    return $day . $sep . $month .$sep . $year . $time;
}

/**
 * Возвращает массив дат в интервале
 * @param string $start
 * @param string $start
 * @return array
 */
function dateInterval($start, $end)
{
    $start = strtotime($start);
    $end   = strtotime($end);
    $interval = [];
    for($d = $start; $d <= $end ; $d = strtotime('tomorrow', $d))
        $interval[] = date('Y-m-d', $d);

    return $interval;
}

/**
 * Денежный формат
 * @param string $data
 * @return string|array
 */
function finFormat($data)
{
    if(is_array($data))
        $data = array_map("finFormat", $data);
    else
        $data = sprintf("%01.2f", $data);

    return $data;
}

function emptyFilter($val)
{
    return (bool)$val;
}

/**
 * Проверка на AJAX
 * @return bool
 */
function checkAjax()
{
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/**
 * Генерация 404
 * @access public
 * @return void
 */
/*function create404()
{
    if(true === CONFIG_DEBUG)
        trigger_error('Not found', E_USER_ERROR);

    header("HTTP/1.1 404 Not Found");
    include DIR_TEMPLATE .'/404.html';
    exit();
}

function create403()
{
    if(true === CONFIG_DEBUG)
        trigger_error('Not found', E_USER_ERROR);

    header("HTTP/1.1 403 Forbidden");
    include DIR_TEMPLATE .'/403.html';
    exit();
}*/


/**
 * Формирует пути к картинкам
 * @param mixed $src
 * @param mixed $path
 * @return string
 */
if (!function_exists('src'))
{
    function src($src = '', $path = '')
    {
        switch($path)
        {

            case 'photo' :
                return 'photo/'. $src .'.jpg';

            case 'prew' :
                return 'photo/prew/'. $src .'.jpg';

            case 'css' :
                return 'skins/css/'. $src .'.css';

            case 'scrins_big' :
                return 'scrins/'. $src .'.jpg';

            case 'scrins_small' :
                return 'scrins/small/'. $src .'.jpg';

            case 'js' :
                return  'skins/js/'. $src .'.js';

            case 'htc' :
                return 'skins/'. $src .'.htc';

            case 'swf' :
                return 'skins/'. $src .'.swf';

            default :
                return '/skins/images/'. $src;
        }
    }
}
///////////////////////////////////////////////////////////////////////////
/**
 * Generates a random string of a given type and length.
 *
 * $str = Text::random(); // 9 character random string
 *
 * The following types are supported:
 *
 * alnum
 * :  Upper and lower case a-z, 0-9 (default)
 *
 * alpha
 * :  Upper and lower case a-z
 *
 * hexdec
 * :  Hexadecimal characters a-f, 0-9
 *
 * distinct
 * :  Uppercase characters and numbers that cannot be confused
 *
 * You can also create a custom type by providing the "pool" of characters
 * as the type.
 *
 * @param   string   a type of pool, or a string of characters to use as the pool
 * @param   integer  length of string to return
 * @return  string
 * @uses    UTF8::split
 */
function random($type = NULL, $length = 9)
{
    if ($type === NULL)
    {
        // Default is to generate an alphanumeric string
        $type = 'alnum';
    }

    switch ($type)
    {
        case 'alnum':
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            break;
        case 'alpha':
            $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        case 'hexdec':
            $pool = '0123456789abcdef';
            break;
        case 'numeric':
            $pool = '0123456789';
            break;
        case 'nozero':
            $pool = '123456789';
            break;
        case 'distinct':
            $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
            break;
    }

    // Split the pool into an array of characters
    $pool = str_split($pool, 1);

    // Largest pool key
    $max = count($pool) - 1;

    $str = '';
    for ($i = 0; $i < $length; $i++)
    {
        // Select a random character from the pool and add it to the string
        $str .= $pool[mt_rand(0, $max)];
    }

    // Make sure alnum strings contain at least one letter and one digit
    if ($type === 'alnum' AND $length > 1)
    {
        if (ctype_alpha($str))
        {
            // Add a random digit
            $str[mt_rand(0, $length - 1)] = chr(mt_rand(48, 57));
        }
        elseif (ctype_digit($str))
        {
            // Add a random letter
            $str[mt_rand(0, $length - 1)] = chr(mt_rand(65, 90));
        }
    }

    return $str;
}

/**
 * Фильтрует значение
 *
 * @param mixed $value Значение
 * @return mixed
 */
function filterParameter($value)
{
    if (is_array($value))
    {
        foreach ($value as $key => $val)
        {
            $value[$key] = filterParameter($val);
        }

        return $value;
    }

    $value = trim($value);

    if (get_magic_quotes_gpc())
    {
        $value = stripslashes($value);
    }

    //$value = mysql_real_escape_string($value);

    $value = htmlspecialchars($value);

    return $value;
}

/**
 * Возвращает POST, GET, COOKIE значения параметра без проверки на XSS
 *
 * @param string $name Имя параметра
 * @param mixed $default Значение по умолчанию
 * @return mixed
 */
function getParameterNoXss($name, $default = null)
{
    if (isset($_POST[$name]))
    {
        $value = filterParameterNoXss($_POST[$name]);
    }

    if (isset($_GET[$name]))
    {
        $value = filterParameterNoXss($_GET[$name]);
    }

    if (isset($_COOKIE[$name]))
    {
        $value = filterParameterNoXss($_COOKIE[$name]);
    }

    if (isset($value) == false) return $default;

    if (in_array($name, array('id', 'number', 'perpage')))
    {
        $value = (int) $value;

        if ($value < 1) $value = $default;
    }
    elseif ($name == 'period' && in_array($value, array('today', 'yesterday', 'current_week', 'previous_week', 'current_month', 'previous_month')) == false)
    {
        $value = $default;
    }

    return $value;
}

/**
 * Фильтрует значение без XSS проверки
 *
 * @param mixed $value Значение
 * @return mixed
 */
function filterParameterNoXss($value)
{
    if (is_array($value))
    {
        foreach ($value as $key => $val)
        {
            $value[$key] = filterParameterNoXss($val);
        }

        return $value;
    }

    $value = trim($value);

    if (get_magic_quotes_gpc())
    {
        $value = stripslashes($value);
    }

    return $value;
}

function endingsForm($n, $form1, $form2, $form5)
{
    $n = abs($n) % 100;
    $n1 = $n % 10;

    if ($n > 10 && $n < 20)
        return $form5;
    if ($n1 > 1 && $n1 < 5)
        return $form2;
    if ($n1 == 1)
        return $form1;

    return $form5;
}

# Запомнить переменную
function varSave($var, $clear = false)
{
    if($var === false)
    {
        unset($_SESSION['save']);
        return NULL;
    }

    if(!isset($_SESSION['save'][$var]))
        $_SESSION['save'][$var] = NULL;

    if($clear === true)
        unset($_SESSION['save'][$var]);
    else
    {
        global $POST;

        if(!empty($_POST))
            $_SESSION['save'][$var] = $POST[$var];
    }

    return !empty($_SESSION['save'][$var]) ? $_SESSION['save'][$var] : NULL;
}

/**
 * Возвращает POST, GET, COOKIE значения параметра
 *
 * @param string $name Имя параметра
 * @param mixed $default Значение по умолчанию
 * @return mixed
 */
function getParameter($name, $default = null)
{
    if (isset($_POST[$name]))
    {
        $value = filterParameter($_POST[$name]);
    }

    if (isset($_GET[$name]))
    {
        $value = filterParameter($_GET[$name]);
    }

    if (isset($_COOKIE[$name]))
    {
        $value = filterParameter($_COOKIE[$name]);
    }

    if (isset($value) == false) $value = $default;

    if (in_array($name, array('id', 'number', 'perpage')))
    {
        $value = (int) $value;

        if ($value < 1) $value = $default;
    }
    elseif ($name == 'period' && in_array($value, array('today', 'yesterday', 'current_week', 'previous_week', 'current_month', 'previous_month')) == false)
    {
        $value = $default;
    }
    elseif ($name == 'period' && in_array($value, array('today', 'yesterday', 'current_week', 'previous_week', 'current_month', 'previous_month')))
    {
        $Calendar = new Calendar();
        $value = $Calendar->period_filter($value);
    }

    return $value;
}

function limitZero($data, $mod = false)
{
    return ($data > 0) ? $data : (($mod) ? 1 : 0);
}

function filterUnsafeAttributes($data, $safe_attributes)
{
    foreach ($data as $name => $value)
    {
        if (in_array($name, $safe_attributes) == false) unset($data[$name]);
    }

    return $data;
}

/**
 * Builds a map (key-value pairs) from a multidimensional array or an array of objects.
 * The `$from` and `$to` parameters specify the key names or property names to set up the map.
 * Optionally, one can further group the map according to a grouping field `$group`.
 *
 * For example,
 *
 * ~~~
 * $array = [
 *     ['id' => '123', 'name' => 'aaa', 'class' => 'x'],
 *     ['id' => '124', 'name' => 'bbb', 'class' => 'x'],
 *     ['id' => '345', 'name' => 'ccc', 'class' => 'y'],
 * ];
 *
 * $result = ArrayHelper::map($array, 'id', 'name');
 * // the result is:
 * // [
 * //     '123' => 'aaa',
 * //     '124' => 'bbb',
 * //     '345' => 'ccc',
 * // ]
 *
 * $result = ArrayHelper::map($array, 'id', 'name', 'class');
 * // the result is:
 * // [
 * //     'x' => [
 * //         '123' => 'aaa',
 * //         '124' => 'bbb',
 * //     ],
 * //     'y' => [
 * //         '345' => 'ccc',
 * //     ],
 * // ]
 * ~~~
 *
 * @param array $array
 * @param string|\Closure $from
 * @param string|\Closure $to
 * @param string|\Closure $group
 * @return array
 */
function arrayMap($array, $from, $to, $group = null)
{
    $result = array();
    foreach ($array as $element) {
        $key = $element[$from];
        $value = $element[$to];
        if ($group !== null) {
            $result[$element][$group][$key] = $value;
        } else {
            $result[$key] = $value;
        }
    }

    return $result;
}

/**
 * Функция транслитерации.
 * @param string $string
 * @return string
 */
function translateWord($string)
{
    $string = preg_replace('#[^0-9a-zа-яё\s_-]#ui', '', $string);

    $ru = array(
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё',
        'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М',
        'Н', 'О', 'П', 'Р', 'С', 'Т', 'У',
        'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ы',
        'Ъ', 'Ь', 'Э', 'Ю', 'Я',
        'а', 'б', 'в', 'г', 'д', 'е', 'ё',
        'ж', 'з', 'и', 'й', 'к', 'л', 'м',
        'н', 'о', 'п', 'р', 'с', 'т', 'у',
        'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ы',
        'ъ', 'ь', 'э', 'ю', 'я', ' '
    );

    $en = array(
        "A",  "B",  "V",  "G",  "D",  "E",   "E",
        "ZH", "Z",  "I",  "J",  "K",  "L",   "M",
        "N",  "O",  "P",  "R",  "S",  "T",   "U",
        "F" , "X" , "CZ", "CH", "SH", "SHH", "Y",
        "",   "",   "E", "YU", "YA",
        "a",  "b",  "v",  "g",  "d",  "e",   "e",
        "zh", "z",  "i",  "j",  "k",  "l",   "m",
        "n",  "o",  "p",  "r",  "s",  "t",   "u",
        "f" , "x" , "cz", "ch", "sh", "shh", "y",
        "",    "",  "e", "yu", "ya", "-"
    );

    $string = str_replace($ru, $en, $string);
    return  strtolower($string);
}