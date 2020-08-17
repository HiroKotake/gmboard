<?php
namespace teleios\gmboard\dao;

use teleios\utils\Identifier;
use teleios\gmboard\dao\CiSessions;

/**
 * DBレコード保持クラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category dao
 * @package teleios\gmboard
 */
class Bean
{
    private $attribs;
    private $primaryId;
    private $idType;
    private $idTypeCode;

    public function __construct(string $idType = "")
    {
        $this->attribs = array();
        $this->idTypeCode = ID_TYPE_CODE_LIST[$idType];
        $this->idType = mb_strtolower($idType);
    }

    public function __destruct()
    {
        $aliasId = $this->hasAlias();
        if (!empty($aliasId) && $this->idTypeCode < 50) {
            $daoCiSessions = new CiSessions();
            $aliasList = array();
            if ($daoCiSessions->isSet(SESSION_LIST_ALIAS)) {
                $aliasList = unserialize($daoCiSessions->getSessionData(SESSION_LIST_ALIAS));
            }
            $aliasStr = $aliasId . "_" . $this->idTypeCode . "_" . $this->primaryId;
            if (!in_array($aliasStr, $aliasList)) {
                $aliasList[] = $aliasStr;
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
        elseif ($name == $this->idType) {
            $this->primaryId = $value;
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
        $list = array_keys($this->attribs);
        if (in_array("aliasid")) {
            unset($list["aliasid"]);
            $list["AliasId"];
        }
        return $list;
    }

    /**
     * 保持データを初期化する
     */
    public function reset() : void
    {
        $this->attribs = array();
        $this->primaryId = null;
    }

    /**
     * テーブルのプライマリーキーを設定する
     * @param string $idType プリマリーキー情報を設定するために、constants.phpで指定されている"ID_TYPE_<テーブル名>"を指定
     */
    public function setPrimaryId(string $idType = "") : void
    {
        $this->idTypeCode = ID_TYPE_CODE_LIST[$idType];
        $this->idType = mb_strtolower($idType);
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

    /**
     * プライマリーIDを取得する
     * @return int プライマリーID
     */
    public function getPrimaryId() : int
    {
        return $this->primaryId;
    }
}
