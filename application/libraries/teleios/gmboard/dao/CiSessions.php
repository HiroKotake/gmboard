<?php
namespace teleios\gmboard\dao;

/**
 * セッション情報テーブル管理テーブル
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class CiSessions extends \MY_Model
{
    const TABLE_NAME = 'CiSessions';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
    }
}
