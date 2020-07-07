<?php
namespace teleios\gmboard\dao\test;

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
        $data['GameInfos'] = $this->cIns->daoGameInfos->getAll();
        return $data;
    }

    /**
     * formGamePlayer
     * @param  int   $gameId [description]
     * @return array         [description]
     */
    public function formGamePlayer(int $gameId) : array
    {
        $data = array();
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        $data['Groups'] = $this->cIns->daoGroups->getAll($gameId);
        return $data;
    }

    /**
     * 予約プレイヤー登録
     * @param  int    $gameId       [description]
     * @param  int    $groupId      [description]
     * @param  string $playerId     [description]
     * @param  string $gameNickname [description]
     * @param  string $authCode     [description]
     * @return array                [description]
     */
    public function addGamePlayer(
        int $gameId,
        int $groupId,
        string $playerId,
        string $gameNickname,
        string $authCode
    ) : array {
        $data = array();
        $newId = $this->cIns->daoRegistBooking->add($gameId, $groupId, $playerId, $gameNickname, $authCode);
        $data['PlayerInfo'] = $this->cIns->daoRegistBooking->getByRegistBookingId($gameId, $newId);
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        return $data;
    }

    public function listGames() : array
    {
        $data = array();
        $data['GameInfos'] = $this->cIns->daoGameInfos->getAll();
        return $data;
    }

    /**
     * 予約プレイヤーリスト取得
     * @param  int   $gameId [description]
     * @return array         [description]
     */
    public function listGamePlayers(int $gameId) : array
    {
        $data = array();
        $data['GamePlayers'] = $this->cIns->daoRegistBooking->getByGameId($gameId);
        $data['GameInfo'] = $this->cIns->daoGameInfos->getByGameId($gameId);
        return $data;
    }

    /**
     * 予約プレイヤー情報取得
     * @param  int   $gameId          [description]
     * @param  int   $registBookingId [description]
     * @return array                  [description]
     */
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