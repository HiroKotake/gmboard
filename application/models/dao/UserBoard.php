<?php
defined('BASEPATH') or exit('No direct script access allowed');

use teleios\utils\StringUtility;

/**
 * ユーザメッセージ管理テーブル操作クラス
 */
class UserBoard extends MY_Model
{
    const TABLE_NAME = 'UBoard_';
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
    public function createTable(int $userId) : bool
    {
        $query = 'CALL CreateUserBoard(' . $userId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    /**
     * メッセージ取得
     * @param  int     $userId     [description]
     * @param  integer $lineNumber [description]
     * @param  integer $offset     [description]
     * @return array               [description]
     */
    public function getMessage(int $userId, int $lineNumber = 100, int $offset = 0) : array
    {
        $userBoardName = self::TABLE_NAME . $this->stringUtil->lpad($userId, "0", 12);
        $cond = array(
            'WHERE' => array('UserId' => $userId),
            'LIMIT' => array($lineNumber, $offset)
        );
        return $this->get($userBoardName, $cond);
    }

    /**
     * メッセージ追加
     * @param  int   $userId [description]
     * @param  array $data   [description]
     * @return int           [description]
     */
    public function addNewMessage(int $userId, array $data) : int
    {
        $userBoardName = self::TABLE_NAME . $this->stringUtil->lpad($userId, "0", 12);
        return $this->add($userBoardName, $data);
    }

    /**
     * メッセージ既読処理
     * @param  int  $userId    [description]
     * @param  int  $messageId [description]
     * @return bool            [description]
     */
    public function setAlreadyRead(int $userId, int $messageId) : bool
    {
        $userBoardName = self::TABLE_NAME . $this->stringUtil->lpad($userId, "0", 12);
        $data = array(
            'AlreadyRead'   => 1,
        );
        return $this->update($userBoardName, $data, array('MessageId' => $messageId));
    }

    /**
     * メッセージ削除
     * @param  array  $messageIds [description]
     * @return [type]             [description]
     */
    /**
     * メッセージ削除
     * @param  int   $userId     [description]
     * @param  array $messageIds [description]
     * @return bool              [description]
     */
    public function deleteMessages(int $userId, array $messageIds) : bool
    {
        if (count($messageIds) > 0) {
            $userBoardName = self::TABLE_NAME . $this->stringUtil->lpad($userId, "0", 12);
            return $this->delete($userBoardName, array('MessageId' => $messageIds));
        }
        return false;
    }
}
