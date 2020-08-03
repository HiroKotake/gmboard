            <!-- 登録グループ -->
            <fieldset class="smGrouping">
                <legend>グループリスト表示</legend>
                    <!-- グループリスト -->
{if count($GroupInfos) > 0}
                <ul name="GroupList" id="ulGroupList">
    {foreach from=$GroupInfos item=Group name=GroupList}
                    <li><a href="?gameId={$Group.GameId}&groupId={$Group.AliasId}">[{$Group.GameName}]<br />{$Group.GroupName}</a></li>
    {/foreach}
                </ul>
{else}
                    <p>登録されているグループはありません。</p>
{/if}
                <br /><button id="BtnAddGroup" class="btnBlue_32x180">グループ追加</button>
            </fieldset>
