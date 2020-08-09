<?php
namespace teleios\gmboard\libs\common;

//use teleios\gmboard\dao\Bean;
use teleios\gmboard\dao\PlayerIndex;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\GamePlayers;

/**
 * ユーザ関連基本クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class Personal extends GmbCommon
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ゲームを追加する
     * @param  int    $userId       [description]
     * @param  int    $gameId       [description]
     * @param  string $playerId     [description]
     * @param  string $gameNickname [description]
     * @return array                [description]
     */
    public function attachGame(int $userId, int $gameId, string $playerId, string $gameNickname) : array
    {
        $data = array(
            'Status' => DB_STATUS_EXISTED,
            'PlayerIndexId' => null,
            'GamePlayersId' => null
        );
        $daoPlayerIndex = new PlayerIndex();
        $daoGamePlayers = new GamePlayers();
        $daoGameInfos   = new GameInfos();
        // 登録済み確認
        $fExist = $daoPlayerIndex->isExist($userId, $playerId);
        if ($fExist) {
            return $data;
        }
        // PlayerIndexテーブルへ情報を追加
        $data["PlayerIndexId "] = $daoPlayerIndex->add($userId, $gameId);
        // GamePlayers_xxxxxxxxテーブルへ情報を追加
        $gamePlayersData = array(
            'UserId'        => $userId,
            'PlayerId'      => $playerId,
            'GameNickname'  => $gameNickname
        );
        $data["GamePlsyersId"] = $daoGamePlayers->add($gameId, $gamePlayersData);
        // ゲーム情報
        $gameInfo = $daoGameInfos->getByGameId($gameId)->toArray();
        unset($gameInfo["GameId"]);
        $data["GameInfo"] = $gameInfo;
        // セッションのゲームリストに関する部分をクリア
        $this->ciSession->delSessionData(SESSION_INFO_GAME);
        $this->ciSession->delSessionData(SESSION_LIST_GENRE);
        $this->ciSession->delSessionData(SESSION_LIST_GAME);
        $data["Status"] = DB_STATUS_ADDED;
        return $data;
    }

}
