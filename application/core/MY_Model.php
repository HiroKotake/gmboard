<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\utils\LogWriter;
use teleios\utils\Identifier;
use teleios\gmboard\dao\Bean;

class MY_Model extends CI_Model
{
    protected $logWriter = null;
    protected $tableName = null;
    protected $idType = null;
    protected $calledClass = null;
    protected $calledMethod = null;

    public function __construct()
    {
        parent::__construct();
        $this->logWriter = new LogWriter();
    }

    /**
     * 操作するテーブルを設定する
     * 基本的には継承先クラスのコンストラクタ中で'$tableName'にて設定を実施するが、
     * なんらかの理由により対象テーブルを変更したい場合や、継承先クラスの呼び出し元で
     * 設定したといった場合に使用する。
     *
     * @param string $tableName [description]
     */
    public function setTableName(string $tableName) : void
    {
        $this->tableName = $tableName;
    }

    /**
     * クエリーを実行する
     * @param  string $query [description]
     * @return [type]        [description]
     */
    protected function execQuery(string $query)
    {
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $this->db->query($query);
    }

    /**
     * ログを書き出す
     * @param  string $log [description]
     * @return [type]      [description]
     */
    protected function writeLog(string $log)
    {
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($log));
        }
    }

    /**
     * SELECTクエリを取得する
     * @return string [description]
     */
    protected function getQuerySelect() : string
    {
        $query = $this->db->get_compiled_select($this->tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $query;
    }

    /**
     * INSERTクエリを取得する
     * @param  array  $data [description]
     * @return string       [description]
     */
    protected function getQueryInsert(array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_insert($this->tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $query;
    }

    /**
     * UPDATEクエリを取得する
     * @param  array  $data [description]
     * @return string       [description]
     */
    protected function getQueryUpdate(array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_update($this->tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $query;
    }

    /**
     * 対象のテーブルの全レコード数を取得する
     * @param  boolean $withDeleted 論理削除済みレコードもカウントに含める
     * @return int                  レコード数
     */
    public function countAll(bool $withDeleted = false) : int
    {
        $query = "SELECT COUNT(*) AS 'rnum' FROM " . $this->tableName;
        if (!$withDeleted) {
            $query .= " WHERE 'DeleteFlag' = 0";
        }
        $this->logWriter("[$this->calledClass::$this->calledMethod] $query");
        $resultSet = $this->execQuery($query);
        if (empty($resultSet)) {
            return 0;
        }
        $records = $resultSet->result_array();
        return $records[0]['rnum'];
    }

    /**
     * レコードの内容をBeanへ転記する
     * @param  array $data レコードの情報を含んだ連想配列
     * @return Bean        レコードの内容を含んだオブジェクト
     */
    private function setBeanFromArray(array $data) : Bean
    {
        $bean = new Bean($this->idType);
        $excludes = array(
            "CreateDate",
            "UpdateDate",
            "DeleteDate",
            "DeleteFlag"
        );
        foreach ($data as $key => $value) {
            if (in_array($key, $excludes)) {
                continue;
            }
            $bean->$key = $value;
        }
        return $bean;
    }

    /**
     * 複数のレコード情報を含んだ配列をBeanオブジェクトの配列へ転記する
     * @param  array $data [description]
     * @return array       [description]
     */
    private function setBeans(array $data) : array
    {
        $result = array();
        if (empty($data) || !is_array($data)) {
            return array();
        }
        if (!is_array($data[0])) {
            return $result[] = $this->setBeanFromArray($data);
        }
        foreach ($data as $rec) {
            $result[] = $this->setBeanFromArray($rec);
        }
        return $result;
    }

    /**
     * 対象のテーブルのレコードを全取得する
     * @param  integer $limit   [description]
     * @param  integer $offset  [description]
     * @param  boolean $deleted [description]
     * @return array            [description]
     */
    protected function searchAll(int $limit = 0, int $offset = 0, bool $deleted = false) : array
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        // クエリービルド
        if ($limit !== 0) {
            $this->db->limit($limit, $offset);
        }
        // 削除済みを含むか
        if (!$deleted) {
            $this->db->where('DeleteFlag', 0);
        }
        $query = $this->getQuerySelect();
        // log書き込み
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        // クエリー実行
        $resultSet = $this->db->query($query);
        return $this->setBeans($resultSet->result_array());
    }

    private function setWhere(array $cond) : void
    {
        foreach ($cond as $key => $value) {
            if (is_array($value)) {
                if (!empty($value)) {
                    if ($key == "AliasId") {
                        $aliases = array();
                        foreach($value as $alias) {
                            $aliases[] = Identifier::sftDecode($alias);
                        }
                        $this->db->where_in($key, $aliases);
                        continue;
                    }
                    $this->db->where_in($key, $value);
                }
                continue;
            }
            if ($key == "AliasId") {
                if (mb_strlen($value) >= 16) {
                    $this->db->where($key, Identifier::sftDecode($value));
                }
                continue;
            }
            $this->db->where($key, $value);
        }
    }

    /**
     * 検索条件に従いレコードを取得する
     * @param  array  $condition 以下の検索条件を含む連想配列
     *                           'SELECT' => array([Key1], [Key2], ...[KeyX])
     *                           'WHERE' => array('KEY'=>'VALUE',,,)
     *                           'LIKE' => array('KEY'=>'VALUE',,,)
     *                           'NUMBER' => array(limit[,offset])
     *                           'ORDER_BY' => array('KEY'=>'VALUE',,,)
     * @return array             検索結果を含む配列。結果がない場合は空の配列が返される
     */
    protected function search(array $condition) : array
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        // クエリービルド
        if (array_key_exists('SELECT', $condition) && !empty($condition['SELECT'])) {
            $this->db->select($condition['SELECT']);
        }
        if (array_key_exists('WHERE', $condition) && !empty($condition['WHERE'])) {
            $this->setWhere($condition['WHERE']);
        }
        $this->db->where('DeleteFlag', 0);  // 削除されたレコードは無視する
        if (array_key_exists('LIKE', $condition) && !empty($condition['LIKE'])) {
            foreach ($condition['LIKE'] as $key => $value) {
                $this->db->like($key, $value);
            }
        }
        if (array_key_exists('ORDER_BY', $condition) && !empty($condition['ORDER_BY'])) {
            foreach ($condition['ORDER_BY'] as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if (array_key_exists('NUMBER', $condition) && !empty($condition['NUMBER'])) {
            $this->db->limit($condition['NUMBER'][0], (count($condition['NUMBER']) > 1 ? $condition['NUMBER'][1]: 0));
        }
        $query = $this->getQuerySelect();
        // クエリー実行
        $resultSet = $this->db->query($query);
        $records = $resultSet->result_array();
        if (count($records) == 0) {
            return array();
        }
        return $this->setBeans($records);
    }

    /**
     * 検索条件に従いレコードが存在するか確認する
     * @param  array  $condition 以下の検索条件を含む連想配列
     * @return bool              対象が存在する場合は真を返し、存在しない場合は偽を返す
     */
    protected function isExisted(array $condition) : bool
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        // クエリービルド
        if (array_key_exists('SELECT', $condition) && !empty($condition['SELECT'])) {
            $this->db->select($condition['SELECT']);
        }
        if (array_key_exists('WHERE', $condition) && !empty($condition['WHERE'])) {
            $this->setWhere($condition['WHERE']);
        }
        $query = $this->getQuerySelect();
        // クエリー実行
        $resultSet = $this->db->query($query);
        $records = $resultSet->result_array();
        return count($records) != 0 ? true : false;
    }

    /**
     * エイリアスでレコード取得する
     * @param  string $alias エイリアスID
     * @return array         [description]
     */
    public function getByAlias(string $alias) : Bean
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        $cond = array(
            'WHERE' => array('AliasId' => $alias)
        );
        $resultSet = $this->search($cond);
        return $this->getMonoResult($resultSet);
    }

    /**
     * 指定したエイリアスが使用されているか確認する
     * @param  string $alias [description]
     * @return bool 使用されていない場合はTrueを返し、使用されている場合はFalseを返す
     */
    public function isNotExistAlias(string $alias) : bool
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        $cond = array(
            'WHERE' => array('AliasId' => $alias)
        );
        $resultSet = $this->search($cond);
        return (count($resultSet) == 0);
    }

    /**
     * 指定したエイリアスIDで検索し、レコードを取得する
     * @param  string $aliasId エイリアスID文字列
     * @param  int    $subId   サブID (本関数では使用しないが、継承先で引数が二つ必要な場合に備えて配置)
     * @param  int    $subId2  サブID (本関数では使用しないが、継承先で引数が二つ必要な場合に備えて配置)
     * @return array           検索結果を含んだ配列
     */
    public function getByAliasId(string $aliasId, $subId = "", $subId2 = "")
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        $cond = array(
            'WHERE' => array('AliasId' => $aliasId)
        );
        $resultSet = $this->search($cond);
        return $resultSet;
    }

    public function count(string $aliasId, $subId = "", $subId2 = "") : int
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        $query = 'SELECT COUNT(*) FROM ' . $this->tableName . ' WHERE `DeleteFlag` = 0';
        $resultSet = $this->db->simple_query($query);
        return 0;
    }

    /**
     * 検索結果が唯一の検索を実施した場合に、確実に検索結果のみ取り出す。
     * 万一複数の結果が出た場合はエラーとして空の配列にする。
     * @param  array $resultSet searchで得られた結果
     * @return Bean   複数のレコードが含まれている場合は最初の値をそのまま返し、空の場合はからのBeanオブジェクトを返す。
     */
    protected function getMonoResult(array $resultSet) : Bean
    {
        if (empty($resultSet)) {
            return new Bean($this->idType);
        }
        return $resultSet[0];
    }

    /**
     * レコード追加
     * @param  array $data [description]
     * @return int         [description]
     */
    public function attach(array $data) : int
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        // クエリービルド
        if (count($data) > 0) {
            $datetime = date("Y-m-d H:i:s");
            $data['CreateDate'] = $datetime;
            $query = $this->getQueryInsert($data);
            // クエリー実行
            $this->db->query($query);
            $newId = $this->db->insert_id();
            return $newId;
        }
        return 0;
    }

    /**
     * レコード更新
     * @param  array  $data  [description]
     * @param  array  $where [description]
     * @return bool          [description]
     */
    protected function update(array $data, array $where = null) : bool
    {
        if (empty($where)) {
            return false;
        }
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        // クエリービルド
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $this->db->where_in($key, $value);
            } else {
                $this->db->where($key, $value);
            }
        }
        $datetime = date("Y-m-d H:i:s");
        $data['UpdateDate'] = $datetime;
        $query = $this->getQueryUpdate($data);
        // クエリー実行
        return $this->db->simple_query($query);
    }

    /**
     * レコード論理削除
     * @param  array  $where [description]
     * @return bool          [description]
     */
    protected function logicalDelete(array $where = null) : bool
    {
        if (empty($where)) {
            return false;
        }
        // クエリービルド
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $this->db->where_in($key, $value);
            } else {
                $this->db->where($key, $value);
            }
        }
        $datetime = date("Y-m-d H:i:s");
        $data['DeleteDate'] = $datetime;
        $data['DeleteFlag'] = 1;
        $query = $this->getQueryUpdate($data);
        // クエリー実行
        return $this->db->simple_query($query);
    }

    /**
     * 対象のテーブルを初期化する
     * @return bool [description]
     */
    protected function truncate() : bool
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        $query = "TRUNCATE TABLE " . $this->tableName;
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
            $this->db->simple_query($query);
        }
        return false;
    }

    /**
     * 対象のテーブルを削除する
     * @return bool [description]
     */
    protected function drop() : bool
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __FUNCTION__;
        }
        $query = "DROP TABLE " . $this->tableName;
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
            $this->db->simple_query($query);
        }
        return false;
    }
}
