            <!-- 登録ゲーム -->
            <fieldset class="smGrouping">
                <legend>ゲームリスト表示</legend>
                <!-- ゲームリスト -->
{if count($GameInfos) > 0}
                <ul name="GameList" id="ulGameList">
    {foreach from=$GameInfos item=Game name=GameInfoList}
                    <li><button class="perple_40x280" onclick='jmpGame("{$Game.AliasId}")'>{$Game.Name}</button></li>
    {/foreach}
                </ul>
{else}
                <p>登録されているゲームはありません。</p>
{/if}
	            <br /><button id="BtnAddGame" class="btnBlue_32x180">ゲーム追加</button>
            </fieldset>
