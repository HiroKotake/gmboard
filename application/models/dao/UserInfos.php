<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ情報管理テーブル操作クラス
 */
class UserInfos extends MY_Model
{
    const TABLE_NAME = TABLE_NAME_USER_INFOS;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
    }

    /**
     * レコード取得
     * @param  int $userId [description]
     * @return int         [description]
     */
    public function get(int $userId) : array
    {
        $this->calledMethod == __FUNCTION__;
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        return $this->search($cond);
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

    // 修正
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
            return $this->updata($data, array('UserId' => $userId));
        }
        return false;
    }

    // 論理削除
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
