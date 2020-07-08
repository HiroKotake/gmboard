<?php

/********************************************************
 * 掲示板関連    lib: GroupMessage
 ********************************************************/
/**
 * テスト環境向グループメッセージコントローラークラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class TestGroupMessage extends MY_Controller
{
    /**
     * グループ掲示板へメッセージ追加するフォームを表示
     * @return [type] [description]
     */
    public function formGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formGroupMessage', $data);
    }

    /**
     * グループ掲示板へメッセージ追加
     * @return [type] [description]
     */
    public function addGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('addGroupMessage', $data);
    }

    /**
     * グループ掲示板を表示
     * @return [type] [description]
     */
    public function showGroupMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showGroupMessage', $data);
    }
}
