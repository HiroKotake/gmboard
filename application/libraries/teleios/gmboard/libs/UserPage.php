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
    private $daoUserBoard;
    public function __construct()
    {
        parent::__construct();
        $this->daoUserBoard = new UserBoard();
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
        return $this->daoUserBoard->get($userId, $number, $offset, $order);
    }

    /**
     * メッセージの総数を取得する
     * @return int メッセージ総数
     */
    public function count() : int
    {
        return $this->daoUserBoard->count();
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
        $data["MsgTotal"] = $this->count();
        return $data;
    }

    public function getGroupSearchPage(int $userId, string $obfGameId, string $groupName, int $pageNumber) : array
    {
        // ゲームID復号化
        $gameId = $this->trnasAliasToGameId($obfGameId);
        // 共通ページデータ取得
        $data = $this->getPageDataCommon($userId);
        // グループ検索
        $searchResult = $this->searchByGroupName($gameId, $groupName, $userId, $pageNumber, LINE_NUMBER_SEARCH);
        $data['List'] = $searchResult['GroupList'];
        $data['MaxLineNumber'] = LINE_NUMBER_SEARCH;
        $data['TotalNumber'] = $searchResult['TotalNumber'];
        $data['CurrentPage'] = $pageNumber + 1;
        $totalPageSub = $searchResult['TotalNumber'] % LINE_NUMBER_SEARCH;
        $totalPage = ($searchResult['TotalNumber'] - $totalPageSub) / LINE_NUMBER_SEARCH;
        if ($totalPageSub > 0) {
            $totalPage += 1;
        }
        $data['TotalPage'] = $totalPage;
        return $data;
    }
}
