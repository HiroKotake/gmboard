<?php

/********************************************************
 * 掲示板関連    lib: GroupMessage
 ********************************************************/
class TestGroupMessage extends MY_Controller
{
    // グループ掲示板へメッセージ追加
    public function formGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formGroupMessage', $data);
    }
    public function addGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('addGroupMessage', $data);
    }
    // グループ掲示板表示
    public function showGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showGroupMessage', $data);
    }
}
