<?php

use teleios\utils\Identifier;

class TestIdentifier extends MY_Controller
{
    public function index()
    {
        $ids = array();
        for ($i = 0; $i < 50; $i++) {
            $id = Identifier::getRandomId();
            $encid = Identifier::sftEncode($id);
            $decid = Identifier::sftDecode($encid);
            $ids[] = array(
                        "Origin" => $id,
                        "Outer" => $encid,
                        "Decoded" => $decid,
                        "Check" => ($id == $decid ? "[MATCH]" : "[UNMATCH !!]")
                     );
        }
        $data = array(
            'IDs' => $ids
        );
        $this->smarty->testView('Identifier/ids', $data);
    }

    public function rcode()
    {
        $length = $this->input->get("len");
        echo 'ランダムコード';
        echo '<hr>';
        $code = Identifier::buildRandomCode($length);
        echo $code . '&nbsp;(' . mb_strlen($code) . ')';
        echo '<hr>';
        echo '<a href="../top">戻る</a>';
    }
}
