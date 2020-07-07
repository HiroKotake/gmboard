<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ゲーム情報テーブル管理テーブル
 */
class TableCtl extends MY_Model
{
    const TABLE_NAME = '';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
        $this->load->dbforge();
    }

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

    public function showTables() : array
    {
        $this->calledMethod = __FUNCTION__;
        $query = "SHOW TABLES";
        $resultSet = $this->execQuery($query);
        return $resultSet->result_array();
    }

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