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
        $this->tableName = self::TABLE_NAME;
    }


    public function add(array $data) : int
    {
        return $this->attach($data);
    }

    public function getAll(int $limit = 20, int $offset = 0) : array
    {
        return $this->searchAll($limit, $offset);
    }

    public function get(int $userId) : array
    {
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        return $this->search($cond);
    }

    public function getByLoginId(string $mail) : array
    {
        $cond = array(
            'WHERE' => array('Mail' => $mail)
        );
        return $this->search($cond);
    }

    /**
     * レコード更新
     * @param  int   $userId [description]
     * @param  array $data   [description]
     * @return bool          [description]
     */
    public function set(int $userId, array $data) : bool
    {
        if (count($data) > 0) {
            return $this->update($data, array('UserId' => $userId));
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
        return $this->set($userId, $data);
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
        return $this->set($userId, $data);
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
        return $this->set($userId, $data);
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
        return $this->set($userId, $data);
    }

    /**
     * レコード論理削除
     * @param  int  $userId [description]
     * @return bool         [description]
     */
    public function delete(int $userId) : bool
    {
        return $this->logicalDelete(array('UserId' => $userId));
    }
}
