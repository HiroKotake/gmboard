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
        return $this->cIns->daoGameInfos->getAllGameInfos();
    }
    public function addAttachGame(int $userId, int $gameId, int $playerId, string $nickname) : array
    {
        // GamePlayerにレコード登録
        $newId = $this->cIns->daoGamePlayers->addNewGamePlayer(
            $gameId,
            array(
                'UserId' => $userId,
                'PlayerId' => $playerId,
                'GameNickname' => $nickname
            )
        );
        return $this->showAttachGame($gameId, $newId);
    }

    public function showAttachGame(int $gameId, int $gamePlayerId) : array
    {
        // 表示用データ取得
        $gamePlayer = $this->cIns->daoGamePlayers->getByGamePlayerId($gameId, $gamePlayerId);
        if (count($gamePlayer) == 0) {
            return false;
        }
        $gameInfo = $this->cIns->daoGameInfos->getByGameId($gamePlayer['GameId']);
        $gamePlayer['GameName'] = $gameInfo['Name'];
        return $gamePlayer;
    }
}
