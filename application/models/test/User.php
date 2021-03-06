<?php

use teleios\utils\StringUtility;

class User
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
    }

    public function checkDeplicate(string $loginId) : bool
    {
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $result = $this->cIns->daoUsers->getByLoginId($loginId);
        if (count($result) > 0) {
            return false;
        }
        return true;
    }

    public function addUser(
        string $loginId,
        string $passwd,
        string $nickname,
        string $mailAddr
    ) : int {
        // ユーザ登録
        $sUtil = new StringUtility();
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $data = array(
            'LoginId'   => $loginId,                                // ログインID
            'Password'  => $sUtil->getHashedPassword($passwd),      // ハッシュ済みパスワード
            'Nickname'  => $nickname,                               // ニックネーム
            'Mail'      => $mailAddr                                // 連絡先メールアドレス
        );
        $userId = $this->cIns->daoUsers->addNewUser($data);
        // ユーザ用掲示板作成
        $this->cIns->load->model('dao/UserBoard', 'daoUserBoard');
        $this->cIns->daoUserBoard->createTable($userId);
        // ウェルカムメッセージ追加
        $welcomeMessage = array(
            'FromUserId'    => SYSTEM_USER_ID,                // 送信者ユーザID
            'FromGroupId'   => SYSTEM_GROUP_ID,               // 送信者グループID
            'message'       => 'ようこそ、いらっしょいました！'    // メッセージテキスト
        );
        $this->cIns->daoUserBoard->addNewMessage($userId, $welcomeMessage);

        return $userId;
    }

    public function listUser() : array
    {
        $this->cIns->load->model('dao/Users', 'daoUsers');
        return $this->cIns->daoUsers->getAllUsers();
    }

    public function showUser(int $userId) : array
    {
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $result = $this->cIns->daoUsers->getByUserId($userId);
        if (!$result) {
            return array();
        }
        return $result;
    }

    public function auth(string $loginId, string $passwd) : bool
    {
        $this->cIns->load->model('dao/Users', 'daoUsers');
        $result = $this->cIns->daoUsers->getByLoginId($loginId);
        if (count($result) > 0) {
            return password_verify($passwd, $result[0]['Password']);
        }
        return false;
    }
}
