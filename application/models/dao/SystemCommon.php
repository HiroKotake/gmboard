<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ゲーム情報テーブル管理テーブル
 */
class SystemCommon extends MY_Model
{
    const TABLE_NAME = 'SystemCommon';

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
        $this->calledClass = __CLASS__;
    }

    /**
     * SystemCommonテーブルへ値を設定する
     * @param string $name  [description]
     * @param mixed  $value [description]
     */
    public function set(string $name, $value)
    {
        $this->calledMethod == __FUNCTION__;
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
        $this->calledMethod == __FUNCTION__;
        $cond = array(
            'WHERE' => array('Name' => $name),
        );
        $result = $this->search($cond);
        $temp = $this->getMonoResult($result);
        if (empty($temp)) {
            return null;
        }
        return unserialize($temp['Value']);
    }
}
