<?php

use teleios\gmboard\dao\test\Group;

/********************************************************
 * グループ関連   lib: Group
 ********************************************************/
class TestGroup extends MY_Controller
{
    // グループ追加
    public function formGroup()
    {
        // ゲーム一覧取得
        $testGroup = new Group();
        $games = $testGroup->formGroup();
        // データ作成
        $data = array(
            'Message' => '',
            'Games' => $games
        );
        $this->smarty->testView('Group/formGroup', $data);
    }
    public function addGroup()
    {
        $data = array(
            'Message' => ''
        );
        // 入力値チェック
        $testGroup = new Group();
        $gameId      = $this->input->post('TRGT');
        $groupName   = $this->input->post('GNAME');
        $description = $this->input->post('DESCRIP');
        if (mb_strlen($groupName) == 0) {
            $data['Message'] .= 'グループ名が入力されていません。<br />';
        }
        if (mb_strlen($description) == 0) {
            $data['Message'] .= '説明が入力されていません。<br />';
        }
        if (mb_strlen($data['Message']) > 0) {
            $games = $testGroup->formGroup();
            $data['Games'] = $games;
            $this->smarty->testView('Group/formGroup', $data);
            return;
        }
        // グループ登録
        $groupInfo = $testGroup->addGroup((int)$gameId, $groupName, $description);
        if (count($groupInfo) == 0) {
            echo '登録に失敗しました<br /><a href="./">戻る</a>';
            return;
        }
        // グループ内容表示
        $this->load->model('dao/GameInfos', 'daoGameInfos');
        $gameInfo = $this->daoGameInfos->getByGameId($gameId);
        $data['GroupInfo'] = $groupInfo;
        $data['GameName'] = $gameInfo['Name'];
        $this->smarty->testView('Group/showGroup', $data);
    }
    // グループ一覧表示
    public function listGroup()
    {
        $gameId = $this->input->get('GID');
        $testGroup = new Group();
        $groupList = $testGroup->listGroup((int)$gameId);
        $games = $testGroup->formGroup();
        $data = array(
            'Message' => '',
            'GameId' => $gameId,
            'Games' => $games,
            'GroupList' => $groupList
        );
        if (count($groupList) == 0) {
            $data['Message'] = 'グループは登録されていません。';
        }
        $this->smarty->testView('Group/listGroup', $data);
    }
    // グループ情報表示
    public function showGroup()
    {
        $gameId = $this->input->get('GID');
        $groupId = $this->input->get('GPID');
        $testGroup = new Group();
        $groupInfo = $testGroup->showGroup((int)$gameId, (int)$groupId);
        $data = array(
            'Message' => '',
            'GroupInfo' => $groupInfo
        );
        if (count($groupInfo) == 0) {
            $data['Message'] = '該当するグループはありません！';
        }
        $data['GameName'] = $groupInfo['GameName'];
        $this->smarty->testView('Group/showGroup', $data);
    }
}
