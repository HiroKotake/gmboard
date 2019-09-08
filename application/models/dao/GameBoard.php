<?php
namespace gmboard\application\models\dao;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * グループメッセージ管理テーブル操作クラス
 */
class GameBoard extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * グループ用メッセージボードテーブル作成
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function makeGameBoard(int $groupId)
    {
        $query = 'CALL CreateGroupBoard(' . $groupId . ')';
        return $this->db->query($query);
    }

    /**
     * メッセージ取得
     * @param  int     $groupId    グループ管理ID
     * @param  integer $lineNumber 取得するメッセージ数　0 を指定した場合は全メッセージ、無指定の場合は２０メッセージを取得する
     * @return [type]              [description]
     */
    public function getMessages(int $groupId, int $lineNumber = 20)
    {
        $table = 'GBoard_' . $groupId;
        $this->db->select('UserId, Message, Showable, CreateDate');
        $this->db->where('DeleteFlag', 0);
        $this->db->order_by('id', 'DESC');
        $resultSet = $lineNumber > 0 ? $this->db->get($table, $lineNumber, 0) : $this->db->get($table);
        return $resultSet->result();
    }

    /**
     * メッセージを追加する
     * @param int    $groupId グループ管理ID
     * @param int    $userId  ユーザ管理ID
     * @param [type] $message メッセージ文
     */
    public function addNewMessage(int $groupId, int $userId, $message)
    {
        $table = 'GBoard_' . $groupId;
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'GroupId'       => $groupId,
            'UserId'        => $userId,
            'Message'       => $message,
            'CreateDate'    => $datetime
        );
        $this->db->insert($table, $data);
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
        $table = 'GBoard_' . $groupId;
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'Showable'      => 0,
            'UpdateDate'    => $datetime
        );
        $this->db->where('id', $lineId);
        return $this->db->update($table, $data);
    }

    /**
     * 非表示にしたメッセージを再度表示させる
     * @param  int    $groupId グループ管理ID
     * @param  int    $id      メッセージ管理ID
     * @return [type]          [description]
     */
    public function showMessage(int $groupId, int $messageId)
    {
        $table = 'GBoard_' . $groupId;
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'Showable'      => 1,
            'UpdateDate'    => $datetime
        );
        $this->db->where('messageId', $messageId);
        return $this->db->update($table, $data);
    }

    /**
     * メッセージを論理削除する
     * @param  int    $groupId グループ管理ID
     * @param  int    $id      メッセージ管理ID
     * @return [type]          [description]
     */
    public function deleteLine(int $groupId, int $messageId)
    {
        $table = 'GBoard_' . $groupId;
        $datetime = date("Y-m-d H:i:s");
        $data = array(
            'DeleteFlag'    => 1,
            'DeleteDate'    => $datetime
        );
        $this->db->where('messageId', $messageId);
        return $this->db->update($table, $data);
    }
}
