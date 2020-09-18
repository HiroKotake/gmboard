<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Group;
use teleios\gmboard\libs\common\ShowIdiom;
use teleios\gmboard\Beans\Bean;
use teleios\gmboard\dao\Groups as daoGroups;
use teleios\gmboard\dao\GamePlayers as daoGamePlayers;

/**
 * グループページ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class GroupPage extends Group
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * グループページで表示するデータの共通部分を取得する
     * @param  int    $userId     ユーザID
     * @param  string $obfGameId  難読化ゲームID
     * @param  string $obfGroupId 難読化グループID
     * @return array              表示用データ
     */
    private function getGroupPageDataCommon(int $userId, string $obfGameId, string $obfGroupId) : array
    {
        // ID関連
        $this->gameId = $this->trnasAliasToGameId($obfGameId);
        $this->groupId = $this->transAliasToId($obfGroupId, ID_TYPE_GROUP);
        if ($this->groupId == 0) {
            // セッションからGroupIdを取れなかった場合の保険
            $this->groupId = $this->getAliasIdtoGroupId($obfGroupId);
        }
        // 共通ページデータ取得
        $data = $this->getPageDataCommon($userId);
        // グループ名取得
        $groupName = $this->getGroupName();
        $data['GroupName'] = $groupName;
        // グループ権限取得
        $daoGamePlayers = new daoGamePlayers();
        $gamePlayer = $daoGamePlayers->getByUserId($this->gameId, $userId);
        $data['Authority'] = null;
        if (!$gamePlayer->isEmpty()) {
            $data['Authority'] = $gamePlayer->Authority;
        }
        $data['GameId'] = $obfGameId;
        $data['GroupId'] = $obfGroupId;
        $data['Result'] = "";
        return $data;
    }

    /**
     * グループページで表示するデータを取得する
     * @param  int    $userId     ユーザID
     * @param  string $obfGameId  難読化ゲームID
     * @param  string $obfGroupId 難読化グループID
     * @return array              表示用データ
     */
    public function getPageData(int $userId, string $obfGameId, string $obfGroupId) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // グループ向けメッセージ取得
        $data["Message"] = $this->getGroupMessage($data['GroupName']);
        return $data;
    }

    /**
     * グループ(メンバーリスト)ページで表示するデータを取得する
     * @param  int    $userId     ユーザID
     * @param  string $obfGameId  難読化ゲームID
     * @param  string $obfGroupId 難読化グループID
     * @return array              表示用データ
     */
    public function getPageMemberList(int $userId, string $obfGameId, string $obfGroupId) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // メンバーリスト取得
        $this->gameId = $this->trnasAliasToGameId($obfGameId);
        $this->groupId = $this->transAliasToId($obfGroupId, ID_TYPE_GROUP);
        $daoGamePlayers = new daoGamePlayers();
        $data['UserId'] = $userId;
        $data['MemberList'] = $daoGamePlayers->getByGroupId($this->gameId, $this->groupId);
        return $data;
    }

    /**
     * グループ(申請者リスト)ページで表示するデータを取得する
     * @param  int    $userId     ユーザID
     * @param  string $obfGameId  難読化ゲームID
     * @param  string $obfGroupId 難読化グループID
     * @return array              表示用データ
     */
    public function getPageRequestList(int $userId, string $obfGameId, string $obfGroupId) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // 申請者リスト取得
        return $data;
    }

    /**
     * グループ(招待者リスト)ページで表示するデータを取得する
     * @param  int    $userId     ユーザID
     * @param  string $obfGameId  難読化ゲームID
     * @param  string $obfGroupId 難読化グループID
     * @return array              表示用データ
     */
    public function getPageInviteList(int $userId, string $obfGameId, string $obfGroupId) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // 招待者リスト
        return $data;
    }
}
