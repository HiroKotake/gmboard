
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
    }

    // 登録
    public function addRegistration(array $data) : int
    {
        if (count($data) > 0) {
            return $this->add(self::TABLE_NAME, $data);
        }
        return 0;
    }

    // 検索
    public function getByRcode(string $rcode) : array
    {
        if (!empty($rcode)) {
            $cond = array(
                'WHERE' => array('Rcode' => $rcode)
            );
            return $this->get(self::TABLE_NAME, $cond);
        }
        return null;
    }

    public function getByUserId(string $userId) : array
    {
        if (!empty($userId)) {
            $cond = array(
                'WHERE' => array('UserId' => $userId)
            );
            return $this->get(self::TABLE_NAME, $cond);
        }
        return null;
    }

    // 更新
    public function updateWithData(int $registrationId, array $data) : bool
    {
        if (count($data) > 0) {
            return $this->updata(self::TABLE_NAME, $data, array('RegistrationId' => $registrationId));
        }
        return false;
    }

    // 論理削除
    public function deleteUserInfos(int $registrationId) : bool
    {
        return $this->delete(self::TABLE_NAME, array('RegistrationId' => $registrationId));
    }

}
