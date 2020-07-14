        <!-- 登録グループ -->
            <div name="AreaGroupList">グループリスト表示<br />
                <ul name="GroupList" id="ulGroupList" style="list-style-type: none">
                {if count($GroupInfos) > 0}
                <!-- グループリスト -->
                    {foreach from=$GroupInfos item=Group name=GroupList}
                    <li>[{$Group.GameName}]&nbsp;{$Group.GroupName}</li>
                    {/foreach}
                {else}
                <li>登録されているグループはありません。</li>
                {/if}
                </ul>
                <br /><button id="BtnAddGroup">グループ追加</button>
            </div>
