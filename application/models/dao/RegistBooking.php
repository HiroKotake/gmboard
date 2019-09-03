<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザ登録予約管理テーブル操作クラス
 */
class RegistBooking extends CI_Model
{
    private $tableName = 'RegistBooking';

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
            $resultSet = $this->db->get(self::$tableName);
            return $resultSet->result();
        }
        return array();
    }
    // ゲーム側IDで検索
    public function getByPlayerId(string $playerId)
    {
        return $this->getWithCondition(array('PlayerId' => $playerId));
    }

    // ゲーム側ニックネームで検索
    public function getByNickname(string $gameNickname)
    {
        return $this->getWithCondition(array('GameNickname' => $gameNickname));
    }

    // グループIDで検索
    public function getByGroupId(int $groupId)
    {
        return $this->getWithCondition(array('GroupId' => $groupId));
    }

    // ユーザIDで検索
    public function getByUserId(int $userId)
    {
        return $this->getWithCondition(array('UserId' => $userId));
    }

    // レコード追加
    public function addNewBooking(
        int $groupId,
        string $playerId,
        string $nickname
    ) {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'GroupId'       => $groupId,
            'PlayerId'      => $playerId,
            'Nickname'      => $nickname,
            'CreateDate'    => $datetime
        );
        $this->db->insert(self::$tableName, $data);
        return $this->db->insert_id();
    }

    // 登録済みフラグ更新
    public function registed(int $registBookingId, int $userId)
    {
        $datetime = date("Y-m-d H:i:s");
        $this->db->where('RegistBookingId', $registBookingId);
        $data = array(
            'UserId'        => $userId,
            'Registed'      => 1,
            'UpdateDate'    => $datetime
        );
        return $this->db->update(self::$tableName, $data);
    }

    // レコード論理削除
    public function deleteByRegistBookingId(int $registBookingId)
    {
        $datetime = date("Y-m-d H:i:s");
        $this->db->where('RegistBookingId', $registBookingId);
        $data = array(
            'DeleteDate'    => $datetime,
            'DeleteFlag'    => 1
        );
        return $this->db->update(self::$tableName, $data);
    }

}
