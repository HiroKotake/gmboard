<?php

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

    // ゲーム情報追加
    public function addGameInfo(string $gameName, string $description) : array
    {
        // 追加
        $newGameInfoId = $this->cIns->daoGameInfos->addGameinfo($gameName, $description);
        // プレイヤー管理テーブル追加
        $this->cIns->daoGamePlayers->createTable($newGameInfoId);
        // プレイヤー予約管理テーブル追加
        $this->cIns->daoRegistBooking->createTable($newGameInfoId);
        // ゲーム別グループ管理テーブル作成
        $this->cIns->daoGroups->createTable($newGameInfoId);
        // 結果取得
        $data = $this->cIns->daoGameInfos->getByGameId($newGameInfoId);
        return $data;
    }

    // ゲーム情報一覧表示
    public function listGameInfo() : array
    {
        return $this->cIns->daoGameInfos->getAllGameInfos();
    }

    // ゲーム情報確認
    public function showGameInfo(int $gameId) : array
    {
        return $this->cIns->daoGameInfos->getByGameId($gameId);
    }
}
