<?php

/**
 * Maximal Number System (Case Sensitive)
 *
 */

namespace Opu\Core\Helpers\Opuu;

class Maximal
{
    /**
     * All Charecters
     *
     * @var string all numbers and characters (case sensitive)
     */
    private $allChar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public $serverID = 'MAXIMAL';

    public function __construct($serverID = 'MAXIMAL')
    {
        $this->serverID = $serverID;
    }

    /**
     * Check Data Size
     *
     * @param string $any
     * @return void
     */
    private function tooBig($any)
    {
        if (strlen($any) > 16) {
            throw new \ErrorException('decimal value of given number is ' . strlen($any) . ' character long. expected >=16).');
        }
    }

    /**
     * Decimal to any number system with the base number.
     *
     * @param string $num the decimal number to convert
     * @param string $b the base of desired number system
     * @return string the desired number
     */
    private function decimal2base($num, $b = 62, $limit = true)
    {
        if ($limit) {
            $this->tooBig($num);
        }

        $r = $num % $b;
        $res = $this->allChar[$r];
        $q = floor($num / $b);
        while ($q) {
            $r = $q % $b;
            $q = floor($q / $b);
            $res = $this->allChar[$r] . $res;
        }
        return $res;
    }

    /**
     * Any number system to decimal with the base number.
     *
     * @param string $string the string of number to convert
     * @param string $b the base of the provided strings number system
     * @return string the desired decimal number
     */
    public function base2decimal($string, $b = 62)
    {
        $limit = strlen($string);
        $res = strpos($this->allChar, $string[0]);
        for ($i = 1; $i < $limit; $i++) {
            $res = $b * $res + strpos($this->allChar, $string[$i]);
        }
        return sprintf('%d', floatval($res));
    }

    /**
     * Maximal to Decimal Conversion
     *
     * @param string $maximal the maximal number string
     * @return string the decimal value of maximal number
     */
    public function max2dec($maximal)
    {
        return $this->base2decimal($maximal, 62);
    }

    /**
     * Maximal to Hexadecimal Conversion
     *
     * @param string $maximal the maximal number string
     * @return string the hexadecimal value of maximal number
     */
    public function max2hex($maximal)
    {
        $decimal = $this->max2dec($maximal);
        return $this->decimal2base($decimal, 16);
    }

    /**
     * Maximal to Binary Conversion
     *
     * @param string $maximal the maximal number string
     * @return string the binary value of maximal number
     */
    public function max2bin($maximal)
    {
        $decimal = $this->max2dec($maximal);
        return $this->decimal2base($decimal, 2);
    }

    /**
     * Maximal to Octal Conversion
     *
     * @param string $maximal the maximal number string
     * @return string the octal value of maximal number
     */
    public function max2oct($maximal)
    {
        $decimal = $this->max2dec($maximal);
        return $this->decimal2base($decimal, 8);
    }

    /**
     * Decimal to Maximal Conversion
     *
     * @param string $decimal the decimal number
     * @return string the maximal value of decimal number
     */
    public function dec2max($decimal)
    {
        return $this->decimal2base($decimal, 62);
    }

    /**
     * Binary to Maximal Conversion
     *
     * @param string $binary the binary number
     * @return string the maximal value of binary number
     */
    public function bin2max($binary)
    {
        $decimal = $this->base2decimal($binary, 2);
        return $this->decimal2base($decimal, 62);
    }

    /**
     * Hexadecimal to Maximal Conversion
     *
     * @param string $hex the hex number
     * @return string the maximal value of hex number
     */
    public function hex2max($hex)
    {
        $decimal = $this->base2decimal($hex, 16);
        return $this->decimal2base($decimal, 62);
    }

    /**
     * Octal to Maximal Conversion
     *
     * @param string $octal the octal number
     * @return string the maximal value of octal number
     */
    public function oct2max($octal)
    {
        $decimal = $this->base2decimal($octal, 8);
        return $this->decimal2base($decimal, 62);
    }

    /**
     * GUID: Unique ID (40 Characters)
     *
     * @return string 40 characters long
     */
    public function GUID1()
    {
        $id = sha1(rand(100000, 999999) . $this->GUID2() . rand(100000, 999999) . $this->serverID . rand(100000, 999999));
        return $id;
    }

    /**
     * GUID: Unique ID (20 Characters)
     *
     * @return string 20 characters long
     */
    public function GUID2()
    {
        $id = str_replace('.', '', microtime(true)) . rand(10000000000, 99999999999);
        $parts = str_split($id, 14);
        $part1 = $parts[0];
        $part2 = $this->dec2max($parts[1]);
        $part3 = (date('ym'));
        $result = $part1 . $part3 . $part2;
        return substr(strrev($result), 0, 20);
    }

    /**
     * GUID: Unique ID (14 Characters)
     *
     * @return string 14 characters long
     */
    public function GUID3()
    {
        $id = $this->dec2max(rand(11111, 99999)) . $this->dec2max(str_replace('.', '', microtime(true))) . $this->dec2max(rand(11111, 99999));
        return rand(1, 9) . strrev(substr($id, 0, 13));
    }

    /**
     * GUID: Unique ID (32 Characters)
     *
     * @return string 32 characters long
     */
    public function GUID4()
    {
        $id = md5(uniqid() . $this->GUID2() . $this->serverID);
        return $id;
    }

    /**
     * GUID: Unique ID
     *
     * @param string $data data to be used in this id
     * @param integer $len lenght of the id
     * @return string the id
     */
    public function GUID5($data = null, $len = 16)
    {
        if ($data === null) {
            $data = $this->String(10);
        }
        $id = uniqid() . $this->GUID1() . $data . $this->serverID;

        $binhash = md5($id, true);
        $numhash = unpack('N2', $binhash);
        $hash = $numhash[1] . $numhash[2];
        if ($len && is_int($len)) {
            $hash = substr($hash, 0, $len);
        }
        return $hash;
    }

    /**
     * GUID: Unique ID
     *
     * @return string unique id
     */
    public function GUID6()
    {

        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtolower(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $guidv4 =
                substr($charid, 0, 8) . $hyphen .
                substr($charid, 8, 4) . $hyphen .
                substr($charid, 12, 4) . $hyphen .
                substr($charid, 16, 4) . $hyphen .
                substr($charid, 20, 12);
            return $guidv4;
        }
    }

    /**
     * GUID: Unique ID with openssl
     *
     * @return string unique id
     */
    public function GUID7()
    {
        if (function_exists('openssl_random_pseudo_bytes') === true) {

            $data = openssl_random_pseudo_bytes(16);

            assert(strlen($data) == 16);

            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        } else {
            throw new \ErrorException('GUID7 requires openssl extension.');
        }
    }

    /**
     * String: Random maximal string
     * @param integer $length lenght of the string (default 5)
     * @return string
     */
    public function String($length = 5)
    {
        $charactersLength = strlen($this->allChar);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $this->allChar[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * GUID: Unique ID with timestamp
     *
     * @return string 18 character long unique id
     */
    public function GUID0()
    {
        $rand = rand(111111, 999999);
        $time = rand(1, 9);
        $waiter = '0.00' . $time;
        sleep($waiter);
        $id = str_replace('.', '', microtime(true)) . $rand;
        return substr($id, 0, 18);
    }

    /**
     * GUID: Unique ID
     *
     * @return string 8 character long unique id
     */
    public function UID()
    {
        $rand1 = rand(10000, 99999);
        $rand2 = rand(10000, 99999);
        $rand3 = rand(10000, 99999);
        $result = $rand1 . $rand2 . $rand3;
        return substr($this->dec2max($result), 0, 8);
    }

    /**
     * GUID: Unique ID
     *
     * @return string 8 character long unique id
     */
    public function GUID()
    {
        return substr($this->decimal2base($this->GUID5(), 62, false), 0, 8);
    }
}