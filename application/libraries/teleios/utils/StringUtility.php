<?php
namespace teleios\utils;

class StringUtility
{
    private $randamCodeBase = array(
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        '0','1','2','3','4','5','6','7','8','9',
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        '_','#','@','-','*'
    );

    /**
     * ランダムな文字列を取得する
     * @param  int    $maxNumber 欲しい文字数
     * @return string            生成された文字列
     */
    public static function getRundamCode(int $maxNumber) : string
    {
        $charNumber = count(self::$randamCodeBase) - 1;
        $randamCode = '';
        for ($i = 0; $i < $maxNumber; $i++) {
            $randamCode .= self::$randamCodeBase[mt_rand(0, $charNumber)];
        }
        return $randamCode;
    }

    /**
     * 平文パスワードをハッシュ化する
     * @param  string $passwd 平文パスワード
     * @return string         ハッシュ文字列
     */
    public static function getHashedPassword(string $passwd) : string
    {
        return password_hash($passwd, PASSWORD_BCRYPT, array('cost' => 12));
    }

    /**
     * 文字列の左側を他の文字列で埋めて、指定した文字数にする("str_pad + STR_PAD_LEFT"のエイリアス)
     * @param  string $base      入力文字列
     * @param  string $padString 埋める文字列
     * @param  int    $count     生成する文字列超
     * @return string            埋めた後の文字列
     */
    public static function lpad(string $base, string $padString, int $count) : string
    {
        return str_pad($base, $count, $padString, STR_PAD_LEFT);
    }

    /**
     * 文字列の右側を他の文字列で埋めて、指定した文字数にする("str_pad + STR_PAD_RIGHT"のエイリアス)
     * @param  string $base      入力文字列
     * @param  string $padString 埋める文字列
     * @param  int    $count     生成する文字列超
     * @return string            埋めた後の文字列
     */
    public static function rpad(string $base, string $padString, int $count) : string
    {
        return str_pad($base, $count, $padString, STR_PAD_RIGHT);
    }

    /**
     * 文字列の両端を他の文字列で埋めて、指定した文字数にする("str_pad + STR_PAD_BOTH"のエイリアス)
     * @param  string $base      入力文字列
     * @param  string $padString 埋める文字列
     * @param  int    $count     生成する文字列超
     * @return string            埋めた後の文字列
     */
    public static function bpad(string $base, string $padString, int $count) : string
    {
        return str_pad($base, $count, $padString, STR_PAD_BOTH);
    }

    /**
     * 入力された値がemailアドレスか確認する
     * @param  [type] $mailAddr 入力された文字列
     * @return bool             emailアドレスであれば true を、そうでない場合は false を返す
     */
    public function isMailAddr($mailAddr) : bool
    {
        $checked = filter_var($mailAddr, FILTER_VALIDATE_EMAIL);
        if ($checked) {
            return true;
        }
        return false;
    }
}
