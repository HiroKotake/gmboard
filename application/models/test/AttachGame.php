<?php

class AttachGame
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        $this->cIns->load->model('dao/GamePlayers', 'daoGamePlayers');
    }

    public function formAttachGame() : array
    {
        return $this->cIns->daoGameInfos->getAll();
    }
    public function addAttachGame(int $userId, int $gameId, int $playerId, string $nickname) : array
    {
        // GamePlayerにレコード登録
        $newId = $this->cIns->daoGamePlayers->addNewGamePlayer(array(
            'UserId' => $userId,
            'GameId' => $gameId,
            'PlayerId' => $playerId,
            'GameNickname' => $nickname
        ));
        return $this->showAttachGame($newId);
    }

    public function showAttachGame(int $gamePlayerId) : array
    {
        // 表示用データ取得
        $gamePlayer = $this->cIns->daoGamePlayers->getByGamePlayerId($gamePlayerId);
        if (count($gamePlayer) == 0) {
            return false;
        }
        $gameInfo = $this->cIns->daoGameInfos->getByGameId($gamePlayer['GameId']);
        $gamePlayer['GameName'] = $gameInfo['Name'];
        return $gamePlayer;
    }
}
