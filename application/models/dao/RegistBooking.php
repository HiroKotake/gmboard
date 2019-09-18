<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ登録予約管理テーブル操作クラス
 */
class RegistBooking extends MY_Model
{
    const TABLE_NAME = 'RegistBooking';

    public function __construct()
    {
        parent::__construct();
    }

    // 様々な条件で検索
    public function getWithCondition(array $condition)
    {
        if (count($condition) > 0) {
            foreach ($condition as $key => $value) {
                $this->db->where($key, $value);
            }
            $this->db->where('DeleteFlag', 0);
            $query = $this->getQuerySelect(self::TABLE_NAME);
            $resultSet = $this->db->query($query);
            return $resultSet->result_array();
        }
        return array();
    }
    // RegistBookingIdで検索
    public function getByRegistBookingId(int $registBookingId) : array
    {
        return $this->getWithCondition(array('RegistBookingId' => $registBookingId));
    }
    // ゲーム側IDで検索
    public function getByPlayerId(string $playerId) : array
    {
        return $this->getWithCondition(array('PlayerId' => $playerId));
    }

    // ゲーム側ニックネームで検索
    public function getByNickname(string $gameNickname) : array
    {
        return $this->getWithCondition(array('GameNickname' => $gameNickname));
    }

    // グループIDで検索
    public function getByGroupId(int $groupId) : array
    {
        return $this->getWithCondition(array('GroupId' => $groupId));
    }

    // ユーザIDで検索
    public function getByUserId(int $userId) : array
    {
        return $this->getWithCondition(array('UserId' => $userId));
    }

    // レコード追加
    public function addNewBooking(
        int $groupId,
        string $playerId,
        string $nickname,
        string $authCode
    ) : int {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'GroupId'       => $groupId,
            'PlayerId'      => $playerId,
            'GameNickName'  => $nickname,
            'AuthCode'      => $authCode,
            'CreateDate'    => $datetime
        );
        $query = $this->getQueryInsert(self::TABLE_NAME, $data);
        $this->db->query($query);
        return $this->db->insert_id();
    }

    public function updateRegistBooking(int $registBookingId, array $data)
    {
        $this->db->where('RegistBookingId', $registBookingId);
        $query = $this->getQueryUpdate(self::TABLE_NAME, $data);
        return $this->db->query($query);
    }
    // 登録済みフラグ更新
    public function registed(int $registBookingId, int $userId)
    {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'UserId'        => $userId,
            'Registed'      => 1,
            'UpdateDate'    => $datetime
        );
        return $this->updateRegistBooking($registBookingId, $data);
    }

    // レコード論理削除
    public function deleteByRegistBookingId(int $registBookingId)
    {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'DeleteDate'    => $datetime,
            'DeleteFlag'    => 1
        );
        return $this->updateRegistBooking($registBookingId, $data);
    }
}
