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
class SystemCommon extends \MY_Model
{
    const TABLE_NAME = TABLE_NAME_SYSTEM_COMMON;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->idType = ID_TYPE_SYSTEM_COMMON;
        $this->calledClass = __CLASS__;
    }

    /**
     * SystemCommonテーブルへ値を設定する
     * @param string $name  [description]
     * @param mixed  $value [description]
     */
    public function set(string $name, $value)
    {
        $this->calledMethod = __FUNCTION__;
        $type = gettype($value);
        if (empty($this->get($name))) {
            // 対象のデータがなければ追加
            $data = array(
                'Name' => $name,
                'Type' => $type,
                'Value' => serialize($value)
            );
            // ゲームレコード追加
            return $this->attach($data);
        }
        // 対象のデータが存在する場合は、上書き保存
        $data = array(
            'Type' => $type,
            'Value' => serialize($value)
        );
        return $this->update($data, array('Name' => $name));
    }

    /**
     * SystemCommonテーブルから値を取り出す
     * @param  string $name [description]
     * @return mixed        [description]
     */
    public function get(string $name)
    {
        $this->calledMethod = __FUNCTION__;
        $cond = array(
            'WHERE' => array('Name' => $name),
        );
        $result = $this->search($cond);
        $temp = $this->getMonoResult($result);
        if ($temp->isEmpty()) {
            return null;
        }
        return unserialize($temp->Value);
    }

    /**
     * 論理削除されたレコードを含む全レコードを取得する
     * @return array [description]
     */
    public function getAllRecords() : array
    {
        $this->calledMethod = __FUNCTION__;
        return $this->searchAll(0, 0, true);
    }

    /**
     * テーブルを初期化する
     * @return bool [description]
     */
    public function clearTable() : bool
    {
        $this->calledMethod = __FUNCTION__;
        return $this->truncate();
    }

}
