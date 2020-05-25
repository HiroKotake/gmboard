<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ情報管理テーブル操作クラス
 */
class UserInfos extends MY_Model
{
    const TABLE_NAME = 'UsersInfos';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * レコード追加
     * @param array $data [description]
     */
    public function addNewUserInfos(array $data) : int
    {
        return $this->add(self::TABLE_NAME, $data);
    }

    /**
     * レコード取得
     * @param  int $userId [description]
     * @return int         [description]
     */
    public function getUserInfosByUserId(int $userId) : array
    {
        $cond = array(
            'WHERE' => array('UserId' => $userId)
        );
        return $this->get(self::TABLE_NAME, $cond);
    }

    // 修正
    /**
     * レコード更新
     * @param  int   $userId [description]
     * @param  array $data   [description]
     * @return bool          [description]
     */
    public function updateWithData(int $userId, array $data) : bool
    {
        if (count($data) > 0) {
            return $this->updata(self::TABLE_NAME, $data, array('UserId' => $userId));
        }
        return false;
    }

    // 論理削除
    public function deleteUserInfos(int $userId) : bool
    {
        return $this->delete(self::TABLE_NAME, array('UserId' => $userId));
    }
}
