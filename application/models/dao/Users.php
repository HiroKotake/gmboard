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
     * 全レコード取得
     * @param  boolean $withDeleted 削除したレコードも含む
     * @return array                レコードを配列として取得
     */
    public function getAll(bool $withDeleted = false) : array
    {
        if (!$withDeleted) {
            $this->db->where('DeleteFlag', '0');
        }
        $query = $this->getQuerySelect(self::TABLE_NAME);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * レコード取得
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getWithCondition(array $data) : array
    {
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $this->db->where($key, $value);
            }
            $this->db->where('DeleteFlag', 0);
            $query = $this->getQuerySelect(self::TABLE_NAME);
            $resultSet = $this->db->query($query);
            return $resultSet->result_array();
        }
        return array();
    }

    public function getByUserId(int $userId) : array
    {
        $data = array('UserId' => $userId);
        return $this->getWithCondition($data);
    }

    public function getByLoginId(string $loginId) : array
    {
        $data = array('LoginId' => $loginId);
        return $this->getWithCondition($data);
    }

    /**
     * レコード追加
     * @param array $data [description]
     */
    public function addNewUser(array $data) : int
    {
        $data['CreateDate'] = date("Y-m-d H:i:s");
        $query = $this->getQueryInsert(self::TABLE_NAME, $data);
        $this->db->query($query);
        return $this->db->insert_id();
    }

    /**
     * レコード更新
     * @param  int    $userId [description]
     * @param  array  $data   [description]
     * @return [type]         [description]
     */
    public function updateWithCondition(int $userId, array $data)
    {
        if (count($data) > 0) {
            $datetime = date("Y-m-d H:i:s");
            $data['UpdateDate'] = $datetime;
            $this->db->where('UserId', $userId);
            $query = $this->getQueryUpdate(self::TABLE_NAME, $data);
            return $this->db->query($query);
        }
        return false;
    }

    /**
     * メール認証終了
     * @param  int    $userId [description]
     * @return [type]         [description]
     */
    public function mailAuthed(int $userId)
    {
        $data = array(
            'MailAuthed'    => 1,
            'LastLogin'     => date("Y-m-d H:i:s")
        );
        return $this->updateWithCondition($userId, $data);
    }

    /**
     * パスワード更新
     * @param  int    $userId    [description]
     * @param  string $hashedPwd [description]
     * @return [type]            [description]
     */
    public function updatePassward(int $userId, string $hashedPwd)
    {
        $data = array(
            'Password' => $hashedPwd
        );
        return $this->updateWithCondition($userId, $data);
    }

    /**
     * 垢バン対応
     * @param int $userId [description]
     */
    public function setLoginExclude(int $userId)
    {
        $data = array(
            'LoginExclude' => 1,
        );
        return $this->updateWithCondition($userId, $data);
    }

    /**
     * 垢バン解除
     * @param int $userId [description]
     */
    public function resetLoginExclude(int $userId)
    {
        $data = array(
            'LoginExclude' => 0,
        );
        return $this->updateWithCondition($userId, $data);
    }

    /**
     * レコード論理削除
     * @param  int    $userId [description]
     * @return [type]         [description]
     */
    public function delete(int $userId)
    {
        $data = array(
            'DeleteDate'    => date("Y-m-d H:i:s"),
            'DeeteFlag'     => 1
        );
        $this->db->where($userId);
        $query = $this->getQueryUpdate(self::TABLE_NAME, $data);
        return $this->db->query($query);
    }
}
