<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Game;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\GroupMessage;

/**
 * ゲームページ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class GamePage extends Game
{

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ゲームページで表示するデータの共通部分を取得する
     * @param  int    $userId    ユーザID
     * @param  string $obfGameId 難読化ゲームID
     * @return array             [description]
     */
    private function getGamePageDataCommon(int $userId, string $obfGameId) : array
    {
        $this->gameId = $this->trnasAliasToGameId($obfGameId);
        // 基本ページ情報取得
        $data = $this->getPageDataCommon($userId);
        $data['GameName'] = $this->getGameName($obfGameId);
        // ゲーム情報取得
        $daoGameInfos = new GameInfos();
        $data['GameInfo'] = $daoGameInfos->getByGameId($this->gameId);
        $data['GameId'] = $obfGameId;
        $data['PageName'] = "Game";   // GmbCommonのものを上書き
        return $data;
    }

    /**
     * ゲームトップページで表示するデータを取得する
     * @param  int    $userId    ユーザID
     * @param  string $obfGameId 難読化ゲームID
     * @return array             [description]
     */
    public function getPageData(int $userId, string $obfGameId) : array
    {
        $data = $this->getGamePageDataCommon($userId, $obfGameId);
        // ゲーム内告知取得
        $data['GameNotices'] = $this->getGameNotices($this->gameId);
        return $data;
    }

    /**
     * ゲーム内グループページで表示するデータを取得する
     * @param  int     $userId    ユーザID
     * @param  string  $obfGameId 難読化ゲームID
     * @param  integer $page      ページ番号
     * @param  integer $number    表示グループ数
     * @return array              [description]
     */
    public function getGroupData(int $userId, string $obfGameId, int $page = 0, int $number = 20) : array
    {
        $data = $this->getGamePageDataCommon($userId, $obfGameId);
        // グループリスト取得
        $groupData = $this->getGroupList($page, $number);
        $data['GroupList'] = $groupData['GroupList'];
        $data['GroupNumber'] = $groupData['GroupNumber'];
        return $data;
    }

    /**
     * ゲーム内グループ検索結果ページで表示するデータを取得する
     * @param  int     $userId    ユーザID
     * @param  string  $obfGameId 難読化ゲームID
     * @param  string  $name      グループ名
     * @param  integer $page      ページ番号
     * @param  integer $number    表示グループ数
     * @return array              [description]
     */
    public function searchGroupByName(
        int $userId,
        string $obfGameId,
        string $name,
        int $page = 0,
        int $number = 20) : array
    {
        $data = $this->getGamePageDataCommon($userId, $obfGameId);
        // グループ検索
        return $data;
    }
}
