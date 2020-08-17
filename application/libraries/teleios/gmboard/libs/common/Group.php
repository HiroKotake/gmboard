<?php
namespace teleios\gmboard\libs\common;

use teleios\gmboard\dao\Bean;
use teleios\gmboard\dao\GameInfos;
use teleios\gmboard\dao\Groups;
use teleios\gmboard\dao\GroupBoard;
use teleios\gmboard\dao\RegistBooking;
use teleios\gmboard\dao\GamePlayers;

/**
 * グループ関連基本クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class Group extends GmbCommon
{
    public $gameId;
    public $groupId;
    private $daoGroups;
    private $daoGroupBoard;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->daoGroups = new Groups();
        $this->daoGroupBoard = new GroupBoard();
    }

    /**
     * デストラクタ
     */
    public function __destruct()
    {
    }

    /**
     * グループネームで検索を実行
     *
     * @param  int     $gameId    [description]
     * @param  string  $groupName [description]
     * @param  int     $userId    [description]
     * @param  integer $page      [description]
     * @param  integer $number    [description]
     * @return array              [description]
     */
    public function searchByGroupName(int $gameId, string $groupName, int $userId, int $page = 0, int $number = 20) : array
    {
        $data = array();
        // ユーザのグループ登録状況を確認するためにGamePlayerテーブルから対象ユーザを取得
        $daoGamePlayers = new GamePlayers();
        $userInfo = $daoGamePlayers->getByUserId($gameId, $userId);
        // 検索
        // グループリストを取得し、登録状況カラムを追加。また、不要なデータを削除
        $listGroups = $this->daoGroups->getByGroupName($gameId, $groupName, $page, $number);
        $leaderIds = array();
        if (count($listGroups) > 0) {
            foreach ($listGroups as $group) {
                $leaderIds[] = $group->Leader;
                $data[$group->GroupId] = array(
                    "GroupId" => $group->GroupId,
                    "AliasId" => $group->AliasId,
                    "GroupName" => $group->GroupName,
                    "Leader" => "",
                    "Joined" => ($group->GroupId == $userInfo->GroupId ? 1 : 0)
                );
            }
            // リーダーのニックネームを取得し、データに追記
            $leaders = $daoGamePlayers->getByLeaders($gameId, $leaderIds);
            foreach ($leaders as $ld) {
                $data[$ld->GroupId]["Leader"] = $ld->GameNickname;
            }
        }
        $result = array(
            'TotalNumber' => $this->daoGroups->countByGroupName($gameId, $groupName),
            'GroupList' => $data
        );
        return $result;
    }

    public function getAliasIdtoGroupId(string $obfAliasId) : int
    {
        $groupInfo = $this->daoGroups->getByAliasId($obfAliasId, $this->gameId);
        return $groupInfo->isEmpty() ? 0 : $groupInfo->GroupId;
    }

    public function getGroupName() : string
    {
        $group = $this->daoGroups->getByGroupId($this->gameId, $this->groupId);
        if ($group->isEmpty()) {
            return "";
        }
        return $group->GroupName;
    }

    /**
     * グループ向けメッセージ取得
     * @param  integer $page   [description]
     * @param  integer $number [description]
     * @param  string  $order  [description]
     * @return array           [description]
     */
    public function getGroupMessage(string $groupName, int $page = 0, int $number = 20, string $order = "DESC") : array
    {
        $offset = $page * $number;
        $messages = $this->daoGroupBoard->get($this->gameId, $this->groupId, $number, $offset, $order);
        $libShowIdiom = new ShowIdiom();
        $count = count($messages);
        for ($i = 0; $i < $count; $i++) {
            switch ($messages[$i]->Idiom) {
                case 1:
                case 2:
                    $reps = array($messages[$i]->Message, $groupName);
                    $messages[$i]->Message = str_replace(["%1%", "%2%"], $reps, $libShowIdiom->getIdiom($messages[$i]->Idiom));
                    break;
                default:
                    break;
            }
        }
        return $messages;
    }

    /**
     * メッセージの総数を取得する
     * @return int メッセージ総数
     */
    public function count() : int
    {
        return $this->daoGroupBoard->count($this->gameId, $this->groupId);
    }
}
