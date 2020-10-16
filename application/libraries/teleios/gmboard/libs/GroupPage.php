<?php
namespace teleios\gmboard\libs;

use teleios\gmboard\libs\common\Group;
use teleios\gmboard\libs\common\ShowIdiom;
use teleios\gmboard\Beans\Bean;
use teleios\gmboard\dao\Groups as daoGroups;
use teleios\gmboard\dao\GamePlayers as daoGamePlayers;
use teleios\gmboard\dao\RegistBooking as daoRegistBooking;

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
        $this->setBaseInfos($obfGameId, $obfGroupId, $userId);
        if ($this->groupId == 0) {
            // セッションからGroupIdを取れなかった場合の保険
            $this->groupId = $this->getAliasIdtoGroupId($obfGroupId);
        }
        // 共通ページデータ取得
        $data = $this->getPageDataCommon($userId);
        // ゲーム名、グループ名取得
        $data['GameName']  = $this->getGameName($obfGameId);
        $data['GroupName'] = $this->groupName;
        // グループ権限取得
        $daoGamePlayers = new daoGamePlayers();
        $gamePlayer = $daoGamePlayers->getByUserId($this->gameId, $userId);
        $data['Authority'] = null;
        if (!$gamePlayer->isEmpty()) {
            $data['Authority'] = $gamePlayer->Authority;
        }
        $data['GameId'] = $obfGameId;
        $data['GroupId'] = $obfGroupId;
        $data['PageName'] = "Group";  // GmbCommonのものを上書き
        $data['Result'] = "";
        return $data;
    }

    /**
     * グループページで表示するデータを取得する
     * @param  int     $userId     ユーザID
     * @param  string  $obfGameId  難読化ゲームID
     * @param  string  $obfGroupId 難読化グループID
     * @param  integer $page       ページ番号
     * @return array               [description]
     */
    public function getNoticesData(int $userId, string $obfGameId, string $obfGroupId = "0", int $page = 0) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // グループ向け告知取得
        $data['Notices'] = $this->getGroupNotices($page);
        return $data;
    }

    public function getMessagePage(int $userId, string $obfGameId, string $obfGroupId = "0") : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // グループ向けメッセージ取得
        $data["Message"] = $this->getGroupMessage($data['GroupName']);
        return $data;
    }

    /**
     * グループページで表示するデータを取得する
     * @param  int    $userId     ユーザID
     * @param  string $obfGameId  難読化ゲームID
     * @param  string $obfGroupId 難読化グループID
     * @return array              表示用データ
     */
    public function getGroupSearchPage(int $userId, string $obfGameId, string $obfGroupId, string $groupName, int $pageNumber) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // グループ検索結果
        $searchResult = $this->searchByGroupName($obfGameId, $groupName, $userId, $pageNumber, LINE_NUMBER_SEARCH);
        $data['Type'] = 'GroupSearch';
        $data['Title'] = 'グループ検索結果';
        $data['List'] = $searchResult['GroupList'];
        $data['MaxLineNumber'] = LINE_NUMBER_SEARCH;
        $data['TotalNumber'] = $searchResult['TotalNumber'];
        $data['CurrentPage'] = $pageNumber + 1;
        $totalPageSub = $searchResult['TotalNumber'] % LINE_NUMBER_SEARCH;
        $totalPage = ($searchResult['TotalNumber'] - $totalPageSub) / LINE_NUMBER_SEARCH;
        if ($totalPageSub > 0) {
            $totalPage += 1;
        }
        $data['TotalPage'] = $totalPage;
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
        $daoGamePlayers = new daoGamePlayers();
        $data['UserId'] = $userId;
        $data['MemberList'] = $daoGamePlayers->getByGroupId($this->gameId, $this->groupId);
        return $data;
    }

    /**
     * グループ(申請者リスト)ページで表示するデータを取得する
     * @param  int     $userId     ユーザID
     * @param  string  $obfGameId  難読化ゲームID
     * @param  string  $obfGroupId 難読化グループID
     * @param  integer $page       ページ番号（オフセットのベーズ数値として使用)
     * @param  integer $line       習得レコード数
     * @return array               表示用データ
     */
    public function getPageRequestList(int $userId, string $obfGameId, string $obfGroupId, $page = 0, $line = 20) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // 申請者リスト取得
        $daoRegistBooking = new daoRegistBooking();
        $data['Requests'] = $daoRegistBooking->getRequestsByGroupId($this->gameId, $this->groupId, $line, $line * $page);
        return $data;
    }

    /**
     * グループ(招待者リスト)ページで表示するデータを取得する
     * @param  int     $userId     ユーザID
     * @param  string  $obfGameId  難読化ゲームID
     * @param  string  $obfGroupId 難読化グループID
     * @param  integer $page       ページ番号（オフセットのベーズ数値として使用)
     * @param  integer $line       習得レコード数
     * @return array               表示用データ
     */
    public function getPageInviteList(int $userId, string $obfGameId, string $obfGroupId, $page = 0, $line = 20) : array
    {
        $data = $this->getGroupPageDataCommon($userId, $obfGameId, $obfGroupId);
        // 招待者リスト
        $daoRegistBooking = new daoRegistBooking();
        $data['Invites'] = $daoRegistBooking->getInvitesByGroupId($this->gameId, $this->groupId, $line, $line * $page);
        return $data;
    }
}
