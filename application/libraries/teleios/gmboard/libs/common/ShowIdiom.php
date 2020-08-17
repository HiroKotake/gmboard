<?php
namespace teleios\gmboard\libs\common;

/**
 * ShowIdiom
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class ShowIdiom
{
    private   $idioms;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        require_once(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "../consts/Idiom.php");
        $this->idioms = $idiom;
    }

    /**
     * イデオムIDをメッセージに変換する
     * @param  int    $code イデオムID
     * @param  array  $vars イデオムIDに対応するメッセージ内に含まれる変数を置き換える文字列を含む配列
     * @return string       イデオムIDに対応するメッセージ
     */
    public function getIdiom(int $code, array $vars = null) : string
    {
        $varsCount = count($vars);
        $bases = array();
        for ($i = 0; $i < $varsCount ; $i++) {
            $bases[] = "%$i%";
        }
        return str_replace($bases, $vars, $this->idioms[$code]);
    }
}
