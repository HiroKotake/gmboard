<?php

/********************************************************
 * 掲示板関連    lib: UserMessage
 ********************************************************/
/**
 * テスト環境向ユーザメッセージコントローラークラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class TestUserMessage extends MY_Controller
{
    /**
     * ユーザ掲示板へメッセージ追加するフォームを表示
     * @return [type] [description]
     */
    public function formUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formUserMessage', $data);
    }

    /**
     * ユーザ掲示板へメッセージ追加
     * @return [type] [description]
     */
    public function addUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showUserMessage', $data);
    }

    /**
     * ユーザ掲示板表示
     * @return [type] [description]
     */
    public function showUserMessage()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('showUserMessage', $data);
    }
}
