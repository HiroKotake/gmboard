<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Personal;
use teleios\gmboard\dao\UserBoard;

/**
 * ユーザページ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class UserPage extends Personal
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 個人向けメッセージ取得
     * @param  int     $userId [description]
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @param  string  $order  [description]
     * @return array           [description]
     */
    public function getPersonalMessage(int $userId, int $page = 0, int $number = 20, string $order = "DESC") : array
    {
        $offset = $page * $number;
        $daoUserBoard = new UserBoard();
        return $daoUserBoard->get($userId, $number, $offset, $order);
    }

    /**
     * 配列からシステム内部で使用するGameIdを覗く
     * @param  array $gameList [description]
     * @return array           [description]
     */
    public function eraseUb(array $gameList) : array
    {
        $erasedList = array();
        foreach ($gameList as $key => $list) {
            $erasedList[$key] = array();
            foreach ($list as $game) {
                unset($game["Ub"]);
                $erasedList[$key][] = $game;
            }
        }
        return $erasedList;
    }

    /**
     * ユーザページの初期画面で表示するデータを取得する
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function getPageData(int $userId) : array
    {
        // 共通ページデータ取得
        $data = $this->getPageDataCommon($userId);
        // 個人向けメッセージ取得
        $data["Message"] = $this->getPersonalMessage($userId);
        return $data;
    }
}
