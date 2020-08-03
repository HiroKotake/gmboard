    <div id="DialogAddGroup">
        <form action="AttachGroup" method="post">
            <ul style="list-style-type: none">
            <!-- グループ追加 -->
            <!-- 対象のゲーム -->
            <!-- カテゴリ -->
                <li>
                    <select id="G_GameGenre" name="GGenre">
{foreach from=$GroupGenre key=GenreId item=Genre}
                        <option value="{$GenreId}">{$Genre}</option>
{/foreach}
                    </select>
            <!-- ゲーム -->
                    <select id="G_TargetGame" name="GTarget">
{foreach from=$GroupGame[1] item=GameInfo}
                        <option value="{$GameInfo.Ub}">{$GameInfo.Name}</option>
{/foreach}
                    </select>
                </li>
            <!-- 名称検索 -->
                <li>グループ名：<input type="text" id="TargetGrouName" name="tgn" /></li>
            </ul>
        </form>
    </div>
