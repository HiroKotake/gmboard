<?php

class GamePlayer
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        $this->cIns->load->model('dao/Groups', 'daoGroups');
        $this->cIns->load->model('dao/RegistBooking', 'daoRegistBooking');
    }

    public function formGameList() : array
    {
        $data = array();
        $data['GameInfos'] = $this->cIns->daoGameInfos->getAllGameInfos();
        return $data;
    }

    public function formGamePlayer(int $gameId) : array
    {
        $data = array();
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        $data['Groups'] = $this->cIns->daoGroups->getAllGroups($gameId);
        return $data;
    }


    public function addGamePlayer(
        int $gameId,
        int $groupId,
        string $playerId,
        string $gameNickname,
        string $authCode
    ) : array {
        $data = array();
        $newId = $this->cIns->daoRegistBooking->addNewBooking($gameId, $groupId, $playerId, $gameNickname, $authCode);
        $data['PlayerInfo'] = $this->cIns->daoRegistBooking->getByRegistBookingId($gameId, $newId);
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        return $data;
    }

    public function listGames() : array
    {
        $data = array();
        $data['GameInfos'] = $this->cIns->daoGameInfos->getAllGameInfos();
        return $data;
    }
    public function listGamePlayers(int $gameId) : array
    {
        $data = array();
        $data['GamePlayers'] = $this->cIns->daoRegistBooking->getByGameId($gameId);
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        return $data;
    }
    public function showGamePlayer(int $gameId, int $registBookingId) : array
    {
        $playerInfo = $this->cIns->daoRegistBooking->getByRegistBookingId($gameId, $registBookingId);
        if (count($playerInfo) != 0) {
            $groupInfo = $this->cIns->daoGroups->getByGroupId($gameId, $playerInfo['GroupId']);
            $gameInfo = $this->cIns->daoGameInfos->getByGameId($gameId);
            $playerInfo['GameName'] = $gameInfo['Name'];
            $playerInfo['GroupName'] = $groupInfo['GroupName'];
            return $playerInfo;
        }
        return array();
    }
}
