<?php
namespace teleios\utils;

use teleios\consts\BaseConsts;

/**
 * データベーステーブルリスト取得クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category Utility
 * @package teleios
 */
class DBTables
{
    /******************************************************/
    /* valiables                                          */
    /******************************************************/
    private $_dbconf;
    private $_cachedTables = false;
    private $_dbTableList;
    private $_tables;

    /******************************************************/
    /* functions                                          */
    /******************************************************/
    public function __construct()
    {
        $dbconfig = APPPATH . "config/" . ENVIRONMENT . "/database.php";
        require $dbconfig;
        $this->_cachedTables = $cached_tables;
        $this->_dbconf = $db;
        if (!$this->_cachedTables && is_array($dbTableList))
        {
            $this->_dbTableList = $dbTableList;
        }
    }

    /**
     * データベース設定を取得する
     *
     * @param なし
     * @return array
     **/
    public function getDBConf()
    {
        return $this->_dbconf;
    }

    /**
     * データベース毎の保持テーブルを取得する
     *
     * @param String $dbGroupName 検索するデータベースグループ名、無指定の場合は設定されているデータベースグループ全て
     * @return array "テーブル->データベースグループ"を持つ配列を返す
     **/
    public function getTableList($dbGroupName = null)
    {
        $tableList = array();
        if (is_null($dbGroupName)) {
            // 全データベースグループを検索
            foreach ($this->_dbconf as $dbGroupName => $data) {
                $dbName = $data["database"];
                if ($this->_cachedTables) {
                    $tempList = $this->queryTableList($dbGroupName, $dbName);
                } else {
                    $tempList = $this->loadTableList($dbGroupName);
                }
                foreach ($tempList as $tableName) {
                    $tableList[$tableName] = $dbGroupName;
                }
            }
        } else {
            // 指定されたデータベースグループのみ検索
            $dbName = $this->_dbconf[$dbGroupName]["database"];
            if ($this->_cachedTables) {
                $tempList = $this->queryTableList($dbGroupName, $dbName);
            } else {
                $tempList = $this->loadTableList($dbGroupName);
            }
            foreach ($tempList as $tableName) {
                $tableList[$tableName] = $dbGroupName;
            }
        }
        return $tableList;
    }

    /**
     * テーブル一覧を取得するクエリーを発行する
     *
     * @param String $dbGroupName データベースグループ名
     * @param String $dbName データベース名
     * @return array テーブル一覧
     **/
    public function queryTableList($dbGroupName, $dbName)
    {
        //
        $mcu = BaseConsts::MEMCACHE_TYPE == 'memcache' ? 'MemCacheUtility' : 'RedisUtility';
        $memcacheKey = BaseConsts::MEMCACHE_KEY_DB_TABLE_LIST . $dbGroupName;
        $tableList = $mcu::get($memcacheKey, BaseConsts::MEMCACHE_SVR_LOCAL);
        if (!empty($tableList)) {
            return $tableList;
        }
        $tableList = array();
        //
        //
        $CI =& get_instance();
        $CI->load->database($dbGroupName);
        $query = "SHOW TABLES FROM $dbName";
        $result = $CI->db->query($query);
        //
        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                foreach ($row as $value) {
                    $tableList[] = $value;
                }
            }
        }
        $CI->db->close();
        //
        $mcu::set($memcacheKey, $tableList, BaseConsts::MEMCACHE_SVR_LOCAL, 0);
        //
        return $tableList;
    }

    /**
     * テーブル一覧を設定ファイルから取得
     *
     * @param String $dbGroupName データベースグループ名
     * @return array テーブル一覧
     **/
    public function loadTableList($dbGroupName = "default")
    {
        return $this->_dbTableList[$dbGroupName];
    }
}
