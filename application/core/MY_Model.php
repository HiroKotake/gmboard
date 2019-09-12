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
}
