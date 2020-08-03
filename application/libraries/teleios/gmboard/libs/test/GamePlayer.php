<?php
namespace teleios\gmboard\libs\test;

use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\RegistBooking;

/**
 * テスト環境向ゲームプレイヤー関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class GamePlayer
{
    private $daoGameInfos = null;
    private $daoGroups = null;
    private $daoRegistBooking = null;

    public function __construct()
    {
        $this->daoGameInfos = new GameInfos();
        $this->daoGroups = new Groups();
        $this->daoRegistBooking = new RegistBooking();
    }

    /**
     * ゲーム一覧を取得
     * @return array [description]
     */
    public function formGameList() : array
    {
        $data = array();
        $data['GameInfos'] = $this->daoGameInfos->getAll();
        return $data;
    }

    /**
     * 指定したゲームのプレイヤー一覧を取得
     * @param  int   $gameId [description]
     * @return array         [description]
     */
    public function formGamePlayer(int $gameId) : array
    {
        $data = array();
        $data['GameInfo'] = $this->daoGameInfos->getByGameId($gameId);
        $data['Groups'] = $this->daoGroups->getAll($gameId);
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
        $newId = $this->daoRegistBooking->add($gameId, $groupId, $playerId, $gameNickname, $authCode);
        $data['PlayerInfo'] = $this->daoRegistBooking->getByRegistBookingId($gameId, $newId);
        $data['GameInfo'] = $this->daoGameInfos->getByGameId($gameId);
        return $data;
    }

    /**
     * [listGames description]
     * @return array [description]
     */
    public function listGames() : array
    {
        $data = array();
        $data['GameInfos'] = $this->daoGameInfos->getAll();
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
        $data['GamePlayers'] = $this->daoRegistBooking->getByGameId($gameId);
        $data['GameInfo'] = $this->daoGameInfos->getByGameId($gameId);
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
        $playerInfo = $this->daoRegistBooking->getByRegistBookingId($gameId, $registBookingId);
        if (count($playerInfo) != 0) {
            $groupInfo = $this->daoGroups->getByGroupId($gameId, $playerInfo['GroupId']);
            $gameInfo = $this->daoGameInfos->getByGameId($gameId);
            $playerInfo['GameName'] = $gameInfo->Name;
            $playerInfo['GroupName'] = $groupInfo->GroupName;
            return $playerInfo;
        }
        return array();
    }
}
