<?php
namespace teleios\gmboard\dao;

/**
 * ゲーム拡張機能管理テーブル操作クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class GameExtend extends \MY_Model
{

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
        $this->tableName = TABLE_NAME_GAME_EXTEND;
        $this->idType = ID_TYPE_GAME_EXTEND;
        $this->calledClass = __CLASS__;
    }

    /**
     * ゲーム拡張機能情報を追加する
     * @param  Bean $data 追加する情報
     * @return int        [description]
     */
    public function add(Bean $data) : int
    {
        $this->calledMethod = __FUNCTION__;
        $attrList = $data->getAttribList();
        $addData = array();
        foreach ($attrList as $key) {
            $addData[$key] = $data->$key;
        }
        // ゲームレコード追加
        return $this->attach($data);
    }

    /**
     * ゲーム拡張機能情報を取得する
     * @param  int  $gameExtendId   ゲーム拡張機能情報を追加する
     * @return Bean                 成功した場合は対象のレコード情報を含むBeanオブジェクトを、失敗した場合は空のBeanオブジェクトを返す
     */
    public function get(int $gameExtendId) : Bean
    {
        $this->calledMethod = __FUNCTION__;
        $result = $this->search([
            "WHERE" => ["GameExtendId" => $gameExtendId],
            "ORDER_BY" => ["Priority" => "DESC"]
        ]);
        return $this->getMonoResult($result);
    }

    /**
     * ゲーム拡張機能情報を取得する
     * @param  int   $gameId ゲームID
     * @return array         成功した場合は対象のレコード情報を含むBeanオブジェクトの配列を、失敗した場合は配列を返す
     */
    public function getByGameId(int $gameId) : array
    {
        $this->calledMethod = __FUNCTION__;
        return $this->search(["WHERE" => ["GameId" => $gameId]]);
    }

    /**
     * ゲーム拡張機能情報を更新する
     * @param  int  $gameExtendId ゲーム拡張機能情報を更新する対象のID
     * @param  Bean $data         追加する情報
     * @return bool               成功した場合は真を、失敗した場合は偽を返す
     */
    public function set(int $gameExtendId, Bean $data) : bool
    {
        $this->calledMethod = __FUNCTION__;
        $attrList = $data->getAttribList();
        $setData = array();
        foreach ($attrList as $key) {
            $setData[$key] = $data->$key;
        }
        return $this->updata($setData, ['GameExtendId' => $gameExtendId]);
    }

    /**
     * ゲーム拡張機能情報を論理削除する
     * @param  int  ゲーム拡張機能情報を追加する
     * @return bool 成功した場合は真を、失敗した場合は偽を返す
     */
    public function delete(int $gameExtendId) : bool
    {
        $this->calledMethod = __FUNCTION__;
        return $this->logicalDelete(['GamdExtendId' => $gameExtendId]);
    }

    /**
     * テーブルを初期化する
     * @return bool 成功した場合は真を、失敗した場合は偽を返す
     */
    public function clearTable() : bool
    {
        $this->calledMethod = __FUNCTION__;
        return $this->truncate();
    }
}
