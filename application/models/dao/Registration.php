<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ登録管理テーブル操作クラス
 */
class Registration extends MY_Model
{

    const TABLE_NAME = 'Registration';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
    }

    public function add(array $data) : int
    {
        return $this->attach($data);
    }

    // 検索
    public function get(string $rcode) : array
    {
        if (!empty($rcode)) {
            $cond = array(
                'WHERE' => array('Rcode' => $rcode)
            );
            return $this->search($cond);
        }
        return null;
    }

    public function getByUserId(string $userId) : array
    {
        if (!empty($userId)) {
            $cond = array(
                'WHERE' => array('UserId' => $userId)
            );
            return $this->search($cond);
        }
        return null;
    }

    // 更新
    public function set(int $registrationId, array $data) : bool
    {
        if (count($data) > 0) {
            return $this->updata($data, array('RegistrationId' => $registrationId));
        }
        return false;
    }

    // 論理削除
    public function delete(int $registrationId) : bool
    {
        return $this->logicalDelete(array('RegistrationId' => $registrationId));
    }

}
