<?php
namespace teleios\gmboard\dao\test;

class GameInfo
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        $this->cIns->load->model('dao/GamePlayers', 'daoGamePlayers');
        $this->cIns->load->model('dao/RegistBooking', 'daoRegistBooking');
        $this->cIns->load->model('dao/Groups', 'daoGroups');
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
        $newGameInfoId = $this->cIns->daoGameInfos->add($gameName, $description);
        // プレイヤー管理テーブル追加
        $this->cIns->daoGamePlayers->createTable($newGameInfoId);
        // プレイヤー予約管理テーブル追加
        $this->cIns->daoRegistBooking->createTable($newGameInfoId);
        // ゲーム別グループ管理テーブル作成
        $this->cIns->daoGroups->createTable($newGameInfoId);
        // 結果取得
        $data = $this->cIns->daoGameInfos->getByGameId($newGameInfoId);
        // GameListのバージョンを更新
        $currentVer = $this->cIns->sysComns->get(SYSTEM_KEY_GAMELIST_VER);
        if (empty($currentVer)) {
            $currentVer = 0;
        }
        $this->cIns->sysComns->set(SYSTEM_KEY_GAMELIST_VER, $currentVer + 1);
        return $data;
    }

    /**
     * ゲーム情報一覧表示
     * @return array [description]
     */
    public function listGameInfo() : array
    {
        return $this->cIns->daoGameInfos->getAll();
    }

    //
    /**
     * ゲーム情報確認
     * @param  int   $gameId [description]
     * @return array         [description]
     */
    public function showGameInfo(int $gameId) : array
    {
        return $this->cIns->daoGameInfos->getByGameId($gameId);
    }
}
