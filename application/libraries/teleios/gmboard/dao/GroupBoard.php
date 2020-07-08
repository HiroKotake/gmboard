<?php
namespace teleios\gmboard\dao;

use teleios\utils\StringUtility;

/**
 * グループメッセージ管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class GroupBoard extends \MY_Model
{
    const TABLE_PREFIX = TABLE_PREFIX_GROUP_BOARD;
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
        $this->calledClass = __CLASS__;
    }

    /**
     * グループ用メッセージボードテーブル作成
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function createTable(int $gameId, int $groupId)
    {
        $this->calledMethod = __FUNCTION__;
        $query = 'CALL CreateGroupBoard(' . $gameId . ', ' . $groupId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    /**
     * メッセージ取得
     * @param  int     $gameId     ゲーム管理ID
     * @param  int     $groupId    グループ管理ID
     * @param  string  $order      メッセージIDを対象とした表示順序の指定
     * @param  integer $lineNumber 取得するメッセージ数　0 を指定した場合は全メッセージ、無指定の場合は２０メッセージを取得する
     * @param  integer $offset     取得するメッセージの開始位置
     * @return array               [description]
     */
    public function get(int $gameId, int $groupId, string $order = 'DESC', int $lineNumber = 20, int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $cond = array(
            'SELECT' => array('UserId, Message, Showable, CreateDate'),
            'ORDER_BY' => array('MessageId' => $order),
            'LIMIT' => array($lineNumber, $offset)
        );
        return $this->search($cond);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @param  int     $gameId ゲーム管理ID
     * @param  int     $groupId    グループ管理ID
     * @return array [description]
     */
    public function getAllRecords(int $gameId, int $groupId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        return $this->searchAll(0, 0, true);
    }

    /**
     * メッセージを追加する
     * @param  int    $gameId         [description]
     * @param  int    $groupId        [description]
     * @param  array  $data           以下をデータに持つ連想配列
     *                                UserId         [必須]<br />
     *                                GamePlayerId   [必須]<br />
     *                                GameNickname   [必須]<br />
     *                                Idiom          [任意：指定しない場合は'Message'が必要]<br />
     *                                Message        [任意：Idiom指定されている場合は、無効]<br />
     *                                imagesx        [任意]<br />
     * @return int                    [description]
     */
    public function add(int $gameId, int $groupId, array $data) : int
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        return $this->attach($data);
    }

    /**
     * メッセージの内容を更新する
     * @param  int    $gameId    [description]
     * @param  int    $groupId   [description]
     * @param  int    $messageId [description]
     * @param  string $message   [description]
     * @return bool              [description]
     */
    public function set(int $gameId, int $groupId, int $messageId, string $message) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $data = array(
            'Message'   => $message
        );
        return $this->update($data, array('MessageId' => $messageId));
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
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $data = array(
            'Showable'      => 0
        );
        return $this->update($data, array('MessageId' => $messageId));
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
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        $data = array(
            'Showable'      => 1,
        );
        return $this->update($data, array('MessageId' => $messageId));
    }

    /**
     * メッセージを論理削除する
     * @param  int  $gameId    ゲーム管理ID
     * @param  int  $groupId   グループ管理ID
     * @param  int  $messageId メッセージ管理ID
     * @return bool            [description]
     */
    public function delete(int $gameId, int $groupId, int $messageId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        return $this->logicalDelete(array('MessageId' => $messageId));
    }

    /**
     * 対象のテーブルを初期化する
     * @param  int  $gameId   ゲーム管理ID
     * @param  int  $groupId   グループ管理ID
     * @return bool         [description]
     */
    public function clearTable(int $gameId, int $groupId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 12);
        return $this->truncate();
    }
}
