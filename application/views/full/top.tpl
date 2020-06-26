<html>
<head>
</head>
<body>
    <!-- Header -->
    <!-- Main -->
    <div>
        <div>
            NOTICE AREA<br />
            <a href="Index/showNotices?page=2">もっと見る</a>
        </div>
        <div>
            <form action="MyPage/login" method="post">
                <ul>
                    <li>Login ID : <input type="text" name="lid" />
                    <br />(ログインIDはメールアドレス)</li>
                    <li>Password : <input type="password" name="pwd" /></li>
                    <li><input type="submit" /></li>
                </ul>
            </form>
            <a href="index/regist">新規登録</a>
        </div>
        <div id="TopRegist">
            {if isset($message)}
                {$message}
            {/if}
            <form action="MyPage/regist" method="post">
                <ul>
                    <li>ニックネーム : <input type="text" name="nickname" /></li>
                    <li>Mail : <input type="mail" name="mail" />
                    <br />メールアドレスがログインIDになる</li>
                    <li>Password : <input type="password" name="pwd" /></li>
                    <li>Password(Re) : <input type="password" name="rpd" /></li>
                </ul>
                <input type="submit" />
            </form>
        </div>
    </div>
    <!-- footer -->
    </body>
</html>
