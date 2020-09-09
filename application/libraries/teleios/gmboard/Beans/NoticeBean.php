<?php
namespace teleios\gmboard\Beans;

/**
 * NoticeBean
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category bean
 * @package teleios\gmboard
 */
class NoticeBean extends BaseBean
{
    private $records = array(
        "NoticeId"          => null,    // 管理ID
        "AliasId"           => null,    // IDエイリアス
        "Target"            => null,    // 表示タイプ(0:全体告知, 1:メンバー告知, 10:グループ管理者向け告知)
        "Priority"          => null,    // 優先度
        "Message"           => null,    // メッセージテキスト
        "ShowStartDateTime" => null,    // 表示開始日時
        "ShowEndDateTime"   => null,    // 表示終了日時
        "Showable"          => null,    // メッセージ表示フラグ(0:無効, 1:有効)
        "CreateDate"        => null,    // レコード登録日
        "UpdateDate"        => null,    // レコード更新日
        "DeleteDate"        => null,    // レコード無効日
        "DeleteFlag"        => null     // レコード無効フラグ(0:有効, 1:無効)
    );
    private $recordAttrs = array(
        "NoticeId"          => "integer",   // 管理ID
        "AliasId"           => "string",    // IDエイリアス
        "Target"            => "integer",   // 表示タイプ(0:全体告知, 1:メンバー告知, 10:グループ管理者向け告知)
        "Priority"          => "integer",   // 優先度
        "Message"           => "string",    // メッセージテキスト
        "ShowStartDateTime" => "string",    // 表示開始日時
        "ShowEndDateTime"   => "string",    // 表示終了日時
        "Showable"          => "integer",   // メッセージ表示フラグ(0:無効, 1:有効)
        "CreateDate"        => "string",    // レコード登録日
        "UpdateDate"        => "string",    // レコード更新日
        "DeleteDate"        => "string",    // レコード無効日
        "DeleteFlag"        => "integer"    // レコード無効フラグ(0:有効, 1:無効)
    );

    public function __construct()
    {
        $this->property = $this->records;
        $this->attribute = $this->recordAttrs;
    }
}
