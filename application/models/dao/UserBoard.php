<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ユーザメッセージ管理テーブル操作クラス
 */
class UserBoard extends CI_Model
{
    private $tableName = 'UserBoard';

    public function __construct()
    {
        parent::__construct();
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
     * @param  array   $condition  [description]
     * @param  integer $lineNumber [description]
     * @param  integer $offset     [description]
     * @return [type]              [description]
     */
    public function getWithCondition(
        array $condition,
        int $lineNumber = 100,
        int $offset = 0
    ) {
        if (count($condition) > 0) {
            foreach ($condition as $key => $value) {
                $this->db->where($key, $value);
            }
            $this->db->where('DeleteFlag', 0);
            return $this->db->get(self::$tableName, $lineNumber, $offset);
        }
        return array();
    }

    /**
     * メッセージ追加
     * @param array $data [description]
     */
    public function addNewMessage(array $data)
    {
        $datetime = date("Y-m-d H:i:s");
        $data['CreateDate'] = $datetime;
        $this->db->insert(self::$tableName, $data);
        return $this->db->insert_id();
    }

    /**
     * メッセージ既読処理
     * @param int $messageId [description]
     */
    public function setAlreadyRead(int $messageId)
    {
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'AlreadyRead'   => 1,
            'UpdateDate'    => $datetime
        );
        $this->db->where('MessageId', $messageId);
        return $this->db->update(self::$tableName, $data);
    }

    /**
     * メッセージ削除
     * @param  array  $messageIds [description]
     * @return [type]             [description]
     */
    public function delete(array $messageIds)
    {
        if (count($messageIds) > 0) {
            $datetime = date("Y-m-d H:i:s");
            $data = array(
                'DeleteFlag'   => 1,
                'DeleteDate'    => $datetime
            );
            $this->db->where_in('MessageId', $messageIds);
            return $this->db->update(self::$tableName, $data);
        }
        return false;
    }
}
