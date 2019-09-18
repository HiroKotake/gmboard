<?php
defined('BASEPATH') or exit('No direct script access allowed');

use teleios\utils\StringUtility;

/**
 * グループメッセージ管理テーブル操作クラス
 */
class GroupBoard extends MY_Model
{
    const TABLE_NAME = 'GBoard_';
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
    }

    /**
     * グループ用メッセージボードテーブル作成
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function createGroupBoard(int $groupId)
    {
        $query = 'CALL CreateGroupBoard(' . $groupId . ')';
        $this->writeLog($query);
        return $this->db->query($query);
    }

    /**
     * メッセージ取得
     * @param  int     $groupId    グループ管理ID
     * @param  integer $lineNumber 取得するメッセージ数　0 を指定した場合は全メッセージ、無指定の場合は２０メッセージを取得する
     * @return [type]              [description]
     */
    public function getMessages(int $groupId, int $lineNumber = 20, int $offset = 0)
    {
        $table = self::TABLE_NAME . $this->stringUtil->lpad($groupId, "0", 12);
        $this->db->select('UserId, Message, Showable, CreateDate');
        $this->db->where('DeleteFlag', 0);
        $this->db->order_by('id', 'DESC');
        if ($lineNumber !== 0) {
            $this->db->limit($lineNumber, $offset);
        }
        $query = $this->getQuerySelect($table);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * メッセージを追加する
     * @param int    $groupId グループ管理ID
     * @param int    $userId  ユーザ管理ID
     * @param [type] $message メッセージ文
     */
    public function addNewMessage(int $groupId, int $userId, $message)
    {
        $table = self::TABLE_NAME . $this->stringUtil->lpad($groupId, "0", 12);
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'GroupId'       => $groupId,
            'UserId'        => $userId,
            'Message'       => $message,
            'CreateDate'    => $datetime
        );
        $query = $this->getQueryInsert($table, $data);
        $this->db->query($query);
        return $this->db->insert_id();
    }

    /**
     * メッセージの内容を非表示にする
     * @param  int    $groupId グループ管理ID
     * @param  int    $id      メッセージ管理ID
     * @return [type]          [description]
     */
    public function hideMessage(int $groupId, int $lineId)
    {
        $table = self::TABLE_NAME . $this->stringUtil->lpad($groupId, "0", 12);
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'Showable'      => 0,
            'UpdateDate'    => $datetime
        );
        $this->db->where('id', $lineId);
        $query = $this->getQueryUpdate($table, $data);
        return $this->db->query($query);
    }

    /**
     * 非表示にしたメッセージを再度表示させる
     * @param  int    $groupId グループ管理ID
     * @param  int    $id      メッセージ管理ID
     * @return [type]          [description]
     */
    public function showMessage(int $groupId, int $messageId)
    {
        $table = self::TABLE_NAME . $this->stringUtil->lpad($groupId, "0", 12);
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'Showable'      => 1,
            'UpdateDate'    => $datetime
        );
        $this->db->where('messageId', $messageId);
        $query = $this->getQueryUpdate($table, $data);
        return $this->db->query($query);
    }

    /**
     * メッセージを論理削除する
     * @param  int    $groupId グループ管理ID
     * @param  int    $id      メッセージ管理ID
     * @return [type]          [description]
     */
    public function deleteLine(int $groupId, int $messageId)
    {
        $table = self::TABLE_NAME . $this->stringUtil->lpad($groupId, "0", 12);
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'DeleteFlag'    => 1,
            'DeleteDate'    => $datetime
        );
        $this->db->where('messageId', $messageId);
        $query = $this->getQueryUpdate($table, $data);
        return $this->db->query($query);
    }
}
