<?php
namespace teleios\gmboard\libs\common;

use teleios\gmboard\Beans\Bean;
use teleios\gmboard\Beans\UserMessageBean;
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
    // const
    const MESSAGE_AUTH_BASE = 3;
    const MESSAGE_CHANGE_LEADER = 4;
    const MESSAGE_WITHDRAW = 2;
    const MESSAGE_DISMISS = 3;
    // variable
    public  $adminUserId;
    public  $gameId;
    public  $groupId;
    public  $groupName;
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
     * グループの基本IDを設定する
     * @param string $obfGameId   [description]
     * @param string $obfGroupId  [description]
     * @param int    $adminUserId [description]
     */
    public function setBaseInfos(string $obfGameId, string $obfGroupId, int $adminUserId) : void
    {
        $this->gameId = $this->trnasAliasToGameId($obfGameId);
        $this->groupId = $this->transAliasToId($obfGroupId, ID_TYPE_GROUP);
        $this->adminUserId = $adminUserId;
        $this->groupName = $this->getGroupName();
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

    /**
     * 暗号化済みエイリアスIDからグループIDを取得
     * @param  string $obfAliasId 暗号化済みエイリアスID
     * @return int                対象が存在する場合はグループIDを返し、存在しない場合は 0 を返す
     */
    public function getAliasIdtoGroupId(string $obfAliasId) : int
    {
        $groupInfo = $this->daoGroups->getByAliasId($obfAliasId, $this->gameId);
        return $groupInfo->isEmpty() ? 0 : $groupInfo->GroupId;
    }

    /**
     * 設定済みグループIDからグループ名を取得
     * @return string 存在する場合はグループ名を、存在しない場合は空を返す
     */
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

    /**
     * グループメンバーの権限を変更する
     * @param  string $targetUserId  対象ユーザID
     * @param  int    $newAuth       新しい権限
     * @param  int    $changerUserId 変更者ユーザID
     * @return int                   変更結果
     */
    public function changeAuth(string $targetUserId, int $newAuth, int $changerUserId) : int
    {
        $libGamePlayer = new GamePlayers();
        // 変更対象メンバーの情報取得
        $targetMemberId = $this->transAliasToId($targetUserId, ID_TYPE_GAME_PLAYER);
        if ($targetMemberId > 0) {
            $targetMember = $libGamePlayer->getByGamePlayerId($this->gameId, $targetMemberId);
        } else {
            // セッションにデータが含まれていない場合、DB検索を実施
            $targetMember = $libGamePlayer->getByAliasId($targetUserId, $this->gameId);
        }
        // 変更者確認
        $adminMember = $libGamePlayer->getByUserIdInGroup($this->gameId, $this->groupId, $changerUserId);
        // フロント側でフィルダーしているが、保険としてチェック １
        if ($adminMember->Authority > GROUP_AUTHORITY_SUB_LEADER) {
            return AUTHORITY_CHANGE_NOT_HAVE; // 権限不足により実行不可
        }
        // フロント側でフィルダーしているが、保険としてチェック ２
        if ($newAuth == GROUP_AUTHORITY_LEADER AND $adminMember->Authority == GROUP_AUTHORITY_SUB_LEADER) {
            return AUTHORITY_CHANGE_NOT_HAVE; // 権限不足により実行不可
        }
        $regulerMemberList = $libGamePlayer->getRegularMemberByGroupId($this->gameId, $this->groupId);
        // メンバーが一人なのにリーダーを降りようとしているのか確認
        if (count($regulerMemberList) <= 1 AND $newAuth > 1) {
            return AUTHORITY_CHANGE_ONE_MEMBER; // メンバーが一人なのにリーダーを降りようとしているのでエラー
        }
        // 変更する権限がサブリーダーの場合は、サブリーダー上限に達しているか確認し、上限に達している場合はサブリーダー上限エラーを返す
        $subLeaderNumber = $this->countSubLeader($regulerMemberList);
        if ($subLeaderNumber == GROUP_MAX_SUB_LEADER) {
            return AUTHORITY_CHANGE_MAX_SUBLDR;    // サブリーダー上限に達しているので警告
        }
        // ToDo: リーダーが別のメンバーをリーダーにしようとした場合の処置を検討すること
        // 変更実施（一般的変更)
        $data = ['Authority' => $newAuth];
        $result = $libGamePlayer->set($this->gameId, $targetMember->GamePlayerId, $data);
        // 顕現変更後、対象ユーザへメッセージ送信
        if ($result) {
            if ($targetMember->UserId == $adminMember->UserId AND $newAuth > 1) {
                // リーダーが自身の権限を返納する場合の、次リーダーの選出および対象メンバーへのメッセージ送信 (別メソッドにて実装)
                $this->changeLeader($regulerMemberList);
            } else {
                // 権限変更対象メンバーへ、メッセージ送信
                $this->sendChangeGroupAuthority($targetMember, $newAuth);
            }
            return AUTHORITY_CHANGE_DONE;
        }
        return AUTHORITY_CHANGE_FAILURE;
    }

    /**
     * 退会処理
     * @return bool 処理に成功した場合はtrueを、失敗した場合はfalseを返す
     */
    public function doWithdraw() : bool
    {
        $libGamePlayer = new GamePlayers();
        // リーダーか確認し、リーダーの場合は権限移譲処理を先に実施
        $targetMember = $libGamePlayer->getByUserIdInGroup($this->gameId, $this->groupId, $this->adminUserId);
        if ($targetMember->Authority == GROUP_AUTHORITY_LEADER) {
            $regulerMemberList = $libGamePlayer->getRegularMemberByGroupId($this->gameId, $this->groupId);
            if (count($regulerMemberList) == 1) {
                return false;
            }
            $this->changeLeader($regulerMemberList);
        }
        // 実退会処理
        $result = $this->deleteMember($libGamePlayer, $targetMember->GamePlayerId);
        if ($result) {
            // 退会メッセージを送信
            // - グループメッセージ
            $gmBean = new GroupMessageBean();
            $this->sendGroupMessage($this->gameId, $this->groupId, $gmBean);
            // - ユーザメッセージ
            $umBean = new UserMessageBean();
            $umBean->set("GamePlayerId", SYSTEM_NOTICE_ID);
            $umBean->set("GameNickname", SYSTEM_NOTICE_NAME);
            $umBean->set("Idiom", );
            $umBean->set("Message", $this->GroupName);
            $this->sendUserMessage($targetMember->UserId, $umBean);
        }
        return $result;
    }

    /**
     * 除名処理
     * @param  string $obfTargetUserId ターゲットメンバーのエイリアスID
     * @return bool                    処理に成功した場合はtrueを、失敗した場合はfalseを返す
     */
    public function doDismiss(string $obfTargetUserId) : bool
    {
        $libGamePlayer = new GamePlayers();
        // 権限確認
        $adminMember = $libGamePlayer->getByUserIdInGroup($this->gameId, $this->groupId, $this->adminUserId);
        if ($adminMember->Authority >= GROUP_AUTHORITY_SUB_LEADER) {
            return false;
        }
        // 同一ユーザ確認
        $gamePlayer = $libGamePlayer->getByAliasId($obfTargetUserId, $this->gameId);
        // 実退会処理
        if ($this->userId != $gamePlayer->UserId) {
            $result = $this->deleteMember($libGamePlayer, $gamePlayer->GamePlayerId);
            if ($result) {
                // 除名メッセージを送信
                // - グループメッセージ
                $gmBean = new GroupMessageBean();
                $this->sendGroupMessage($this->gameId, $this->groupId, $gmBean);
                // - ユーザメッセージ
                $umBean = new UserMessageBean();
                $umBean->set("GamePlayerId", SYSTEM_NOTICE_ID);
                $umBean->set("GameNickname", SYSTEM_NOTICE_NAME);
                $umBean->set("Idiom", );
                $umBean->set("Message", $this->GroupName);
                $this->sendUserMessage($gamePlayer->UserId, $umBean);
            }
            return $result;
        }
        return false;
    }

    /**
     * グループ退会処理
     * @param  GamePlayers $libGamePlayer GamePlayersのインスタンス
     * @param  int         $gamePlayerId  退会処理するメンバーのGamePlayerId
     * @return bool                       処理に成功した場合はtrueを、失敗した場合はfalseを返す
     */
    private function deleteMember(GamePlayers &$libGamePlayer, int $gamePlayerId) : bool
    {
        $data = [
            'Authority' => GROUP_AUTHORITY_WITHDRAWED
        ];
        $libGamePlayer->set($this->gameId, $gamePlayerId, $data);
        return false;
    }

    /**
     * 対象メンバーに権限変更があったことを通知
     * @param Bean $targetMember 対象メンバーのGamePlayer情報
     * @param int  $newAuth      新権限
     */
    private function sendChangeGroupAuthority(Bean $targetMember, int $newAuth) : void
    {
        // 任命メッセージ送信
        $umBean = new UserMessageBean();
        $umBean->set("GamePlayerId", SYSTEM_NOTICE_ID);
        $umBean->set("GameNickname", SYSTEM_NOTICE_NAME);
        $umBean->set("Idiom", $newAuth + MESSAGE_AUTH_BASE);
        $umBean->set("Message", $this->GroupName);
        $this->sendUserMessage($targetMember->UserId, $umBean);
    }

    /**
     * サブリーダーの人数をカウントする
     * @param  array $regulerMemberList グループメンバーの情報で構成されたBean配列
     * @return int                      サブリーダーの人数
     */
    private function countSubLeader(array $regulerMemberList) : int
    {
        $count = 0;
        foreach ($regulerMemberList as $member) {
            if ($member->Authority == GROUP_AUTHORITY_SUB_LEADER) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * 自動リーダー変更
     * リーダーが退会もしくはリーダーが降りた場合に、自動的にリーダー変更を行う
     *
     * @param  array $memberList  メンバー情報を含むBean配列(デフォルト：ｎｕｌｌ)
     * @return bool               成功した場合はtrueを、失敗した場合はをfalse返す
     */
    public function changeLeader(array $memberList = null) : bool
    {
        $libGamePlayer = new GamePlayers();
        // メンバーリスト取得
        if (empty($memberList)) {
            $memberList = $libGamePlayer->getRegularMemberByGroupId($this->gameId, $this->groupId);
        }
        // メンバー数を確認し、一人グループの場合は変更なし
        if (count($memberList) <= 1) {
            return false;
        }
        $libGroups = new Groups();
        $groupInfo = $libGroups->getByGroupId($this->gameId, $this->groupId);
        // サブリーダー(サーブリーダーが二人いる場合は、最古参メンバーを指定)
        foreach ($memberList as $member) {
            if ($member->Authority == GROUP_AUTHORITY_SUB_LEADER) {
                $this->subChangeLeader($libGroups, $libGamePlayer, $groupInfo, $member);
                return true;
            }
        }
        // サブリーダーが存在しない場合は、権限３以下で旧リーダーを覗く最古参メンバーをリーダーへ
        foreach ($memberList as $member) {
            if ($member->Authority == GROUP_AUTHORITY_MENBER) {
                $this->subChangeLeader($libGroups, $libGamePlayer, $groupInfo, $member);
                return true;
            }
        }
        return true;
    }

    /**
     * リーダー変更を実施
     * @param Groups      $libGroups     Groupインスタンス
     * @param GamePlayers $libGamePlayer GamePlayerインスタンス
     * @param Bean        $groupInfo     グループ情報を含むBean
     * @param Bean        $member        新リーダーのメンバー情報を含むBean
     */
    private function subChangeLeader(Groups &$libGroups, GamePlayers &$libGamePlayer, Bean &$groupInfo, Bean &$member) : void
    {
        // 任命メッセージ作成
        $umBean = new UserMessageBean();
        $umBean->set("GamePlayerId", SYSTEM_NOTICE_ID);
        $umBean->set("GameNickname", SYSTEM_NOTICE_NAME);
        $umBean->set("Idiom", MESSAGE_CHANGE_LEADER);
        $umBean->set("Message", $groupInfo->GroupName);
        //
        $libGroups->updateLeader($this->gameId, $this->groupId, $member->UserId);
        $libGamePlayer->set($this->gameId, $member->GamePlayerId, ["Authority" => GROUP_AUTHORITY_LEADER]);
        $this->sendUserMessage($member->UserId, $umBean);
    }
}
