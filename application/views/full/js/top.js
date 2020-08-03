    <script>
        {literal}function inputCheck() {
            var nname = $("#nname").val();
            var idml = $("#idml").val();
            var pass = $("#pass").val();
            var repass = $("#repass").val();
            var errMsg = "";
            if (nname.length <= 0) {
                errMsg += "ニックネームが入力されていません。\r\n";
            }
            if (idml.length <= 0) {
                errMsg += "メールアドレスが入力されていません。\r\n";
            }
            if (pass.length <= 0 || repass.length <= 0) {
                errMsg += "パスワードが入力されていません。\r\n";
            }
            if (pass != repass) {
                errMsg += "パスワードとパスワード(確認)が一致していません。\r\n";
            }
            return errMsg;
        }

        $(function(){
            $("#TopRegist").dialog({
                autoOpen: false,
                width: 550,
                modal: true,
                buttons: [
                    {
                        text: "キャンセル",
                        click: function(){
                            $("#TopRegist").dialog("close");
                        }
                    },
                    {
                        text: "申請",
                        click: function(){
                            var errMsg = inputCheck();
                            if (errMsg.length > 0) {
                                alert(errMsg);
                            } else {
                                var data = $("#dregist").serialize();
                                $.post("{/literal}{$Regist}{literal}", data).done();
                            }
                        }
                    }
                ]
            });
            $("#linkRegist").click(function(){
                $("#TopRegist").dialog("open");
            });
        }){/literal}
    </script>
