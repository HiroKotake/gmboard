<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ログイン画面
 */
class Test extends MY_Controller
{
    /**
     * テストトップ画面
     * @return [type] [description]
     */
    public function index()
    {
        $this->smarty->testView('top');
    }

    /********************************************************
     * ゲーム情報関連  lib: GameInfo
     ********************************************************/
    // ゲーム情報追加
    public function formGameInfo()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('formGameInfo', $data);
    }

    public function addGameInfo()
    {
        $gameName = $this->input->post('GameName');
        $description = $this->input->post('Description');
        if (empty($gameName) or empty($description)) {
            $data = array(
                'message' => '必須入力項目が入力されていません'
            );
            $this->smarty->testView('formGameInfo', $data);
            return;
        }
        $this->load->model('test/GameInfo', 'testGameInfo');
        $newId = $this->testGameInfo->addGameInfo($gameName, $description);
        $data = array(
            'GameId' => $newId,
            'GameInfo' => $this->testGameInfo->getByGameId($newId)
        );
        $this->smarty->testView('showGameInfo', $data);
    }
    // ゲーム情報一覧表示
    public function listGameInfo()
    {
        $this->load->model('test/GameInfo', 'testGameInfo');
        $data = array(
            'list' => $this->testGameInfo->listGameInfo()
        );
        $this->smarty->testView('listGameInfo', $data);
    }
    // ゲーム情報確認
    public function showGameInfo()
    {
        $gameId = $this->input->get('GameID');
        $this->load->model('test/GameInfo', 'testGameInfo');
        $gameInfo = $this->testGameInfo->showGameInfo((int)$gameId);
        $data = array(
            'WithData' => 1,
            'Message' => '',
            'GameInfo' => $gameInfo
        );
        if (count($gameInfo) == 0) {
            $data['WithData'] = 0;
            $data['Message'] = 'No DATA';
        }
        $this->smarty->testView('showGameInfo', $data);
    }
    /********************************************************
     * ユーザ関連    lib: User
     ********************************************************/
    // ユーザ追加
    public function formUser()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('formUser', $data);
    }
    public function addUser()
    {
        $this->smarty->testView('showUser');
    }
    // ユーザ一覧表示
    public function listUser()
    {
        $this->smarty->testView('listUser');
    }
    // ユーザ情報表示
    public function showUser()
    {
        $this->smarty->testView('showUser');
    }
    // ログイン認証・確認
    public function checkLogin()
    {
        $this->smarty->testView('checkLogin');
    }
    /********************************************************
     * グループ関連   lib: Group
     ********************************************************/
    // グループ追加
    public function formGroup()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('formGroup', $data);
    }
    public function addGroup()
    {
        $this->smarty->testView('showGroup');
    }
    // グループ一覧表示
    public function listGroup()
    {
        $this->smarty->testView('listGroup');
    }
    // グループ情報表示
    public function showGroup()
    {
        $this->smarty->testView('showGroup');
    }
    // グループメンバー追加
    public function addGroupMember()
    {
        $this->smarty->testView('addGroupMember');
    }
    // グループメンバー一覧表示
    public function listGroupMember()
    {
        $this->smarty->testView('listGroupMember');
    }
    /********************************************************
     * 掲示板関連    lib: GroupMessage / UserMessage
     ********************************************************/
    // グループ掲示板へメッセージ追加
    public function formGroupMessage()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('formGroupMessage', $data);
    }
    public function addGroupMessage()
    {
        $this->smarty->testView('addGroupMessage');
    }
    // グループ掲示板表示
    public function showGroupMessage()
    {
        $this->smarty->testView('showGroupMessage');
    }
    // ユーザ掲示板へメッセージ追加
    public function formUserMessage()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('formUserMessage', $data);
    }
    public function addUserMessage()
    {
        $this->smarty->testView('showUserMessage');
    }
    // ユーザ掲示板表示
    public function showUserMessage()
    {
        $this->smarty->testView('showUserMessage');
    }
}
