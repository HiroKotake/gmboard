<?php
namespace gmboard\application\models\dao;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ情報管理テーブル操作クラス
 */
class Users extends CI_Model
{
    private $tableName = 'Users';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * レコード取得
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function getWithCondition(array $data)
    {
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $this->db->where($key, $value);
            }
            $this->db->where('DeleteFlag', 0);
            $resultSet = $this->db->get(self::$tableName);
            return $resultSet->result_array();
        }
        return array();
    }

    public function getByUserId(int $userId)
    {
        $data = array('UserId' => $userId);
        return $this->getWithCondition($data);
    }

    public function getByLoginId(string $loginId)
    {
        $data = array('LoginId' => $loginId);
        return $this->getWithCondition($data);
    }

    /**
     * レコード追加
     * @param array $data [description]
     */
    public function addNewUser(array $data)
    {
        $data['CreateDate'] = date("Y-m-d H:i:s");
        $this->db->insert(self::$tableName, $data);
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
            return $this->db->update(self::$tableName, $data);
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
        return $this->db->update(self::$tableName, $data);
    }
}
