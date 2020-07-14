        <!-- 登録ゲーム -->
            <div name="AreaGameList">ゲームリスト表示<br />
                <ul name="GameList" id="ulGameList" style="list-style-type: none">
                {if count($GameInfos) > 0}
                <!-- ゲームリスト -->
                    {foreach from=$GameInfos item=Game name=GameInfoList}
                    <li>{$Game.Name}</li>
                    {/foreach}
                {else}
                    <li>登録されているゲームはありません。</li>
                {/if}
                </ul>
                <br /><button id="BtnAddGame">ゲーム追加</button>
            </div>
