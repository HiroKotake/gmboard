<?php

class GamePlayer
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        $this->cIns->load->model('dao/GamePlayers', 'daoGamePlayers');
    }

    public function formGamePlayer() : array
    {
        return $this->cIns->daoGameInfos->getAll();
    }
    public function addGamePlayer(int $gameId, string $playerId, string $gameNickname) : array
    {
        $data = array();
        // GamePlayerにレコード登録
        $newId = $this->cIns->daoGamePlayers->addNewGamePlayer(array(
            'GameId' => (int)$gameId,
            'PlayerId' => $playerId,
            'GameNickname' => $gameNickname
        ));
        // 表示用データ取得
        $data['PlayerInfo'] = $this->cIns->daoGamePlayers->getByGamePlayerId($newId);
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        return $data;
    }
    public function listGamePlayer() : array
    {
        $data = array();
        $data['GamePlayers'] = $this->cIns->daoGamePlayers->getAll();
        $data['GameInfos'] = $this->cIns->daoGameInfos->getAll();
        return $data;
    }
    public function showGamePlayer(int $gamePlayerId) : array
    {
        $playerInfo = $this->cIns->daoGamePlayers->getByGamePlayerId($gamePlayerId);
        if (count($playerInfo) == 0) {
            return false;
        }
        $gameInfo = $this->cIns->daoGameInfos->getByGameId($playerInfo['GameId']);
        $playerInfo['GameName'] = $gameInfo['Name'];
        return $playerInfo;
    }
}
