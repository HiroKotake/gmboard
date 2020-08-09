<?php
namespace teleios\gmboard\dao;

use teleios\utils\StringUtility;

/**
 * ユーザメッセージ管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class UserBoard extends \MY_Model
{
    const TABLE_PREFIX = TABLE_PREFIX_USER_BOARD;
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
        $this->idType = ID_TYPE_USER_BOARD;
        $this->calledClass = __CLASS__;
    }

    /**
     * ユーザ用メッセージボードを作成する
     * @param  int    $userId ユーザID
     * @return [type]         [description]
     */
    public function createTable(int $userId) : bool
    {
        $this->calledMethod = __FUNCTION__;
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
    public function get(int $userId, int $lineNumber = 100, int $offset = 0, $order = "DESC") : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($userId, "0", 12);
        $cond = array(
//            'WHERE' => array('UserId' => $userId),
            'LIMIT' => array($lineNumber, $offset),
            'ORDER_BY' => ["CreateDate", $order]
        );
        return $this->search($cond);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @param  int     $userId     [description]
     * @return array [description]
     */
    public function getAllRecords(int $userId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($userId, "0", 12);
        return $this->searchAll(0, 0, true);
    }

    /**
     * 指定したエイリアスIDで検索し、レコードを取得する
     * [基底クラスのgetByAliasIdのオーバーライド]
     * @param  string $aliasId エイリアスID文字列
     * @param  int    $userId  ユーザ管理ID
     * @return Bean            検索結果を含むBeanオブジェクト
     */
    public function getByAliasId(string $aliasId, $userId) : Bean
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($userId, "0", 12);
        $cond = array(
            'WHERE' => array('AliasId' => $aliasId)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * メッセージ追加
     * @param  int   $userId [description]
     * @param  array $data   [description]
     * @return int           [description]
     */
    public function add(int $userId, array $data) : int
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($userId, "0", 12);
        return $this->attach($data);
    }

    /**
     * メッセージ既読処理
     * @param  int  $userId    [description]
     * @param  int  $messageId [description]
     * @return bool            [description]
     */
    public function set(int $userId, int $messageId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($userId, "0", 12);
        $data = array(
            'AlreadyRead'   => 1,
        );
        return $this->update($data, array('UBoardMsgId' => $messageId));
    }

    /**
     * メッセージ削除
     * @param  int   $userId     [description]
     * @param  array $messageIds [description]
     * @return bool              [description]
     */
    public function delete(int $userId, array $messageIds) : bool
    {
        $this->calledMethod = __FUNCTION__;
        if (count($messageIds) > 0) {
            $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($userId, "0", 12);
            return $this->logicalDelete(array('UBoardMsgId' => $messageIds));
        }
        return false;
    }

    /**
     * 対象のテーブルを初期化する
     * @param  int  $userId ユーザID
     * @return bool         [description]
     */
    public function clearTable(int $userId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($userId, "0", 12);
        return $this->truncate();
    }
}
