<?php

/********************************************************
 * 掲示板関連    lib: UserMessage
 ********************************************************/
class TestUserMessage extends MY_Controller
{
    // ユーザ掲示板へメッセージ追加
    public function formUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formUserMessage', $data);
    }
    public function addUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showUserMessage', $data);
    }
    // ユーザ掲示板表示
    public function showUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showUserMessage', $data);
    }
}
