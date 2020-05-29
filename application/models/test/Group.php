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
    /**
     * グループ追加
     * @param  int    $gameId      [description]
     * @param  string $groupName   [description]
     * @param  string $description [description]
     * @return array               [description]
     */
    public function addGroup(int $gameId, string $groupName, string $description) : array
    {
        // グループ登録
        $data = array(
            'GroupName'     => $groupName,      // グループ名
            'Leader'        => SYSTEM_USER_ID,  // リーダーのユーザID
            'Description'   => $description     // 説明
        );
        // リータ追加
        $newGroupId = $this->cIns->daoGroups->add($gameId, $data);
        // グループ掲示板作成
        $this->cIns->load->model('dao/GroupBoard', 'daoGroupBoard');
        $this->cIns->daoGroupBoard->createTable($gameId, $newGroupId);
        // グループ告知枠作成
        $this->cIns->load->model('dao/GroupNotices', 'daoGroupNotices');
        $this->cIns->daoGroupNotices->createTable($gameId, $newGroupId);
        // グループ情報取得
        $result = $this->cIns->daoGroups->getByGroupId($gameId, $newGroupId);
        if (count($result) == 0) {
            return array();
        }
        return $result[0];
    }

    public function listGroup(int $gameId) : array
    {
        $groups = $this->cIns->daoGroups->getAll($gameId);
        return $groups;
    }

    public function showGroup(int $gameId, int $groupId) : array
    {
        $group = $this->cIns->daoGroups->getByGroupId($gameId, $groupId);
        if (count($group) == 0) {
            return array();
        }
        $this->cIns->load->model('dao/GameInfos', 'daoGameInfos');
        $gameInfo = $this->cIns->daoGameInfos->getByGameId($gameId);
        if (count($gameInfo) == 0) {
            $group['GameName'] = '未登録ゲーム';
            return $group;
        }
        $group['GameName'] = $gameInfo['Name'];
        return $group;
    }
}
