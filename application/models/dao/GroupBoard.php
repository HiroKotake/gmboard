<?php

use teleios\utils\StringUtility;

/**
 * グループメッセージ管理テーブル操作クラス
 */
class GroupBoard extends MY_Model
{
    const TABLE_PREFIX = 'GBoard_';
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
    }

    /**
     * グループ用メッセージボードテーブル作成
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function createTable(int $gameId, int $groupId)
    {
        $query = 'CALL CreateGroupBoard(' . $gameId . ', ' . $groupId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    /**
     * メッセージ取得
     * @param  int     $groupId    グループ管理ID
     * @param  string  $order      メッセージIDを対象とした表示順序の指定
     * @param  integer $lineNumber 取得するメッセージ数　0 を指定した場合は全メッセージ、無指定の場合は２０メッセージを取得する
     * @param  integer $offset     取得するメッセージの開始位置
     * @return array               [description]
     */
    public function getMessages(int $gameId, int $groupId, string $order = 'DESC', int $lineNumber = 20, int $offset = 0) : array
    {
        $table = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $cond = array(
            'SELECT' => array('UserId, Message, Showable, CreateDate'),
            'ORDER_BY' => array('MessageId' => $order),
            'LIMIT' => array($lineNumber, $offset)
        );
        return $this->get($table, $cond);
    }

    /**
     * メッセージを追加する
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @param  int    $userId  ユーザID
     * @param  string $message メッセージ
     * @return int             [description]
     */
    public function addNewMessage(int $gameId, int $groupId, int $userId, string $message) : int
    {
        $table = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $data = array(
            'GroupId'       => $groupId,
            'UserId'        => $userId,
            'Message'       => $message
        );
        return $this->add($table, $data);
    }

    /**
     * メッセージの内容を非表示にする
     * @param  int  $gameId    ゲーム管理ID
     * @param  int  $groupId   グループ管理ID
     * @param  int  $messageId メッセージ管理ID
     * @return bool            [description]
     */
    public function hideMessage(int $gameId, int $groupId, int $messageId) : bool
    {
        $table = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $data = array(
            'Showable'      => 0
        );
        return $this->update($table, $data, array('MessageId' => $messageId));
    }

    /**
     * 非表示にしたメッセージを再度表示させる
     * @param  int  $gameId     ゲーム管理ID
     * @param  int  $groupId   グループ管理ID
     * @param  int  $messageId メッセージ管理ID
     * @return bool            [description]
     */
    public function showMessage(int $gameId, int $groupId, int $messageId) : bool
    {
        $table = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $data = array(
            'Showable'      => 1,
        );
        return $this->update($table, $data, array('MessageId' => $messageId));
    }

    /**
     * メッセージを論理削除する
     * @param  int  $gameId    ゲーム管理ID
     * @param  int  $groupId   グループ管理ID
     * @param  int  $messageId メッセージ管理ID
     * @return bool            [description]
     */
    public function deleteLine(int $gameId, int $groupId, int $messageId) : bool
    {
        $table = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        return $this->delete($table, array('MessageId' => $messageId));
    }
}
