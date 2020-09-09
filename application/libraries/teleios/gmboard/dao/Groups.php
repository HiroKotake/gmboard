<?php
namespace teleios\gmboard\dao;

use teleios\gmboard\Beans\Bean;
use teleios\utils\StringUtility;

/**
 * グループ情報管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class Groups extends \MY_Model
{
    const TABLE_PREFIX = TABLE_PREFIX_GROUP;
    private $stringUtil = null;

    public function __construct()
    {
        parent::__construct();
        $this->stringUtil = new StringUtility();
        $this->idType = ID_TYPE_GROUP;
        $this->calledClass = __CLASS__;
    }

    /**
     * ゲーム別グループ管理テーブル作成
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @return [type]          [description]
     */
    public function createTable(int $gameId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $query = 'CALL CreateGroup(' . $gameId . ')';
        $this->writeLog($query);
        return $this->db->simple_query($query);
    }

    /**
     * テーブル名を生成する
     * @param  int    $gameId [description]
     * @return string         [description]
     */
    public function getTableName(int $gameId) : string
    {
        return self::TABLE_PREFIX . $this->stringUtil->lpad($gameId, "0", 8);
    }

    /**
     * 指定したゲームの登録グループ一覧を取得する
     * @param  int   $gameId [description]
     * @return array         [description]
     */
    public function getAll(int $gameId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->setTableName($gameId);
        return $this->searchAll();
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @param  int     $gameId ゲーム管理ID
     * @return array [description]
     */
    public function getAllRecords(int $gameId) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->setTableName($this->getTableName($gameId));
        return $this->searchAll(0, 0, true);
    }

    /**
     * グループを追加
     * @param  int   $gameId ゲーム管理ID
     * @param  array $data   'GroupName','Leader(<-UserIdを入れる)','Description'をキー名としたデータを持つ配列
     * @return int           [description]
     */
    public function add(int $gameId, array $data) : int
    {
        $this->calledMethod = __FUNCTION__;
        if (count($data) > 0) {
            $this->setTableName($this->getTableName($gameId));
            return $this->attach($data);
        }
        return false;
    }

    // グループ名検索
    /**
     * グループ名検索
     * @param  int     $gameId    ゲーム管理ID
     * @param  string  $groupName [description]
     * @param  integer $limit     [description]
     * @param  integer $offset    [description]
     * @return array              [description]
     */
    public function getByGroupName(int $gameId, string $groupName, int $limit = 10, int $offset = 0) : array
    {
        $this->calledMethod = __FUNCTION__;
        $this->setTableName($this->getTableName($gameId));
        $cond = array(
            'LIKE' => array('GroupName' => $groupName),
            'LIMIT' => array($limit, $offset),
            'ORDER_BY' => array('GroupId' => 'ASC')
        );
        return $this->search($cond);
    }

    /**
     * グループ名に適合する検索件数を取得する
     * @param  int    $gameId    [description]
     * @param  string $groupName [description]
     * @return int               [description]
     */
    public function countByGroupName(int $gameId, string $groupName) : int
    {
        $query = "SELECT COUNT(*) AS 'rnum' FROM " . $this->getTableName($gameId) . " WHERE `GroupName` LIKE '%$groupName%'";
        $resultSet = $this->execQuery($query);
        $record = $resultSet->result_array();
        return $record[0]["rnum"];
    }

    // グループID検索
    /**
     * グループID検索
     * @param  int   $gameId  ゲーム管理ID
     * @param  int   $groupId [description]
     * @return array          [description]
     */
    public function getByGroupId(int $gameId, int $groupId) : Bean
    {
        $this->calledMethod = __FUNCTION__;
        $this->setTableName($this->getTableName($gameId));
        $cond = array(
            'WHERE' => array('GroupId' => $groupId)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * 指定したエイリアスIDで検索し、レコードを取得する
     * [基底クラスのgetByAliasIdのオーバーライド]
     * @param  string $aliasId エイリアスID文字列
     * @param  int    $gameId  ゲーム管理ID
     * @return Bean            検索結果を含むBeanオブジェクト
     */
    public function getByAliasId(string $aliasId, $gameId) : Bean
    {
        $this->calledMethod = __FUNCTION__;
        $this->setTableName($this->getTableName($gameId));
        $cond = array(
            'WHERE' => array('AliasId' => $aliasId)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * レコードの内容を更新する
     * @param  int   $gameId  ゲーム管理ID
     * @param  int   $groupId グループ管理ID
     * @param  array $data    変更する内容を含んだいフィールド名をキーとした連想配列
     * @return bool           [description]
     */
    public function set(int $gameId, int $groupId, array $data) : bool
    {
        $this->calledMethod = __FUNCTION__;
        if (count($data) > 0) {
            $this->setTableName($this->getTableName($gameId));
            return $this->update($data, array('GroupId' => $groupId));
        }
        return false;
    }

    /**
     * グループ名変更
     * @param  int    $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @param  string $groupName グループ名
     * @return bool              [description]
     */
    public function updateGroupName(int $gameId, int $groupId, string $groupName) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $data = array('GroupName' => $groupName);
        return $this->set($gameId, $groupId, $data);
    }

    /**
     * リーダー変更
     * @param  int  $gameId  ゲーム管理ID
     * @param  int  $groupId グループ管理ID
     * @param  int  $userId  真リーダーのユーザID
     * @return bool          [description]
     */
    public function updateLeader(int $gameId, int $groupId, int $userId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $data = array('Leader' => $userId);
        return $this->set($gameId, $groupId, $data);
    }

    /**
     * 説明文変更
     * @param  int  $gameId  ゲーム管理ID
     * @param  int    $groupId グループ管理ID
     * @param  string $description 説明文
     * @return bool                [description]
     */
    public function updateDescription(int $gameId, int $groupId, string $description) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $data = array('Description' => $description);
        return $this->set($gameId, $groupId, $data);
    }

    /**
     * レコードを論理削除する
     * @param  int  $gameId  ゲーム管理ID
     * @param  int  $groupId グループ管理ID
     * @return bool          [description]
     */
    public function delete(int $gameId, int $groupId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->setTableName($this->getTableName($gameId));
        return $this->logicalDelete(array('GroupId' => $groupId));
    }

    /**
     * 対象のテーブルを初期化する
     * @param  int  $gameId   ゲーム管理ID
     * @return bool         [description]
     */
    public function clearTable(int $gameId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $this->setTableName($this->getTableName($gameId));
        return $this->truncate();
    }
}
