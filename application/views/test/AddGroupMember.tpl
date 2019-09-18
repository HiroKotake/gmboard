    <h3>登録済みメンバー</h3>
    {if count($RegistedMembers) > 0}
    <table>
    </table>
    {else}
    登録済みメンバーがいません。<br />
    {/if}
    <hr />
    <h3>予約済みメンバー</h3>
    {if count($BookingMembers) > 0}
    <table>
    </table>
    {else}
    予約済みメンバーがいません。<br />
    {/if}
    <hr />
{if mb_strlen($Message)}
{$Message}<br />
{else}
    <h3>メンバー予約</h3>
    ゲーム側ユーザID：{$BookingMember.PlayerId}<br />
    認証確認用コード：{$BookingMember.AuthCode}<br />
    ゲーム側ニックネーム：{$BookingMember.GameNickName}<br />
    登録日：{$BookingMember.CreateDate}<br />
{/if}
<a href="./">戻る</a>
