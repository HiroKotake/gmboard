<?php
namespace teleios\gmboard\Beans;

/**
 * BaseBean
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category bean
 * @package teleios\gmboard
 */
class BaseBean
{
    /**
     * カラム名をキーとした配列
     * @var array
     */
    protected $property;
    /**
     * 対応するカラムの属性情報
     * @var array
     */
    protected $attribute;

    /**
     * 値を設定する
     * @param  string  $key   カラム名
     * @param  [type]  $value 設定値
     * @return boolean        カラムに設定されているもの以外をカラム名にした場合には、falseを返し、値が設定できた場合にはtrueを返す。また設定値がそのカラムの属性と異なる場合には値を設定せずfalseを返す
     */
    public function set(sting $key, $value) : boolean
    {
        if (in_array($key, array_keys($this->property))) {
            if ($this->attribute[$key] == gettype($value)) {
                $this->property[$key] = $value;
                return true;
            }
        }
        return false;
    }

    /**
     * 指定した変数名(この場合はカラム名)の値を取得する
     * @param  string $key カラム名
     * @return [type]      設定されている場合はその値を、設定されていない場合はNullを返す
     */
    public function __get(string $key)
    {
        if (in_array($key, array_keys($this->property))) {
            return $this->property[$key];
        }
        return false;
    }

    /**
     * レコード追加用の配列データを生成する
     * @return array null以外を設定されているカラムのデータを元した配列データを返す。値が全く設定されていない場合は空の配列を返す
     */
    public function getInsertData() : array
    {
        $data = array();
        foreach ($this->property as $key=>$value) {
            if ($value != null) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
