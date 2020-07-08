<?php
namespace teleios\gmboard\libs\test;

use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\GamePlayers;
use teleios\gmboard\dao\RegistBooking;
use teleios\gmboard\dao\Groups;

/**
 * テスト環境向ゲーム情報関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class GameInfo
{
    private $daoGameInfos = null;
    private $daoGamePlayers = null;
    private $daoRegistBooking = null;
    private $daoGroups = null;

    public function __construct()
    {
        $this->daoGameInfos = new GameInfos();
        $this->daoGamePlayers = new GamePlayers();
        $this->daoRegistBooking = new RegistBooking();
        $this->daoGroups = new Groups();
    }

    /**
     * ゲーム情報追加
     * @param  string $gameName    [description]
     * @param  string $description [description]
     * @return array               [description]
     */
    public function addGameInfo(string $gameName, string $description) : array
    {
        // 追加
        $newGameInfoId = $this->daoGameInfos->add($gameName, $description);
        // プレイヤー管理テーブル追加
        $this->daoGamePlayers->createTable($newGameInfoId);
        // プレイヤー予約管理テーブル追加
        $this->daoRegistBooking->createTable($newGameInfoId);
        // ゲーム別グループ管理テーブル作成
        $this->daoGroups->createTable($newGameInfoId);
        // 結果取得
        $data = $this->daoGameInfos->getByGameId($newGameInfoId);
        // GameListのバージョンを更新
        $currentVer = $this->sysComns->get(SYSTEM_KEY_GAMELIST_VER);
        if (empty($currentVer)) {
            $currentVer = 0;
        }
        $this->sysComns->set(SYSTEM_KEY_GAMELIST_VER, $currentVer + 1);
        return $data;
    }

    /**
     * ゲーム情報一覧表示
     * @return array [description]
     */
    public function listGameInfo() : array
    {
        return $this->daoGameInfos->getAll();
    }

    //
    /**
     * ゲーム情報確認
     * @param  int   $gameId [description]
     * @return array         [description]
     */
    public function showGameInfo(int $gameId) : array
    {
        return $this->daoGameInfos->getByGameId($gameId);
    }
}
