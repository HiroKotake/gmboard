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
            <form action="GateCheck/login" method="post">
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
            <form action="GateCheck/regist" method="post">
                <ul>
                    <li>Mail : <input type="mail" name="mail" />
                    <br />メールアドレスがログインIDになる</li>
                    <li>Password : <input type="password" name="pwd" /></li>
                    <li>Password(Re) : <input type="password" name="rpd" /></li>
                    <li><input type="submit" /></li>
                </ul>
            </form>
        </div>
    </div>
    <!-- footer -->
    </body>
</html>
