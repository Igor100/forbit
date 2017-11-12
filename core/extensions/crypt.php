<?php


class Crypt
{
    /**
     * Функция хэширования пароля
     * @param string $password - хэшируемый пароль
     * @return string
     */
    public static function hash($config, $password, $hash = '')
    {
        $logic  = $config['logic'];
        $round  = $config['iteration'];

        if(CRYPT_MD5 != 1)
        {
            trigger_error(__FUNCTION__ .'(). '
                .'The system does not support md5 algorithm',
                E_USER_WARNING);
            exit();
        }

        $string = 'abcdefghijklmnopqrstuvwxyz0123456789#$%.!-=(){}[]\/';

        if(empty($hash))
        {
            $string = str_pad('', 108, $string);
            $string = str_shuffle($string);
            $rand   = round(microtime(true) - floor(microtime(true)), 2) * 100;
            $salt   = substr($string, $rand, 8);
        }
        else
        {
            preg_match('~(.*)([a-f0-9]{32})$~ui', $hash, $out);

            if(empty($out[1]) && !empty($out[0]))
                $salt = substr($out[0], 0, 8);
            else
                $salt = substr(preg_replace('~[^'. preg_quote($string) .']~ui',
                    '-',
                    $hash),
                    0, 8);
        }

        $hash = crypt($password, '$1$'. $salt);

        $crypt = array( array(CRYPT_MD5,      '$1$'),
            array(CRYPT_BLOWFISH, '$2y$07$'),
            array(CRYPT_SHA256,   '$5$rounds=1000$'),
            array(CRYPT_SHA512,   '$6$rounds=1000$'),
        );

        $logic = (string)$logic;
        $cnt   = strlen($logic);
        $round = ($round < 1) ? 1 : $round;
        $round = ($round > 1000) ? 1000 : $round;

        while($round--)
        {
            for($i = 0; $i < $cnt; $i++)
            {
                if(empty($crypt[$logic[$i]][0]) || $crypt[$logic[$i]][0] != 1)
                    $crypt[$logic[$i]][1] = '$1$';

                preg_match("~[^\$]+$~i", $hash, $out);
                $hash = substr($out[0], 0, 8);
                $hash = crypt($password, $crypt[$logic[$i]][1] . $hash);
            }
        }

        return $salt . md5($hash);
    }
}