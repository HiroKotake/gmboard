<?php
namespace teleios\gmboard\dao;

use teleios\utils\Identifier;

class Bean
{
    private $attribs;

    public function __construct()
    {
        $this->attribs = array();
    }

    public function __set($key, $value) : void
    {
        $name = mb_strtolower($key);
        if ($name == "aliasid") {
            $this->attribs[$name] = Identifier::sftEncode($value);
            return;
        }
        $this->attribs[$name] = $value;
    }

    public function __get($key)
    {
        $name = mb_strtolower($key);
        return $this->attribs[$name];
    }

    /**
     * 保持している値の名称一覧を含む配列を返す
     * @return array ]
     */
    public function getAttribList() : array
    {
        return array_keys($this->attribs);
    }

    /**
     * 保持しているデータが空か確認する。
     * @return bool 空である場合はTrueを、空でない場合はFalseを返す
     */
    public function isEmpty() : bool
    {
        return empty($this->attribs);
    }
}
