    <div id="DialogAddGame">
        <form action="AttachGame" method="post">
            <ul style="list-style-type: none">
            <!-- ゲーム追加 -->
            <!-- カテゴリ -->
                <li>
                    <select id="GameGenre" name="Genre">
{foreach from=$GameGenre key=GenreId item=Genre}
                        <option value="{$GenreId}">{$Genre}</option>
{/foreach}
                </select>
            <!-- ゲーム -->
                    <select id="TargetGame" name="Target">
{foreach from=$GameList[1] item=GameInfo}
    {if $GameInfo.Joined == 0}
                        <option value="{$GameInfo.Ub}">{$GameInfo.Name}</option>
    {/if}
{/foreach}
                    </select>
                </li>
            <!-- ゲーム側のプレイヤーID -->
                <li>ゲーム側のプレイヤーID:<input type="text" id="TargePID" name="gpid" /></li>
            <!-- ゲーム側のニックネーム -->
                <li>ゲーム側のニックネーム:<input type="text" id="TargetNickname" name="gnn" /></li>
            </ul>
        </form>
    </div>
