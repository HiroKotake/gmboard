            <!-- 登録グループ -->
            <fieldset class="smGrouping">
                <legend>グループリスト表示</legend>
                    <!-- グループリスト -->
{if count($GroupInfos) > 0}
                <ul name="GroupList" id="ulGroupList">
    {foreach from=$GroupInfos item=Group name=GroupList}
                    <li><button class="perple_50x280" onclick='jmpGroup("{$Group.GameId}", "{$Group.GroupId}")'>[{$Group.GameName}]<br />{$Group.GroupName}</button></li>
    {/foreach}
                </ul>
{else}
                    <p>登録されているグループはありません。</p>
{/if}
                <br /><button id="BtnAddGroup" class="btnBlue_32x180">グループ追加</button>
            </fieldset>
