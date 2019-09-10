<?php
defined('BASEPATH') or exit('No direct script access allowed');

use teleios\utils\StringUtility;

/**
 * ユーザメッセージ管理テーブル操作クラス
 */
class UserBoard extends CI_Model
{
    const TableName = 'UserBoard_';
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
    }

    /**
     * ユーザ用メッセージボードを作成する
     * @param  int    $userId ユーザID
     * @return [type]         [description]
     */
    public function createBoard(int $userId)
    {
        $query = 'CALL CreateUserBoard(' . $userId . ')';
        return $this->db->query($query);
    }

    /**
     * メッセージ取得
     * @param  int     $userId     [description]
     * @param  array   $condition  [description]
     * @param  integer $lineNumber [description]
     * @param  integer $offset     [description]
     * @return array               [description]
     */
    public function getWithCondition(
        int $userId,
        array $condition,
        int $lineNumber = 100,
        int $offset = 0
    ) : array {
        if (count($condition) > 0) {
            foreach ($condition as $key => $value) {
                $this->db->where($key, $value);
            }
            $this->db->where('DeleteFlag', 0);
            $groupBoardName = self::TableName . $this->stringUtil->lpad($userId, "0", 12);
            $resultSet = $this->db->get($groupBoardName, $lineNumber, $offset);
            return $resultSet->result_array();
        }
        return array();
    }

    public function getLastest(int $userId, int $lineNumber = 100, int $offset = 0) : array
    {
        $condition = array(
            'UserId' => $userId,
            'DeleteFlag' => 0
        );
        return $this->getWithCondition($userId, $condition, $lineNumber, $offset);
    }

    /**
     * メッセージ追加
     * @param array $data [description]
     */
    public function addNewMessage(int $userId, array $data)
    {
        $groupBoardName = self::TableName . $this->stringUtil->lpad($userId, "0", 12);
        $datetime = date("Y-m-d H:i:s");
        $data['CreateDate'] = $datetime;
        $this->db->insert($groupBoardName, $data);
        return $this->db->insert_id();
    }

    /**
     * メッセージ既読処理
     * @param int $messageId [description]
     */
    public function setAlreadyRead(int $userId, int $messageId)
    {
        $groupBoardName = self::TableName . $this->stringUtil->lpad($userId, "0", 12);
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'AlreadyRead'   => 1,
            'UpdateDate'    => $datetime
        );
        $this->db->where('MessageId', $messageId);
        return $this->db->update($groupBoardName, $data);
    }

    /**
     * メッセージ削除
     * @param  array  $messageIds [description]
     * @return [type]             [description]
     */
    public function delete(int $userId, array $messageIds)
    {
        if (count($messageIds) > 0) {
            $datetime = date("Y-m-d H:i:s");
            $groupBoardName = self::TableName . $this->stringUtil->lpad($userId, "0", 12);
            $data = array(
                'DeleteFlag'   => 1,
                'DeleteDate'    => $datetime
            );
            $this->db->where_in('MessageId', $messageIds);
            return $this->db->update($groupBoardName, $data);
        }
        return false;
    }
}
