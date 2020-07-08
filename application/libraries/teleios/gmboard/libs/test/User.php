<?php
namespace teleios\gmboard\libs\test;

use teleios\utils\StringUtility;
use teleios\gmboard\dao\Users;

/**
 * テスト環境向ユーザ関連クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category library
 * @package teleios\gmboard
 */
class User
{
    private $daoUsers = null;

    public function __construct()
    {
        $this->daoUsers = new Users();
    }

    /**
     * ログインIDが重複していないか判定
     * @param  string $loginId [description]
     * @return bool            [description]
     */
    public function checkDeplicate(string $loginId) : bool
    {
        $result = $this->daoUsers->getByLoginId($loginId);
        if (count($result) > 0) {
            return false;
        }
        return true;
    }

    /**
     * ユーザを登録する
     * @param  string $loginId  [description]
     * @param  string $passwd   [description]
     * @param  string $nickname [description]
     * @param  string $mailAddr [description]
     * @return int              [description]
     */
    public function addUser(
        string $loginId,
        string $passwd,
        string $nickname,
        string $mailAddr
    ) : int {
        // ユーザ登録
        $sUtil = new StringUtility();
        $data = array(
            'LoginId'   => $loginId,                                // ログインID
            'Password'  => $sUtil->getHashedPassword($passwd),      // ハッシュ済みパスワード
            'Nickname'  => $nickname,                               // ニックネーム
            'Mail'      => $mailAddr                                // 連絡先メールアドレス
        );
        $userId = $this->daoUsers->add($data);
        // ユーザ用掲示板作成
        $daoUserBoard  = new UserBoard();
        $daoUserBoard->createTable($userId);
        // ウェルカムメッセージ追加
        $welcomeMessage = array(
            'FromUserId'    => SYSTEM_USER_ID,                // 送信者ユーザID
            'FromGroupId'   => SYSTEM_GROUP_ID,               // 送信者グループID
            'message'       => 'ようこそ、いらっしょいました！'    // メッセージテキスト
        );
        $this->daoUserBoard->add($userId, $welcomeMessage);

        return $userId;
    }

    /**
     * ユーザ一覧を取得
     * @return array [description]
     */
    public function listUser() : array
    {
        return $this->daoUsers->getAll();
    }

    /**
     * 指定したユーザの情報を取得
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function showUser(int $userId) : array
    {
        $result = $this->daoUsers->get($userId);
        if (!$result) {
            return array();
        }
        return $result;
    }

    /**
     * 認証を実施
     * @param  string $loginId [description]
     * @param  string $passwd  [description]
     * @return bool            [description]
     */
    public function auth(string $loginId, string $passwd) : bool
    {
        $result = $this->daoUsers->getByLoginId($loginId);
        if (count($result) > 0) {
            return password_verify($passwd, $result['Password']);
        }
        return false;
    }
}
