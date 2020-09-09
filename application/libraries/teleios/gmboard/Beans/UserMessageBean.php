<?php
namespace teleios\gmboard\Beans;

/**
 * UserMessageBean
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category bean
 * @package teleios\gmboard
 */
class UserMessageBean extends BaseBean
{
    private $records = array(
        "UBoardMsgId"   => null,    // メッセージ管理ID
        "AliasId"       => null,    // IDエイリアス
        "FromUserId"    => null,    // 送信者ユーザID
        "FromUserName"  => null,    // 送信者ニックネーム
        "FromGroupId"   => null,    // 送信グループID
        "FromGroupName" => null,    // 送信グループネーム
        "Idiom"         => null,    // 慣用句コード
        "Message"       => null,    // メッセージテキスト
        "Images"        => null,    // イメージのファイル名のJSON配列
        "Data"          => null,    // 各種データ
        "AlreadyRead"   => null,    // メッセージ既読フラグ(0:無効, 1:有効)
        "NoRePlay"      => null,    // 返信不要フラグ(0:通常, 1:不要)
        "CreateDate"    => null,    // レコード登録日
        "UpdateDate"    => null,    // レコード更新日
        "DeleteDate"    => null,    // レコード無効日
        "DeleteFlag"    => null     // レコード無効フラグ(0:有効, 1:無効)
    );
    private $recordAttrs = array(
        "UBoardMsgId"   => "integer",   // メッセージ管理ID
        "AliasId"       => "string",    // IDエイリアス
        "FromUserId"    => "integer",   // 送信者ユーザID
        "FromUserName"  => "string",    // 送信者ニックネーム
        "FromGroupId"   => "integer",   // 送信グループID
        "FromGroupName" => "string",    // 送信グループネーム
        "Idiom"         => "integer",   // 慣用句コード
        "Message"       => "string",    // メッセージテキスト
        "Images"        => "string",    // イメージのファイル名のJSON配列
        "Data"          => "string",    // 各種データ
        "AlreadyRead"   => "integer",   // メッセージ既読フラグ(0:無効, 1:有効)
        "NoRePlay"      => "integer",   // 返信不要フラグ(0:通常, 1:不要)
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
