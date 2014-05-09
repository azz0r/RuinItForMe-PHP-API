<?php


class System_Password {


    private static $key = '14a4e4e1919096900b70279f3da924b243358073';


    public static function decrpyt($password) {
        return @openssl_decrypt ($password, 'BF-ECB', self::$key);
    }


    public static function encrypt($password) {
        if (empty($password)) {
            throw new System_Exception_Application(918);
        }

        return @openssl_encrypt ($password, 'BF-ECB', self::$key);
    }


    public static function generate($length = 8) {

        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand ((double) microtime () * 1000000);
        $i    = 0;
        $pass = null;

        while ($i <= $length) {
            $num  = rand () % 33;
            $tmp  = substr ($chars, $num, 1);
            $pass = $pass.$tmp;
            $i++;
        }

        return $pass;
    }


}