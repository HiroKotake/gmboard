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
        $this->cIns->load->model('dao/Groups', 'daoGroups');
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

    public function showAttachGame(int $gameId, int $groupId) : array
    {
        // 表示用データ取得
        $data = array();
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        $data['GroupInfo'] = $this->cIns->daoGroups->getByGroupId($gameId, $groupId);
        $data['GamePlayers'] = $this->cIns->daoGamePlayers->getByGroupId($gameId, $groupId);
        if (count($data['GamePlayers']) == 0) {
            $data['GamePlayers'] = array();
        }
        return $data;
    }
}
