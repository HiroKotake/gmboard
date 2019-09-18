<?php

class Group
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/Groups', 'daoGroups');
    }

    public function formGroup()
    {
        // ゲームリスト取得
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        return $this->cIns->daoGameInfos->getAll();
    }
    public function addGroup(int $gameId, string $groupName, string $description) : array
    {
        // グループ登録
        $data = array(
            'GameId'        => $gameId,         // ゲーム管理ID
            'GroupName'     => $groupName,      // グループ名
            'Leader'        => SYSTEM_USER_ID,  // リーダーのユーザID
            'Description'   => $description     // 説明
        );
        $newGroupId = $this->cIns->daoGroups->addGroup($data);
        // グループ掲示板作成
        $this->cIns->load->model('dao/GroupBoard', 'daoGroupBoard');
        $this->cIns->daoGroupBoard->createGroupBoard($newGroupId);
        // グループ告知枠作成
        $this->cIns->load->model('dao/GroupNotices', 'daoGroupNotices');
        $this->cIns->daoGroupNotices->createGroupNotice($newGroupId);
        //
        $result = $this->cIns->daoGroups->getByGroupId($newGroupId);
        if (count($result) == 0) {
            return array();
        }
        return $result[0];
    }

    public function listGroup()
    {
        $groups = $this->cIns->daoGroups->getAll();
        return $groups;
    }

    public function showGroup(int $groupId) : array
    {
        $groupInfos = $this->cIns->daoGroups->getByGroupId($groupId);
        if (count($groupInfos) == 0) {
            return array();
        }
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        $group = $groupInfos[0];
        $gameInfo = $this->cIns->daoGameInfos->getByGameId($group['GameId']);
        if (count($gameInfo) == 0) {
            $group['GameName'] = '未登録ゲーム';
            return $group;
        }
        $group['GameName'] = $gameInfo['Name'];
        return $group;
    }
}
