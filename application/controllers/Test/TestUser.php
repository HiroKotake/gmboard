<?php

use teleios\utils\StringUtility;

/********************************************************
 * ユーザ関連１   lib: User
 ********************************************************/
class TestUser extends MY_Controller
{
    // ユーザ追加
    public function formUser()
    {
        $data = array(
            'Message' => '入力をお願いします'
        );
        $this->smarty->testView('User/formUser', $data);
    }
    public function addUser()
    {
        $mailAddr   = $this->input->post('MAIL');
        $passwd     = $this->input->post('PWD');
        $repasswd   = $this->input->post('RPWD');
        $nickname   = $this->input->post('NNAME');
        // 入力確認
        $notice = "";
        if (mb_strlen($mailAddr) == 0) {
            $notice .= 'メールアドレスが入力されていません。<br />';
        }
        if (!StringUtility::isMailAddr($mailAddr)) {
            $notice .= 'メールアドレスの記述が間違っています<br />';
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
        /*
        if (!preg_match("/^[a-zA-Z0-9_-]+$/", $loginId)) {
            $notice .= 'ログインIDは半角英数、ハイフン、アンダーバーのみ使用できます。<br />';
        }
        */

        // ログインID重複チェック
        $this->load->model('test/User', 'testUser');
        $isLoginIdDeplicate = $this->testUser->checkDeplicate($mailAddr);
        if (!$isLoginIdDeplicate) {
            $notice .= '入力されたメールアドレスは、既に使われています。<br />';
        }

        // エラーがある場合は入力画面を再表示
        if (mb_strlen($notice) > 0) {
            $data = array('Message' => $notice);
            $this->smarty->testView('User/formUser', $data);
            return;
        }

        // 登録
        $newUserId = $this->testUser->addUser($mailAddr, $passwd, $nickname, $mailAddr);

        // 登録情報取得
        $userInfo = $this->testUser->showUser($newUserId);
        $data = array(
            'Message' => '',
            'UserInfo' => $userInfo[0]
        );

        $this->smarty->testView('User/showUser', $data);
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
        $this->smarty->testView('User/listUser', $data);
    }
    // ユーザ情報表示
    public function showUser()
    {
        $userId = $this->input->get('UserId');
        $this->load->model('test/User', 'testUser');
        $userInfo = $this->testUser->showUser($userId);
        $message = '';
        if (count($userInfo) == 0) {
            $message = '該当するユーザは存在しません。';
        }
        $data = array(
            'Message' => $message,
            'UserInfo' => $userInfo
        );
        $this->smarty->testView('User/showUser', $data);
    }
    // ログイン認証・確認
    public function checkLogin()
    {
        $this->smarty->testView('User/checkLogin');
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
            echo '<a href="../top">戻る</a>';
            return;
        }
        echo 'ログイン認証失敗<br />';
        echo '<a href="../top">戻る</a>';
    }
}
