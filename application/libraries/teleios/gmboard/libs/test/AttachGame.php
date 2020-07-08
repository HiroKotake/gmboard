<?php
namespace teleios\gmboard\libs\test;

use teleios\gmboard\dao\Users;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\GamePlayers;
use teleios\gmboard\dao\Groups;

/**
 * テスト環境向ゲーム関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class AttachGame
{
    private $daoUsers = null;
    private $daoGameInfos = null;
    private $daoGamePlayers = null;
    private $daoGroups = null;

    public function __construct()
    {
        $this->daoUsers = new Users();
        $this->daoGameInfos = new GameInfos();
        $this->daoGamePlayers = new GamePlayers();
        $this->daoGroups = new Groups();
    }

    public function formAttachGame() : array
    {
        return $this->daoGameInfos->getAll();
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
        $newId = $this->daoGamePlayers->add(
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
        $data['GameInfo'] = $this->daoGameInfos->getByGameId($gameId);
        $data['GroupInfo'] = $this->daoGroups->getByGroupId($gameId, $groupId);
        $data['GamePlayers'] = $this->daoGamePlayers->getByGroupId($gameId, $groupId);
        if (count($data['GamePlayers']) == 0) {
            $data['GamePlayers'] = array();
        }
        return $data;
    }
}
