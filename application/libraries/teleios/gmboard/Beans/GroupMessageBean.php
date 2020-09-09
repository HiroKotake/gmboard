<?php
namespace teleios\gmboard\Beans;

/**
 * GroupMessageBean
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category bean
 * @package teleios\gmboard
 */
class GroupMessageBean extends BaseBean
{
    private $records = array(
        "GBoardMsgId"   => null,    // 管理ID
        "AliasId"       => null,    // IDエイリアス
        "UserId"        => null,    // ユーザID
        "GamePlayerId"  => null,    // ゲームユーザ管理ID
        "GameNickname"  => null,    // 送信者ニックネーム
        "ParentMsgId"   => null,    // 基底ID
        "Idiom"         => null,    // 慣用句コード
        "Message"       => null,    // メッセージテキスト
        "Images"        => null,    // イメージのファイル名のJSON配列
        "Data"          => null,    // 各種データ保持用
        "Showable"      => null,    // メッセージ表示フラグ(0:無効, 1:有効)
        "CreateDate"    => null,    // レコード登録日
        "UpdateDate"    => null,    // レコード更新日
        "DeleteDate"    => null,    // レコード無効日
        "DeleteFlag"    => null     // レコード無効フラグ(0:有効, 1:無効)
    );
    private $recordAttrs = array(
        "GBoardMsgId"   => "integer",   // 管理ID
        "AliasId"       => "string",    // IDエイリアス
        "UserId"        => "integer",   // ユーザID
        "GamePlayerId"  => "integer",   // ゲームユーザ管理ID
        "GameNickname"  => "string",    // 送信者ニックネーム
        "ParentMsgId"   => "integer",   // 基底ID
        "Idiom"         => "integer",   // 慣用句コード
        "Message"       => "string",    // メッセージテキスト
        "Images"        => "string",    // イメージのファイル名のJSON配列
        "Data"          => "string",    // 各種データ保持用
        "Showable"      => "integer",   // メッセージ表示フラグ(0:無効, 1:有効)
        "CreateDate"    => "string",    // レコード登録日
        "UpdateDate"    => "string",    // レコード更新日
        "DeleteDate"    => "string",    // レコード無効日
        "DeleteFlag"    => "integer"    // レコード無効フラグ(0:有効, 1:無効)
    );

    public function __construct()
    {
        $this->property = $this->records;
        $this->attribute = $this->recordAttrs;
    }
}
