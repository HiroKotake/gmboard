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

    const TABLE_NAME = TABLE_NAME_CI_SESSIONS;

    private $cIns = null;

    public function __construct()
    {
        parent::__construct();
        $this->cIns =& get_instance();
        $this->tableName = self::TABLE_NAME;
        $this->idType = ID_TYPE_CI_SESSION;
        $this->calledClass = __CLASS__;
        $this->cIns->load->library('session');
    }

    /**
     * 対象のキーが設定されているか確認
     * @param  String $key [description]
     * @return bool        [description]
     */
    public function isSet(string $key) : bool
    {
        return $this->cIns->session->has_userdata($key);
    }

    /**
     * セッションに値を設定する
     * @param String $key   [description]
     * @param mix    $value [description]
     */
    public function setSessionData(string $key, $value) : void
    {
        $this->cIns->session->set_userdata($key, $value);
    }

    /**
     * セッションに時限付き値を設定する
     * @param String $key   [description]
     * @param mix    $value [description]
     * @param int    $time  [description]
     */
    public function setExpiredSessionData(string $key, $value, int $time) : void
    {
        $this->setSessionData($key, $value);
        $this->cIns->session->mark_as_temp($key, $time);
    }

    /**
     * 指定されたキーの値を取得する
     * @param  String $key [description]
     * @return mix         [description]
     */
    public function getSessionData(string $key)
    {
        return $this->cIns->session->userdata($key);
    }

    /**
     * セッションの値を削除する
     * @param String $key [description]
     */
    public function delSessionData(string $key) : void
    {
        $this->cIns->session->unset_userdata($key);
    }

    /**
     * セッションの値を全てクリアする
     */
    public function flushAll() : void
    {
        $this->cIns->session->sess_destroy();
    }

    /**
     * セッションデータを物理削除する
     * @param string $sessionId [description]
     */
    public function deleteSession(string $sessionId) :void
    {
        $this->calledMethod = __FUNCTION__;
        $query = "DELETE FROM " . $this->tableName . " WHERE `id` = '" . $sessionId . "'";
        $this->execQuery($query);
    }
}
