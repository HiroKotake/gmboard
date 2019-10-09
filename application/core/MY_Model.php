<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use teleios\utils\LogWriter;

class MY_Model extends CI_Model
{
    private $logWriter = null;

    public function __construct()
    {
        parent::__construct();
        $this->logWriter = new LogWriter();
    }

    protected function execQuery(string $query)
    {
        return $this->db->query($query);
    }

    protected function writeLog(string $log)
    {
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($log);
        }
    }

    protected function getQuerySelect(string $tableName) : string
    {
        $query = $this->db->get_compiled_select($tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($query);
        }
        return $query;
    }

    protected function getQueryInsert(string $tableName, array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_insert($tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($query);
        }
        return $query;
    }

    protected function getQueryUpdate(string $tableName, array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_update($tableName);
        $this->db->flush_cache();
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($query);
        }
        return $query;
    }

    protected function getAll(string $tableName, int $limit = 0, int $offset = 0, bool $deleted = false) : array
    {
        // クエリービルド
        if ($limit !== 0) {
            $this->db->limit($limit, $offset);
        }
        // 削除済みを含むか
        if ($deleted) {
            $this->db->where('DeleteFlag', 1);
        }
        $query = $this->getQuerySelect($tableName);
        // クエリー実行
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * 検索条件に従いレコードを取得する
     * @param  string $tableName レコード取得対象のテーブル名
     * @param  array  $condition 以下の検索条件を含む連想配列
     *                           'SELECT' => array([Key1], [Key2], ...[KeyX])
     *                           'WHERE' => array('KEY'=>'VALUE',,,)
     *                           'LIKE' => array('KEY'=>'VALUE',,,)
     *                           'NUMBER' => array(limit[,offset])
     *                           'ORDER_BY' => array('KEY'=>'VALUE',,,)
     * @return array             検索結果を含む配列。結果がない場合は空の配列が返される
     */
    protected function get(string $tableName, array $condition) : array
    {
        // クエリービルド
        if (array_key_exists('SELECT', $condition) && !empty($condition['SELECT'])) {
            $this->db->select($condition['SELECT']);
        }
        if (array_key_exists('WHERE', $condition) && !empty($condition['WHERE'])) {
            foreach ($condition['WHERE'] as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
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
        $query = $this->getQuerySelect($tableName);
        // クエリー実行
        $resultSet = $this->db->query($query);
        $records = $resultSet->result_array();
        if (count($records) == 0) {
            return array();
        }
        return $records;
    }

    protected function add(string $tableName, array $data) : int
    {
        // クエリービルド
        $datetime = date("Y-m-d H:i:s");
        $data['CreateDate'] = $datetime;
        $query = $this->getQueryInsert($tableName, $data);
        // クエリー実行
        $this->db->query($query);
        $newId = $this->db->insert_id();
        return $newId;
    }

    protected function update(string $tableName, array $data, array $where = null) : bool
    {
        // クエリービルド
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }
        $datetime = date("Y-m-d H:i:s");
        $data['UpdateDate'] = $datetime;
        $query = $this->getQueryUpdate($tableName, $data);
        // クエリー実行
        return $this->db->simple_query($query);
    }

    protected function delete(string $tableName, array $where = null) : bool
    {
        // クエリービルド
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if (is_array($value)) {
                    $this->db->where_in($key, $value);
                } else {
                    $this->db->where($key, $value);
                }
            }
        }
        $datetime = date("Y-m-d H:i:s");
        $data['DeleteDate'] = $datetime;
        $data['DeleteFlag'] = 1;
        $query = $this->getQueryUpdate($tableName, $data);
        // クエリー実行
        return $this->db->simple_query($query);
    }
}
