<?php
namespace teleios\utils;

use teleios\consts\BaseConsts;

/**
 * ログ書き込みクラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category Utility
 * @package teleios
 */
class LogWriter
{
    /******************************************************/
    /* valiables                                          */
    /******************************************************/
    private $hDbLog;
    private $hDebugLog;

    /******************************************************/
    /* functions                                          */
    /******************************************************/
    public function __construct()
    {
        $today = date("Ymd");
        $dbLogFile = APPPATH . "logs/" . BaseConsts::APP_LOG_DB_PREFIX . gethostname() . "_" . $today . ".log";
        $debugLogFile = APPPATH . "logs/" . BaseConsts::APP_LOG_DEBUG_PREFIX. gethostname() . "_" . $today . ".log";
        try {
            $this->hDbLog = fopen($dbLogFile, "a+");
            $this->hDebugLog = fopen($debugLogFile, "a+");
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function __destruct()
    {
        if ($this->hDbLog) {
            fclose($this->hDbLog);
        }
        if ($this->hDebugLog) {
            fclose($this->hDebugLog);
        }
    }

    public function dbLog($msg)
    {
        $today = date("Y-m-d H:i:s");
        fwrite($this->hDbLog, "[$today] " . $msg . PHP_EOL);
    }

    public function debugLog($msg)
    {
        $today = date("Y-m-d H:i:s");
        fwrite($this->hDebugLog, "[$today] " . $msg . PHP_EOL);
    }
}
