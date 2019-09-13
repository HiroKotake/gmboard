<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\utils\StringUtility;

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
        $result = $this->testGameInfo->addGameInfo($gameName, $description);
        $data = array(
            'Message'  => '',
            'GameId'   => $result[0]['GameId'],
            'GameInfo' => $result[0]
        );
        if (count($result) == 0) {
            $data['Message'] = 'ゲーム情報の追加に失敗しました。<br />';
        }
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
            'Message' => '',
            'GameInfo' => $gameInfo
        );
        if (count($gameInfo) == 0) {
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
            'message' => '入力をお願いします'
        );
        $this->smarty->testView('formUser', $data);
    }
    public function addUser()
    {
        $loginId    = $this->input->post('LoginID');
        $passwd     = $this->input->post('PWD');
        $repasswd   = $this->input->post('RPWD');
        $nickname   = $this->input->post('NNAME');
        $mailAddr   = $this->input->post('MAIL');
        // 入力確認
        $notice = "";
        if (mb_strlen($loginId) == 0) {
            $notice .= 'ログインIDが入力されていません。<br />';
        }
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $loginId)) {
            $notice .= 'ログインIDは半角英数、ハイフン、アンダーバーのみ使用できます。<br />';
        }
        if (mb_strlen($passwd) == 0) {
            $notice .= 'パスワードが入力されていません。<br />';
        }
        if (mb_strlen($repasswd) == 0) {
            $notice .= 'パスワード(確認)が入力されていません。<br />';
        }
        if ($repasswd !== $repasswd) {
            $notice .= '入力されたパスワードと確認用パスワードが異なります<br />';
        }
        if (mb_strlen($nickname) == 0) {
            $notice .= 'ニックネームが入力されていません。<br />';
        }
        if (mb_strlen($mailAddr) == 0) {
            $notice .= 'メールアドレスが入力されていません。<br />';
        }
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $loginId)) {
            $notice .= 'ログインIDは半角英数、ハイフン、アンダーバーのみ使用できます。<br />';
        }
        $sUtil = new StringUtility();
        if (!$sUtil->isMailAddr($mailAddr)) {
            $notice .= 'メールアドレスの記述が間違っています<br />';
        }

        // ログインID重複チェック
        $this->load->model('test/User', 'testUser');
        $isLoginIdDeplicate = $this->testUser->checkDeplicate($loginId);
        if (!$isLoginIdDeplicate) {
            $notice .= '入力されたログインIDは、既に使われています。<br />';
        }

        // エラーがある場合は入力画面を再表示
        if (mb_strlen($notice) > 0) {
            $data = array('message' => $notice);
            $this->smarty->testView('formUser', $data);
            return;
        }

        // 登録
        $newUserId = $this->testUser->addUser($loginId, $passwd, $nickname, $mailAddr);

        // 登録情報取得
        $userInfo = $this->testUser->showUser($newUserId);
        $data = array(
            'Message' => '',
            'UserInfo' => $userInfo
        );

        $this->smarty->testView('showUser', $data);
    }
    // ユーザ一覧表示
    public function listUser()
    {
        $this->load->model('test/User', 'testUser');
        $users = $this->testUser->listUser();
        $message = '';
        if (count($users) == 0) {
            $message = 'ユーザが登録されていません。';
        }
        $data = array(
            'Message' => $message,
            'Users' => $users
        );
        $this->smarty->testView('listUser', $data);
    }
    // ユーザ情報表示
    public function showUser()
    {
        $userId = $this->input->get('UserId');
        $this->load->model('test/User', 'testUser');
        $userInfo = $this->testUser->showUser($userId);
        $message = '';
        $user = null;
        if (count($userInfo) == 0) {
            $message = '該当するユーザは存在しません。';
        } else {
            $user = $userInfo[0];
        }
        $data = array(
            'Message' => $message,
            'UserInfo' => $user
        );
        $this->smarty->testView('showUser', $data);
    }
    // ログイン認証・確認
    public function checkLogin()
    {
        $this->smarty->testView('checkLogin');
    }

    public function doLogin()
    {
        $loginId = $this->input->post('LID');
        $password = $this->input->post('PWD');
        echo 'LoginId：' . $loginId . '<br />';
        echo 'Password：' . $password. '<br />';
        $this->load->model('test/User', 'testUser');
        $result = $this->testUser->auth($loginId, $password);
        if ($result) {
            echo 'ログイン認証成功<br />';
            echo '<a href="./">戻る</a>';
            return;
        }
        echo 'ログイン認証失敗<br />';
        echo '<a href="./">戻る</a>';
    }
    /********************************************************
     * グループ関連   lib: Group
     ********************************************************/
    // グループ追加
    public function formGroup()
    {
        $this->smarty->testView('formGroup', $data);
    }
    public function addGroup()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('showGroup', $data);
    }
    // グループ一覧表示
    public function listGroup()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('listGroup', $data);
    }
    // グループ情報表示
    public function showGroup()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('showGroup', $data);
    }
    // グループメンバー追加
    public function addGroupMember()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('addGroupMember', $data);
    }
    // グループメンバー一覧表示
    public function listGroupMember()
    {
        $data = array(
            'message' => ''
        );
        $this->smarty->testView('listGroupMember', $data);
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
