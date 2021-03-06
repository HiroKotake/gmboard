<?php
namespace teleios\utils;

use \teleios\utils\LogWriter;
/**
 * データベース操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category Utility
 * @package teleios
 */

class DBUtility
{
    /******************************************************/
    /* valiables                                          */
    /******************************************************/
    private static $cvTableList;
    private static $cvDbConfig;
    private static $cvDbConnection = array();
    private static $cvInitialized = false;

    /******************************************************/
    /* functions                                          */
    /******************************************************/

    /**
     * 初期化
     *
     * @param なし
     * @return なし
     */
    public static function initialize()
    {
        $dbTables = new DBTables();
        self::$cvDbConfig = $dbTables->getDBConf();
        self::$cvTableList = $dbTables->getTableList();
        unset($dbTables);
        self::$cvInitialized = true;
    }

    /**
     * DB接続開始
     *
     * @param String $tableName 主テーブル名
     * @return なし
     */
    public static function connect($tableName)
    {
        if (!self::$cvInitialized) {
            self::initialize();
        }
        //
        if (array_key_exists($tableName, self::$cvTableList)) {
            $dbGroupName = self::$cvTableList[$tableName];
            //$dbConfig = self::$cvDbConfig[$dbGroupName];
            if (array_key_exists($dbGroupName, self::$cvDbConfig) && !array_key_exists($dbGroupName, self::$cvDbConnection)) {
                $CI =& get_instance();
                self::$cvDbConnection[$dbGroupName] = $CI->load->database($dbGroupName, true);
            }
        }
    }

    /**
     * DB接続終了
     *
     * @param String $dbGroupName データベースグループ名。省略時は接続している全てのデータベースが対象となる
     * @return なし
     */
    public static function close($dbGroupName = null)
    {
        if (count(self::$cvDbConnection) <= 0) {
            return null;
        }
        // データベースグループ名を指定しない場合は全ての接続を切る
        if (is_null($dbGroupName)) {
            $dbGroupNameList = array_keys(self::$cvDbConnection);
            foreach ($dbGroupNameList as $dbGroupName) {
                self::$cvDbConnection[$dbGroupName]->close();
            }
            self::$cvDbConnection = array();
            return;
        }
        if (array_key_exists($dbGroupName, self::$cvDbConnection)) {
            self::$cvDbConnection[$dbGroupName]->close();
            //
            $tempArray = self::$cvDbConnection;
            self::$cvDbConnection = array();
            foreach ($tempArray as $key => $val) {
                if ($dbGroupName != $key) {
                    self::$cvDbConnection[$key] = $val;
                }
            }
        }
    }

    /**
     * テーブル名をメソッド名として利用し、クエリーを実行させる
     * これはPHPの未定義関数呼び出し時に呼ばる__call関数を使用して実装している
     *
     * @param String $name テーブル名
     * @param String $query SQLクエリー
     * @return ResultSet クエリー実行に成功した場合はリゾルトセットを返し、失敗した場合は空のリゾルトセットを返す。また、対象のテーブルが存在しない場合はnullを返す
     */
    public static function __call($name, $query)
    {
        return self::query($name, $query);
    }

    /**
     * テーブル名をメソッド名として利用し、クエリーを実行させる
     * これはPHPの未定義関数呼び出し時に呼ばる__call関数を使用して実装している
     *
     * @param String $name テーブル名
     * @param String $query SQLクエリー
     * @return ResultSet クエリー実行に成功した場合はリゾルトセットを返し、失敗した場合は空のリゾルトセットを返す。また、対象のテーブルが存在しない場合はnullを返す
     */
    public static function __callStatic($name, $query)
    {
        return self::query($name, $query);
    }

    /**
     * クエリー実行
     *
     * @param String $tableName 主テーブル名
     * @param String $query 実行するSQL文字列
     * @return ResultSet クエリー実行に成功した場合はリゾルトセットを返し、失敗した場合は空のリゾルトセットを返す。また、対象のテーブルが存在しない場合はnullを返す
     */
    public static function query($tableName, $query)
    {
        if (array_key_exists($tableName, self::$cvTableList)) {
            $dbGroupName = self::$cvTableList[$tableName];
            self::$cvDbConnection[$dbGroupName]->reconnect();    // 接続確認。遅いようであればコメントアウトすることを検討
            return self::$cvDbConnection[$dbGroupName]->query($query);
        }
        return null;
    }

    /**
     * CSVファイルからレコードを一括してテーブルへ挿入する
     * @param  String $tableName 挿入するテーブル名
     * @param  String $csvFile   挿入するレコードを含むCSVファイル。一行目はフィールド名を定義する必要がある
     * @return bool              成功した場合には true を、失敗した場合は false を返す
     */
    public static function insertFromCsv($tableName, $csvFile) : bool
    {
        if (!file_exists($csvFile)) {
            // 対象ファイルが無い
            return false;
        }
        try {
            $hFile = fopen($csvFile, "r");
            if (!$hFile) {
                // ファイルオープエラー
                return false;
            }
            // DB へ接続
            $dbGroupName = self::$cvTableList[$tableName];
            self::$cvDbConnection[$dbGroupName]->reconnect();    // 接続確認。遅いようであればコメントアウトすることを検討
            // 行挿入
            $fieldsNames = fgetcsv($hFile);
            $fieldsCount = count($fieldsNames);
            while (($line = fgetcsv($hFile)) !== false) {
                if ($fieldsCount != count($line)) {
                    continue;
                }
                $query = 'INSERT INTO ' . $tableName. '(';
                foreach ($fieldsNames as $field) {
                    $query .= $field . ',';
                }
                rtrim($query, ",");
                $query .= ') VALUES (';
                foreach ($line as $value) {
                    if (is_string($value)) {
                        $query .= '"' . $value . '",';
                    } else {
                        $query .= $value . ',';
                    }
                }
                rtrim($query, ",");
                $query .= ')';
                self::$cvDbConnection[$dbGroupName]->query($query);
            }
        } catch (Exception $e) {
            $logWriter = new LogWriter();
            $logWriter->debugLog($e->getMessage());
            return false;
        } finally {
            // ファイルクローズ
            fclose($hFile);
        }
        return true;
    }

    /**
     * INSERTクエリー実行後の挿入したID番号を取得
     *
     * @param なし
     * @return int ID番号
     */
    public static function getInsertId()
    {
        return self::$cvDbConnection[$dbGroupName]->insert_id();
    }

    /**
     * トランザクション開始
     *
     * @param String $dbGroupName データベースグループ名
     * @return なし
     */
    public static function transactionStart($dbGroupName)
    {
        self::$cvDbConnection[$dbGroupName]->trans_begin();
    }

    /**
     * トランザクション終了
     *
     * @param String $dbGroupName データベースグループ名
     * @return なし
     */
    public static function transactionCommit($dbGroupName)
    {
        self::$cvDbConnection[$dbGroupName]->trans_commit();
    }

    /**
     * トランザクション中止
     *
     * @param String $dbGroupName データベースグループ名
     * @return なし
     */
    public static function transactionRollback($dbGroupName)
    {
        self::$cvDbConnection[$dbGroupName]->trans_rollback();
    }
}
