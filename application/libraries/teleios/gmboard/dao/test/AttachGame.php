<?php
namespace teleios\gmboard\dao\test;

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
        return $this->cIns->daoGameInfos->getAll();
    }

    /**
     * GamePlayerにレコード登録
     * @param  int    $userId   [description]
     * @param  int    $gameId   [description]
     * @param  int    $playerId [description]
     * @param  string $nickname [description]
     * @return array            [description]
     */
    public function addAttachGame(int $userId, int $gameId, int $playerId, string $nickname) : array
    {
        // GamePlayerにレコード登録
        $newId = $this->cIns->daoGamePlayers->add(
            $gameId,
            array(
                'UserId' => $userId,
                'PlayerId' => $playerId,
                'GameNickname' => $nickname
            )
        );
        return $this->showAttachGame($gameId, $newId);
    }

    /**
     * 表示用データ取得
     * @param  int   $gameId  [description]
     * @param  int   $groupId [description]
     * @return array          [description]
     */
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