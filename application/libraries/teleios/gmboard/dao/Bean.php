<?php
namespace teleios\gmboard\dao;

use teleios\utils\Identifier;
use teleios\gmboard\dao\CiSessions;

class Bean
{
    private $attribs;
    private $primaryId;

    public function __construct()
    {
        $this->attribs = array();
    }

    public function __destruct()
    {
        $aliasId = $this->hasAlias();
        if (!empty($aliasId)) {
            $daoCiSessions = new CiSessions();
            $aliasList = array();
            if ($daoCiSessions->isSet(SESSION_LIST_ALIAS)) {
                $aliasList = unserialize($daoCiSessions->getSessionData(SESSION_LIST_ALIAS));
            }
            if (!array_key_exists($aliasId, $aliasList)) {
                $aliasList[$aliasId] = $this->primaryId;
                $daoCiSessions->setSessionData(SESSION_LIST_ALIAS, serialize($aliasList));
            }
        }
    }

    public function __set($key, $value) : void
    {
        $name = mb_strtolower($key);
        if ($name == "aliasid") {
            $this->attribs[$name] = Identifier::sftEncode($value);
            return;
        }
        if ($name != "aliasid" && substr($name, -2) == "id") {
            $this->primaryId = array(
                "name" => $key,
                "id" => $value
            );
        }
        $this->attribs[$key] = $value;
    }

    public function __get($key)
    {
        $name = mb_strtolower($key);
        if ($name == "aliasid") {
            return $this->attribs[$name];
        }
        return array_key_exists($key, $this->attribs) ? $this->attribs[$key] : null;
    }

    /**
     * 保持している値の名称一覧を含む配列を返す
     * @return array 変数名の配列
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

    /**
     * エイリアスIDを持っているか確認する
     * @return string 持っている場合はエイリアスIDを返し、持っていない場合は空文字を返す。
     */
    public function hasAlias() : string
    {
        if (array_key_exists("aliasid", $this->attribs)) {
            return Identifier::sftDecode($this->attribs["aliasid"]);
        }
        return "";
    }

    /**
     * 保持している値を連想配列で取得する
     * @return array フィールド名をキーとした連想配列を返す。何も保持していない場合は空の配列を返す
     */
    public function toArray() : array
    {
        $data = array();
        foreach($this->attribs as $key => $value) {
            if ($key == "aliasid") {
                $data["AliasId"] = $value;
                continue;
            }
            $data[$key] = $value;
        }
        return $data;
    }
}
