<?php
namespace teleios\utils;

/**
 * ID生成クラス
 * PG中でrandom_intを使用しているので、PHP7.0以前が実装されている場合は、別途、random_intライブラリを導入する。
 */
class Identifier
{
    const BASE_36 = 35;
    const BASE_16 = 15;
    const BASE_10 = 9;

    /**
     * PHPのバージョンを確認し、７．０．０以前のバージョンならばrandom_intライブラリを読み込む
     */
    private static function checkHasRandomInt() : void
    {
        // PHP 7.０以降か確認し、そうでなければrandom_intライブラリを読み込む
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            return;
        }
        require_once(__FILE__ . DIRECTORY_SEPARATOR .  "../libs/random_compat/random.php");
    }

    /**
     * 10進数(0から35)を３６進数１文字に変換する
     * @param  int    $num 10進数の ０ から 35
     * @return string      $num の値が 0 から 35 であれば36進数１文字を返し、それ以外は null を返す
     */
    private static function rep10to36(int $num) : string
    {
      if ($num < 0 && $num >= 36) {
         return null;
      }

       $stysix = array(
          "0","1","2","3","4","5","6","7","8","9",
          "a","b","c","d","e","f","g","h","i","j",
          "k","l","m","n","o","p","q","r","s","t",
          "u","v","w","x","y","z"
       );
       return $stysix[$num];
    }

    /**
     * 10進数数値を36進数文字列へ変換する
     * @param  int    $number 10進数数値
     * @return string         36進数文字列
     */
    public static function tento36(int $number) : string
    {
       $wss = array();
       while(true) {
          $rest = $number % 36;
          $amari = self::rep10to36($rest);
          $wss[] = $amari;
          $base = $number - $rest;
          if ($base == 0) {
              break;
          }
          $work = $base / 36;
          if ($work < 36) {
             $wss[] = self::rep10to36($work);
             break;
          }
          $number = $work;
       }

       $rss = array();
       while(($cra = array_pop($wss)) != null) {
          $rss[] = $cra;
       }
       $str = "";
       foreach ($rss as $char) {
          $str .= $char;
       }
       return $str;
    }

    /**
     * ３６進数１文字を10進数(0から35)に変換する
     * @param  string $char 36進数１文字(0-9,a-z)
     * @return int          36進数が適正であれば 0 から 35の範囲で数値を返し、それ以外はnull を返す
     */
    private static function rep36to10(string $char) : int
    {
       $stysix = array(
           "0" =>  0,"1" =>  1,"2" =>  2,"3" =>  3,"4" =>  4,"5" =>  5,"6" =>  6,"7" =>  7,"8" =>  8,"9" =>  9,
           "a" => 10,"b" => 11,"c" => 12,"d" => 13,"e" => 14,"f" => 15,"g" => 16,"h" => 17,"i" => 18,"j" => 19,
           "k" => 20,"l" => 21,"m" => 22,"n" => 23,"o" => 24,"p" => 25,"q" => 26,"r" => 27,"s" => 28,"t" => 29,
           "u" => 30,"v" => 31,"w" => 32,"x" => 33,"y" => 34,"z" => 35
       );
       if (strlen($char) > 1 || strlen($char) == 0) {
          return null;
       }
       $char = mb_strtolower($char);
       if (!array_key_exists($char, $stysix)) {
           return null;
       }

       return $stysix[$char];
    }

    /**
     * 36進数を10進数に変換する
     * @param  string $number36 36進数文字列
     * @return int              10進数数値
     */
    public static function tenfrom36(string $number36) : int
    {

       $ary = str_split($number36);
       $rss = array();
       while(($cra = array_pop($ary)) != null) {
          $rss[] = $cra;
       }
       $count = count($rss);
       $num = 0;
       for ($i = 0; $i < $count; $i++) {
          $wNum = self::rep36to10($rss[$i]);
          $base = pow(36, $i);
          $num += $wNum * $base;
       }
       return (int)$num;
    }

    /**
     * 指定した桁数のランダムな文字列を生成する
     * @param  int    $length 生成する文字数
     * @return string         生成された文字列
     */
    public static function buildRandomCode(int $length) : string
    {
        self::checkHasRandomInt();
        $code = "";
        for ($i = 0 ; $i < $length ; $i++) {
            $char = self::rep10to36(random_int(0, self::BASE_36));
            $switch = random_int(0,1);
            $code .= $switch ? $char : mb_strtoupper($char);
        }
        return $code;
    }

    /**
     * 指定された進数で構成され、指定された桁数を持つIDを文字列を生成する
     * @param int $length 生成するコード長
     * @param int $number 進数指定（15: 16進数、９： 10進数）
     * @return 生成された文字列
     */
    private static function buildRandomId(int $length, int $number) : string
    {
        self::checkHasRandomInt();
        $code = null;
        for ($index = 0; $index < $length; $index++) {
            $code .= dechex(random_int(0, $number));
        }
        return mb_strtoupper($code);
    }

    /**
     * １６進コードのみで構成され、指定された桁数を持つIDを文字列を生成する
     * @param int $length 生成するコード長
     * @return 生成された文字列
     */
    public static function buildRandomId16(int $length) : string
    {
        return self::buildRandomId($length, BASE_16);
    }

    /**
     * 0から９の数値で構成され、指定された桁数を持つIDを文字列を生成する
     * @param int $length 生成するコード長
     * @return 生成された文字列
     */
    public static function buildRandomId10(int $length) : string
    {
        return self::buildRandomId($length, BASE_10);
    }

    /**
     * １６進数で構成され、指定された桁数を持つIDを文字列を生成する
     * @param  int    $min 文字数の最低数を指定する。１２以下を指定した場合は指定値を無視し１２桁になり、IDの重複率は保証されない。
     *                     1,000,000回あたりの重複率は、１３文字では 5%、
     *                     14文字では 0.33% - 0.35%、
     *                     15文字では 0.006% - 0.02%、
     *                     16文字では、0.0015% - 0.006%、
     *                     17文字では、0% - 0.0001%、
     *                     18文字では、0% となるため IDの重複を避けるためには、１８文字以上を推奨する。
     *                     指定しない場合は１8がデフォルトとなる。
     * @return string      生成された文字列
     */
    public static function getRandomId16(int $min = 18) : string
    {
        self::checkHasRandomInt();
        $min = $min < 12 ? 12 : $min;
        $mtime = microtime(true);
        $mta = explode('.', $mtime);
        if (count($mta) < 2) {
            // 小数点部がない場合があるので、ない場合に補完
            $mta[] = '0000';
        }
        $split = str_split($mta[0] . $mta[1], 7);
        $upper = mb_strtoupper(dechex(strrev($split[0])));
        $lower = mb_strtoupper(dechex($split[1]));
        $randNumber = $min - strlen($upper) - strlen($lower);
        $rid = "";
        for ($index = 0; $index < $randNumber; $index++) {
            $rid .= mb_strtoupper(dechex(random_int(0,15)));
        }
        return $rid . $upper . $lower;
    }

    /**
     * 3６進数で構成され、指定された桁数を持つIDを文字列を生成する
     * 桁数に１5以上を指定することで、ほぼ重複しないIDを生成できるので、
     * 重複チェック処理を実行せずに使用することが出来る。
     *
     * @param  integer $min 生成するIDの桁数 (ディフォルト１６)。10以下を指定した場合は、強制的に１０桁になる。
     * @return string       生成されたID
     */
    public static function getRandomId(int $min=16) : string
    {
        self::checkHasRandomInt();
        $min = $min < 10 ? 10 : $min;
        $mtime = microtime(true);
        $mta = explode('.', $mtime);
        if (count($mta) < 2) {
            // 小数点部がない場合があるので、ない場合に補完
            $mta[] = '0';
        }
        $workStr = $mta[0] . $mta[1];
        $baseStr = mb_strtoupper(self::tento36($workStr));
        $randNumber = $min - strlen($baseStr);
        $rid = "";
        for ($index = 0; $index < $randNumber; $index++) {
            $rid .= mb_strtoupper(self::rep10to36(random_int(0, self::BASE_36)));
        }
        return $rid . $baseStr;
    }

    /**
     * IDを難読化する
     * @param  string $code ID文字列（０−９、A-Zで構成された文字列)
     * @return string       難読化ID
     */
    public static function sftEncode(string $code) : string
    {
        if (empty($code)) {
            return "";
        }
        self::checkHasRandomInt();
        $leng = strlen($code);
        $randomMax = round($leng/2, 0,PHP_ROUND_HALF_DOWN);
        if ($randomMax > 33) {
            $randomMax = 33;
        }
        $shift = random_int(2, $randomMax);
        $str1 = substr($code, 0, $shift);
        $str2 = substr($code, $shift);
        $baseStr = $str2 . $str1;
        $spPoint = round($leng/2, 0, PHP_ROUND_HALF_UP);
        $strSplits = str_split($baseStr, $spPoint);
        $fix = strtoupper(self::rep10to36($shift));
        if (random_int(0,1)) {
            // 先頭：2-(36進数:桁数-1:Max 35)　尾文字が"0"の場合に有効
            return $strSplits[0] . $fix . '0' . strrev($strSplits[1]);
        }
        // 後備：2-(36進数:桁数-1:Max 35) 頭文字が"1"の場合に有効
        return $strSplits[0] . '1' . $fix . strrev($strSplits[1]);
    }

    /**
     * 難読化したIDを復調する
     * @param  string $code 難読化ID
     * @return string       復調したID
     */
    public static function sftDecode(string $code) : string
    {
        if (empty($code)) {
            return "";
        }
        $leng = strlen($code);
        $spPoint = round($leng/2, 0, PHP_ROUND_HALF_UP);
        $strSplits = str_split($code, $spPoint);
        $top = substr($strSplits[0], -1);
        $end = substr($strSplits[1], 0, 1);
        $str1 = substr($strSplits[0], 0, -1);
        $str2 = strrev(substr($strSplits[1], 1));
        $baseStr = $str1 . $str2;
        $baseLeng = strlen($baseStr);
        $shift = self::rep36to10($top);
        if ($top == 1) {
            $shift = self::rep36to10($end);
        }
        $str3 = substr($baseStr, -$shift);
        $str4 = substr($baseStr, 0, $baseLeng - $shift);
        return $str3 . $str4;
    }
}
