<?php

class Top extends MY_Controller
{
    /**
     * テストトップ画面
     * @return [type] [description]
     */
    public function index()
    {
        $this->smarty->testView('top');
    }
}
