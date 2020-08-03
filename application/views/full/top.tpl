<!doctype html>
<html>
<head>
	<title>GMBOARD TOP</title>
{include file="./includes/head.tpl"}
{include file="./js/top.js" Regist=$Regist}
</head>
<body>
    <!-- Hide Contents -->
    <div id="TopRegist">
        <form action="{$Regist}" method="post" id="dregist">
            <ul style="list-style-type: none">
                <li>ニックネーム : <input type="text" name="nickname" id="nname" /></li>
                <li>Mail : <input type="mail" name="mail" id="idml" />
                <br />メールアドレスがログインIDになる</li>
                <li>Password : <input type="password" name="pwd" id="pass" /></li>
                <li>Password(Re) : <input type="password" name="rpd" id="repass" /></li>
            </ul>
        </form>
    </div>
    <!-- Header -->
{if isset($message)}
        {$message}
{/if}
    <!-- Main -->
	<div class="TopBase">
        <div class="TopContainer">
            <div class="TopNotice">
                <ul>
					<li>NOTICE AREA</li>
					<li>NOTICE AREA</li>
					<li>NOTICE AREA</li>
					<li>NOTICE AREA</li>
					<li>NOTICE AREA</li>
                </ul>
            </div>
            <div class="TopMoreNotice">
                <a href="Index/showNotices?page=2">もっと見る</a>
            </div>
            <div class="TopLogin">
                <form action="{$Login}" method="post">
                    <ul style="list-style-type: none">
                        <li>Login ID : <input type="text" name="lid" />
                            <br />(ログインIDはメールアドレス)</li>
                        <li>Password : <input type="password" name="pwd" /></li>
                        <li><input type="submit" /></li>
                    </ul>
                </form>
            </div>
            <div class="TopNewRegist">
                <button id="linkRegist" class="bunGley_28x100">新規登録</button>
            </div>
        </div>
    </div>
    <!-- footer -->
    </body>
</html>
