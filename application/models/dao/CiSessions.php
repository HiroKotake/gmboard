<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ゲーム情報テーブル管理テーブル
 */
class CiSessions extends MY_Model
{
    const TABLE_NAME = 'CiSessions';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
    }
}
