<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ情報管理テーブル操作クラス
 */
class Users extends MY_Model
{
    const TABLE_NAME = 'Users';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * レコード追加
     * @param array $data [description]
     */
    public function addNewUser(array $data) : int
    {
        return $this->add(self::TABLE_NAME, $data);
    }

    public function getAllUsers(int $limit = 20, int $offset = 0) : array
    {
        return $this->getAll(self::TABLE_NAME, $limit, $offset);
    }

    public function getByUserId(int $userId) : array
    {
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        return $this->get(self::TABLE_NAME, $cond);
    }

    public function getByLoginId(string $loginId) : array
    {
        $cond = array(
            'WHERE' => array('LoginId' => $loginId)
        );
        return $this->get(self::TABLE_NAME, $cond);
    }

    /**
     * レコード更新
     * @param  int   $userId [description]
     * @param  array $data   [description]
     * @return bool          [description]
     */
    public function updateWithData(int $userId, array $data) : bool
    {
        if (count($data) > 0) {
            return $this->update(self::TABLE_NAME, $data, array('UserId' => $userId));
        }
        return false;
    }

    /**
     * メール認証終了
     * @param  int  $userId [description]
     * @return bool         [description]
     */
    public function mailAuthed(int $userId) : bool
    {
        $data = array(
            'MailAuthed'    => 1,
            'LastLogin'     => date("Y-m-d H:i:s")
        );
        return $this->updateWithData($userId, $data);
    }

    /**
     * パスワード更新
     * @param  int    $userId    [description]
     * @param  string $hashedPwd [description]
     * @return bool              [description]
     */
    public function updatePassward(int $userId, string $hashedPwd) : bool
    {
        $data = array(
            'Password' => $hashedPwd
        );
        return $this->updateWithData($userId, $data);
    }

    /**
     * 垢バン対応
     * @param  int  $userId [description]
     * @return bool         [description]
     */
    public function setLoginExclude(int $userId) : bool
    {
        $data = array(
            'LoginExclude' => 1,
        );
        return $this->updateWithData($userId, $data);
    }

    /**
     * 垢バン解除
     * @param  int  $userId [description]
     * @return bool         [description]
     */
    public function resetLoginExclude(int $userId) : bool
    {
        $data = array(
            'LoginExclude' => 0,
        );
        return $this->updateWithData($userId, $data);
    }

    /**
     * レコード論理削除
     * @param  int  $userId [description]
     * @return bool         [description]
     */
    public function deleteUser(int $userId) : bool
    {
        return $this->delete(self::TABLE_NAME, array('UserId' => $userId));
    }
}
