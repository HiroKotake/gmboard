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

    protected function writeLog(string $log)
    {
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($log);
        }
    }

    protected function getQuerySelect(string $tableName) : string
    {
        $query = $this->db->get_compiled_select($tableName);
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($query);
        }
        return $query;
    }

    protected function getQueryInsert(string $tableName, array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_insert($tableName);
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($query);
        }
        return $query;
    }

    protected function getQueryUpdate(string $tableName, array $data) : string
    {
        $query = $this->db->set($data)->get_compiled_update($tableName);
        if (ENVIRONMENT != 'production') {
            $this->logWriter->dbLog($query);
        }
        return $query;
    }

    protected function getAll(string $tableName, int $limit = 0, int $offset = 0) : array
    {
        if ($limit !== 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->getQuerySelect($tableName);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    /**
     * 検索条件に従いレコードを取得する
     * @param  string $tableName レコード取得対象のテーブル名
     * @param  array  $condition 以下の検索条件を含む連想配列
     *                           'WHERE' => array('KEY'=>'VALUE',,,)
     *                           'LIKE' => array('KEY'=>'VALUE',,,)
     *                           'NUMBER' => array(limit[,offset])
     * @return array             検索結果を含む配列。結果がない場合は空の配列が返される
     */
    protected function get(string $tableName, array $condition) : array
    {
        if (array_key_exists('WHERE', $condition) && !empty($condition['WHERE'])) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $this->db->where('DeleteFlag', 0);
        if (array_key_exists('LIKE', $condition) && !empty($condition['LIKE'])) {
            foreach ($where as $key => $value) {
                $this->db->like($key, $value);
            }
        }
        if (array_key_exists($condition['NUMBER']) && !empty($condition['NUMBER'])) {
            $this->db->limit($condition['NUMBER'][0], (count($condition['NUMBER']) > 1 ? $condition['NUMBER'][1]: 0));
        }
        $query = $this->getQuerySelect($tableName);
        $resultSet = $this->db->query($query);
        return $resultSet->result_array();
    }

    protected function add(string $tableName, array $data, array $where = null) : int
    {
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $datetime = date("Y-m-d H:i:s");
        $data['CreateDate'] = $datetime;
        $query = $this->getQueryInsert($tableName, $data);
        $this->db->query($query);
        return $this->db->insert_id();
    }

    protected function update(string $tableName, array $data, array $where = null) : int
    {
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $datetime = date("Y-m-d H:i:s");
        $data['UpdateDate'] = $datetime;
        $query = $this->getQueryUpdate($tableName, $data);
        return $this->db->query($query);
    }

    protected function delete(string $tableName, array $where = null) : int
    {
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $this->db->where($key, $value);
            }
        }
        $datetime = date("Y-m-d H:i:s");
        $data['DeleteDate'] = $datetime;
        $data['DeleteFlag'] = 1;
        $query = $this->getQueryUpdate($tableName, $data);
        return $this->db->query($query);
    }
}
