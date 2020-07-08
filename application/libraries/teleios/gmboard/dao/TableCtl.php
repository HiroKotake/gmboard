<?php
namespace teleios\gmboard\dao;

/**
 * ゲーム情報テーブル管理テーブル
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class TableCtl extends \MY_Model
{
    const TABLE_NAME = '';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
        $this->load->dbforge();
    }

    /**
     * テーブル固有情報を全テーブル共通情報を結合する
     * @param  string $filename [description]
     * @param  string $footer   [description]
     * @return bool             [description]
     */
    public function makeTable(string $filename, string $footer) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $result = false;
        $hFile = fopen($filename, "r");
        $hFooter = fopen($footer, "r");
        if ($hFile) {
            $jsonText = fread($hFile, filesize($filename));
            $data = json_decode($jsonText, true);
            $jsonFooter = fread($hFooter, filesize($footer));
            $dataFooter = json_decode($jsonFooter, true);
            $fieldArray = array_merge($data["Fields"], $dataFooter["Fields"]);
            $this->dbforge->add_field($fieldArray);
            $this->dbforge->add_key($data["PrimaryKey"], true);
            if (!empty($data["Index"])) {
                foreach ($data["Index"] as $indexKey) {
                    $this->dbforge->add_key($indexKey);
                }
            }
            $result = $this->dbforge->create_table($data["TableName"], true, $data["Attribute"]);
            $this->writeLog("TRY CREATE TABLE(". $data["TableName"]. ") <-- " . ($result? "SUCCESS" : "FAILURE"));
            if (!$result && ENVIRONMENT != 'production') {
                $this->logWriter->debugLog($data["TableName"] . " CRATE TRY !");
                $this->logWriter->debugLog(var_export($data));
            }
            fclose($hFooter);
            fclose($hFile);
        }
        return $result;
    }

    /**
     * 指定したテーブルを削除する
     * @param  string $tableName [description]
     * @return bool              [description]
     */
    public function dropTable(string $tableName) : bool
    {
        if (ENVIRONMENT != 'production') {
            // 本番環境以外で有効
            $this->calledMethod = __FUNCTION__;
            $query = "DROP TABLE " . $tableName;
            $result = $this->db->simple_query($query);
            $this->writeLog(trim($query) . " <" . ($result? "SUCCESS" : "FAILURE") . ">");
            return $result;
        }
        return true;
    }

    /**
     * 指定したテーブルをクリアする
     * @param  string $tableName [description]
     * @return bool              [description]
     */
    public function truncateTable(string $tableName) : bool
    {
        if (ENVIRONMENT != 'production') {
            // 本番環境以外で有効
            $this->calledMethod = __FUNCTION__;
            $query = "TRUNCATE TABLE " . $tableName;
            return $this->execQuery($query);
        }
        return false;
    }

    /**
     * 作成済みテーブル一覧を取得する
     * @return array [description]
     */
    public function showTables() : array
    {
        $this->calledMethod = __FUNCTION__;
        $query = "SHOW TABLES";
        $resultSet = $this->execQuery($query);
        return $resultSet->result_array();
    }

    /**
     * 特定の接頭子を持つテーブル一覧を取得する
     * @param  string $subTablePrefix [description]
     * @return array                  [description]
     */
    public function showSubTables(string $subTablePrefix) : array
    {
        $tables = $this->showTables();
        $ptn = '/^' . $subTablePrefix . '/';
        $columName = "Tables_in_" . $this->db->database;
        $tableNames = array();
        foreach ($tables as $record) {
            if (preg_match($ptn, $record[$columName])) {
                $tableNames[] = $record[$columName];
            }
        }
        return $tableNames;
    }

    /**
     * 全テーブルを削除する
     * @return array [description]
     */
    public function dropAllTable() : array
    {
        $tables = $this->showTables();
        $result = array();
        foreach ($tables as $tbl) {
            foreach ($tbl as $value) {
                if ($value != "CiSessions") {
                    $result[$value] = $this->dropTable($value);
                }
            }
        }
        return $result;
    }
}
