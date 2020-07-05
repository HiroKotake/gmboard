<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\utils\LogWriter;

class MY_Model extends CI_Model
{
    protected $logWriter = null;
    protected $tableName = null;
    protected $calledClass = null;
    protected $calledMethod = null;

    public function __construct()
    {
        parent::__construct();
        $this->logWriter = new LogWriter();
    }

    public function setTableName(string $tableName) : void
    {
        $this->tableName = $tableName;
    }

    protected function execQuery(string $query)
    {
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $this->db->query($query);
    }

    protected function writeLog(string $log)
    {
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($log));
        }
    }

    protected function getQuerySelect() : string
    {
        $query = $this->db->get_compiled_select($this->tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $query;
    }

    protected function getQueryInsert(array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_insert($this->tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $query;
    }

    protected function getQueryUpdate(array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_update($this->tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
        }
        return $query;
    }

    protected function searchAll(int $limit = 0, int $offset = 0, bool $deleted = false) : array
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __METHOD__;
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
        return $resultSet->result_array();
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
            $this->calledMethod = __METHOD__;
        }
        // クエリービルド
        if (array_key_exists('SELECT', $condition) && !empty($condition['SELECT'])) {
            $this->db->select($condition['SELECT']);
        }
        if (array_key_exists('WHERE', $condition) && !empty($condition['WHERE'])) {
            foreach ($condition['WHERE'] as $key => $value) {
                if (is_array($value)) {
                    if (!empty($value)) {
                        // 空の配列でなければ処理に入る 2020-06-15
                        $this->db->where_in($key, $value);
                    }
                } else {
                    $this->db->where($key, $value);
                }
            }
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
        return $records;
    }

    /**
     * 検索条件に従いレコードが存在するか確認する
     * @param  array  $condition 以下の検索条件を含む連想配列
     * @return bool              対象が存在する場合は真を返し、存在しない場合は偽を返す
     */
    protected function isExisted(array $condition) : bool
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __METHOD__;
        }
        // クエリービルド
        if (array_key_exists('SELECT', $condition) && !empty($condition['SELECT'])) {
            $this->db->select($condition['SELECT']);
        }
        if (array_key_exists('WHERE', $condition) && !empty($condition['WHERE'])) {
            foreach ($condition['WHERE'] as $key => $value) {
                if (is_array($value)) {
                    if (!empty($value)) {
                        // 空の配列でなければ処理に入る 2020-06-15
                        $this->db->where_in($key, $value);
                    }
                } else {
                    $this->db->where($key, $value);
                }
            }
        }
        $query = $this->getQuerySelect();
        // クエリー実行
        $resultSet = $this->db->query($query);
        $records = $resultSet->result_array();
        return count($records) != 0 ? true : false;
    }

    /**
     * 検索結果が唯一の検索を実施した場合に、確実に検索結果のみ取り出す。
     * 万一複数の結果が出た場合はエラーとして空の配列にする。
     * @param  array $resultSet searchで得られた結果
     * @return array
     */
    protected function getMonoResult(array $resultSet) : array
    {
        if (count($resultSet) <= 0) {
            return $resultSet;
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
            $this->calledMethod = __METHOD__;
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
            $this->calledMethod = __METHOD__;
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

    protected function truncate() : bool
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __METHOD__;
        }
        $query = "TRUNCATE TABLE " . $this->tableName;
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
            $this->db->simple_query($query);
        }
        return false;
    }

    protected function drop() : bool
    {
        if (empty($this->calledMethod)) {
            $this->calledMethod = __METHOD__;
        }
        $query = "DROP TABLE " . $this->tableName;
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog("[$this->calledClass::$this->calledMethod] " . trim($query));
            $this->db->simple_query($query);
        }
        return false;
    }
}
