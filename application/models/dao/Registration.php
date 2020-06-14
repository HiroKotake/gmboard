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
    public function get(int $regId) : array
    {
        if (!empty($regId)) {
            $cond = array(
                'WHERE' => array('RegistrationId' => $regId)
            );
            $resultSet = $this->search($cond);
            return $this->getMonoResult($resultSet);
        }
        return null;
    }

    public function getByUserId(string $userId) : array
    {
        if (!empty($userId)) {
            $cond = array(
                'WHERE' => array('UserId' => $userId)
            );
            $resultSet = $this->search($cond);
            return $this->getMonoResult($resultSet);
        }
        return null;
    }

    // 更新
    public function set(int $registrationId, array $data) : bool
    {
        if (count($data) > 0) {
            return $this->update($data, array('RegistrationId' => $registrationId));
        }
        return false;
    }

    // 論理削除
    public function delete(int $registrationId) : bool
    {
        return $this->logicalDelete(array('RegistrationId' => $registrationId));
    }

}
