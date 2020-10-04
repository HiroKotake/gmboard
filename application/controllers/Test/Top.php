<?php
use teleios\utils\LangUtils;

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

    /**
     * 言語ユーティリティ動作チェック
     * @return [type] [description]
     */
    public function lang()
    {
        $langUtil = new LangUtils();
        $msgs = $langUtil->getMessageList();
        echo 'message code:<br />';
        foreach ($msgs['code'] as $key => $val) {
            echo $key . '&nbsp->&nbsp' . $val . '<br />';
        }
        echo '<br />';
        echo 'japanese:<br />';
        foreach ($msgs['msg'] as $key => $val) {
            echo $key . '&nbsp->&nbsp' . $val . '<br />';
        }
        echo '<br />';
        $reps = ['member' => 'hogetta', 'group' => 'testGroup'];
        echo $langUtil->getMessage('join', $reps) . '<br />';
        echo '<br />';
        $reps = ['member' => 'dissmiss', 'group' => 'testGroup'];
        echo $langUtil->getMessageByCode(2, $reps) . '<br />';
        echo '<hr />';
        echo '<a href="../top">戻る</a>';
    }

    /**
     * 言語ユーティリティ動作チェック
     * @return [type] [description]
     */
    public function langCache()
    {
        $langUtil = new LangUtils("japanese", LangUtils::MODE_REDIS);
        $msgs = $langUtil->getAllFromCache();
        echo 'message code:<br />';
        foreach ($msgs['code'] as $key => $val) {
            echo $key . '&nbsp->&nbsp' . $val . '<br />';
        }
        echo '<br />';
        echo 'japanese:<br />';
        foreach ($msgs['msg'] as $key => $val) {
            echo $key . '&nbsp->&nbsp' . $val . '<br />';
        }
        echo '<br />';
        $reps = ['member' => 'hogetta', 'group' => 'testGroup'];
        echo $langUtil->getMessage('join', $reps) . '<br />';
        echo '<br />';
        $reps = ['member' => 'dissmiss', 'group' => 'testGroup'];
        echo $langUtil->getMessageByCode(2, $reps) . '<br />';
        echo '<hr />';
        echo '<a href="../top">戻る</a>';
    }
}
