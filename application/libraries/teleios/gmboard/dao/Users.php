<?php
namespace teleios\gmboard\dao;

/**
 * ユーザ情報管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class Users extends \MY_Model
{
    const TABLE_NAME = TABLE_NAME_USERS;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
    }

    /**
     * ユーザを追加する
     * @param  array $data [description]
     * @return int         [description]
     */
    public function add(array $data) : int
    {
        $this->calledMethod == __FUNCTION__;
        return $this->attach($data);
    }

    /**
     * ユーザ一覧を取得する
     * @param  integer $limit  [description]
     * @param  integer $offset [description]
     * @return array           [description]
     */
    public function getAll(int $limit = 20, int $offset = 0) : array
    {
        $this->calledMethod == __FUNCTION__;
        return $this->searchAll($limit, $offset);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @return array [description]
     */
    public function getAllRecords() : array
    {
        $this->calledMethod == __FUNCTION__;
        return $this->searchAll(0, 0, true);
    }

    /**
     * 指定したユーザIDのレコードを取得する
     * @param  int   $userId [description]
     * @return array         [description]
     */
    public function get(int $userId) : array
    {
        $this->calledMethod == __FUNCTION__;
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * 指定したログインID（メールアドレス）のレコード取得する
     * @param  string $mail [description]
     * @return array        [description]
     */
    public function getByLoginId(string $mail) : array
    {
        $this->calledMethod == __FUNCTION__;
        $cond = array(
            'WHERE' => array('Mail' => $mail)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * レコード更新
     * @param  int   $userId [description]
     * @param  array $data   [description]
     * @return bool          [description]
     */
    public function set(int $userId, array $data) : bool
    {
        $this->calledMethod == __FUNCTION__;
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
        $this->calledMethod == __FUNCTION__;
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
        $this->calledMethod == __FUNCTION__;
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
        $this->calledMethod == __FUNCTION__;
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
        $this->calledMethod == __FUNCTION__;
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
        $this->calledMethod == __FUNCTION__;
        return $this->logicalDelete(array('UserId' => $userId));
    }

    /**
     * テーブルを初期化する
     * @return bool [description]
     */
    public function clearTable() : bool
    {
        $this->calledMethod == __FUNCTION__;
        return $this->truncate();
    }
}
