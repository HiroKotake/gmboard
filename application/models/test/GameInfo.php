<?php

class GameInfo
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
    }

    // ゲーム情報追加
    public function addGameInfo(string $gameName, string $description) : array
    {
        // 追加
        $newGameInfoId = $this->cIns->daoGameInfos->addGameinfo($gameName, $description);
        // 結果取得
        $data = $this->cIns->daoGameInfos->getByGameId($newGameInfoId);
        return $data;
    }

    // ゲーム情報一覧表示
    public function listGameInfo() : array
    {
        return $this->cIns->daoGameInfos->getAll();
    }

    // ゲーム情報確認
    public function showGameInfo(int $gameId) : array
    {
        $resultSet = $this->cIns->daoGameInfos->getByGameId($gameId);
        return $resultSet[0];
    }
}
