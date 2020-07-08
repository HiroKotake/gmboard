<?php
namespace teleios\gmboard\dao;

/**
 * ユーザ登録管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class Registration extends \MY_Model
{

    const TABLE_NAME = TABLE_NAME_REGISTRATION;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
    }

    /**
     * ユーザ登録申請情報を追加する
     * @param  array $data [description]
     * @return int         [description]
     */
    public function add(array $data) : int
    {
        return $this->attach($data);
    }

    /**
     * ユーザ登録申請情報を取得する
     * @param  int   $regId [description]
     * @return array        [description]
     */
    public function get(int $regId) : array
    {
        $this->calledMethod == __FUNCTION__;
        if (!empty($regId)) {
            $cond = array(
                'WHERE' => array('RegistrationId' => $regId)
            );
            $resultSet = $this->search($cond);
            return $this->getMonoResult($resultSet);
        }
        return null;
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
     * ユーザIDを指定してユーザ登録申請情報を取得する
     * @param  string $userId [description]
     * @return array          [description]
     */
    public function getByUserId(string $userId) : array
    {
        $this->calledMethod == __FUNCTION__;
        if (!empty($userId)) {
            $cond = array(
                'WHERE' => array('UserId' => $userId)
            );
            $resultSet = $this->search($cond);
            return $this->getMonoResult($resultSet);
        }
        return null;
    }

    /**
     * ユーザ登録申請情報を更新する
     * @param  int   $registrationId [description]
     * @param  array $data           [description]
     * @return bool                  [description]
     */
    public function set(int $registrationId, array $data) : bool
    {
        $this->calledMethod == __FUNCTION__;
        if (count($data) > 0) {
            return $this->update($data, array('RegistrationId' => $registrationId));
        }
        return false;
    }

    /**
     * ユーザ登録申請情報を論理削除する
     * @param  int  $registrationId [description]
     * @return bool                 [description]
     */
    public function delete(int $registrationId) : bool
    {
        $this->calledMethod == __FUNCTION__;
        return $this->logicalDelete(array('RegistrationId' => $registrationId));
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