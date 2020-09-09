<?php
namespace teleios\gmboard\dao;

use teleios\gmboard\Beans\Bean;
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
        $this->idType = ID_TYPE_GROUP_BOARD;
        $this->calledClass = __CLASS__;
    }

    /**
     * テーブル名を生成する
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @return string          テーブル名
     */
    private function buildTableName(int $gameId, int $groupId) : string
    {
        $tableName = self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8)
                . '_' . $this->stringUtil->lpad($groupId, "0", 8);
        return $tableName;
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
     * @param  integer $lineNumber 取得するメッセージ数　0 を指定した場合は全メッセージ、無指定の場合は２０メッセージを取得する
     * @param  integer $offset     取得するメッセージの開始位置
     * @param  string  $order      メッセージIDを対象とした表示順序の指定
     * @return array               [description]
     */
    public function get(int $gameId, int $groupId, int $lineNumber = 100, int $offset = 0, string $order = 'DESC') : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $cond = array(
            'SELECT' => array('AliasId', 'UserId', 'GameNickname', 'ParentMsgId', 'Idiom', 'Message', 'Showable', 'CreateDate'),
            'ORDER_BY' => array('GBoardMsgId' => $order),
            'LIMIT' => array($lineNumber, $offset)
        );
        return $this->search($cond);
    }

    /**
     * 親メッセージ取得
     * @param  int     $gameId     ゲーム管理ID
     * @param  int     $groupId    グループ管理ID
     * @param  integer $lineNumber 取得するメッセージ数　0 を指定した場合は全メッセージ、無指定の場合は２０メッセージを取得する
     * @param  integer $offset     取得するメッセージの開始位置
     * @param  string  $order      メッセージIDを対象とした表示順序の指定
     * @return array               [description]
     */
    public function getParentMsg(int $gameId, int $groupId, int $lineNumber = 100, int $offset = 0, string $order = 'DESC') : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $cond = array(
            'SELECT' => array('AliasId', 'UserId', 'GameNickname', 'ParentMsgId', 'Idiom', 'Message', 'Showable', 'CreateDate'),
            'ORDER_BY' => array('GBoardMsgId' => $order),
            'LIMIT' => array($lineNumber, $offset),
            'WHERE' => array('ParentMsgId' => 0)
        );
        return $this->search($cond);
    }

    /**
     * 子メッセージ取得
     * @param  int    $gameId      ゲーム管理ID
     * @param  int    $groupId     グループ管理ID
     * @param  int    $parentMsgId 親メッセージID
     * @param  string $order       メッセージIDを対象とした表示順序の指定
     * @return array               [description]
     */
    public function getChildMsg(int $gameId, int $groupId, int $parentMsgId, string $order = 'DESC') : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $cond = array(
            'SELECT' => array('AliasId', 'UserId', 'GameNickname', 'ParentMsgId', 'Idiom', 'Message', 'Showable', 'CreateDate'),
            'ORDER_BY' => array('GBoardMsgId' => $order),
            'WHERE' => array('ParentMsgId' => $parentMsgId)
        );
        return $this->search($cond);
    }

    /**
     * 指定したエイリアスIDで検索し、レコードを取得する
     * [基底クラスのgetByAliasIdのオーバーライド]
     * @param  string $aliasId エイリアスID文字列
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @return Bean            検索結果を含むBeanオブジェクト
     */
    public function getByAliasId(string $aliasId, $gameId, $groupId) : Bean
    {
        $this->calledMethod = __FUNCTION__;

        $this->tableName = $this->buildTableName($gameId, $groupId);
        $cond = array(
            'WHERE' => array('AliasId' => $aliasId)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
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
        $this->tableName = $this->buildTableName($gameId, $groupId);
        return $this->searchAll(0, 0, true);
    }

    /**
     * メッセージの総数を取得する
     * @param  int $gameId  ゲームID
     * @param  int $groupId グループID
     * @return int          メッセージ総数
     */
    public function count(int $gameId, int $groupId) : int
    {
        $this->tableName = $this->buildTableName($gameId, $groupId);
        return parent::count();
    }

    /**
     * 親メッセージの数を取得する
     * @param  int   $gameId  ゲームID
     * @param  int   $groupId グループID
     * @return array          メッセージ総数
     */
    public function countByParentMsg(int $gameId, int $groupId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $query = 'SELECT COUNT(*) AS "Num" FROM ' . $this->tableName . ' WHERE `DeleteFlag` = 0 AND `ParentMsgId` = 0';
        $this->writeLog($query);
        $resultSet = $this->db->query($query);
        return $this->getMonoResult($this->setBeans($resultSet->result_array()));
    }
    /**
     * 親メッセージ別の子メッセージ数を取得する
     * @param  int   $gameId  ゲームID
     * @param  int   $groupId グループID
     * @return array          メッセージ総数
     */
    public function countByChildMsg(int $gameId, int $groupId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $query = 'SELECT `ParentMsgId`, COUNT(*) AS "Num" FROM ' . $this->tableName . ' GROUP BY `ParentMsgId` WHERE `DeleteFlag` = 0';
        $this->writeLog($query);
        $resultSet = $this->db->query($query);
        return $this->setBeans($resultSet->result_array());
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
        $this->tableName = $this->buildTableName($gameId, $groupId);
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
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $data = array(
            'Message'   => $message
        );
        return $this->update($data, array('GBoardMsgId' => $messageId));
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
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $data = array(
            'Showable'      => 0
        );
        return $this->update($data, array('GBoardMsgId' => $messageId));
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
        $this->tableName = $this->buildTableName($gameId, $groupId);
        $data = array(
            'Showable'      => 1,
        );
        return $this->update($data, array('GBoardMsgId' => $messageId));
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
        $this->tableName = $this->buildTableName($gameId, $groupId);
        return $this->logicalDelete(array('GBoardMsgId' => $messageId));
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
        $this->tableName = $this->buildTableName($gameId, $groupId);
        return $this->truncate();
    }
}
