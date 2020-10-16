<?php

use teleios\gmboard\libs\admin\CtrlRecords;
use teleios\gmboard\dao\CiSessions;
/**
 * テスト環境向データ生成コントローラークラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class TestData extends MY_Controller
{
    private function subBuildTables() : string
    {
        $ctrlRecords = new CtrlRecords();
        $data = $ctrlRecords->makeTables();
        $message = "";
        foreach($data as $tableName => $result) {
            $message .= $tableName . ":" . ($result ? "成功" : "失敗") . "\r\n";
        }
        return $message;
    }
    /**
     * テーブルを生成
     */
    public function buildTables() : void
    {
        $message = $this->subBuildTables();
        echo json_encode(["message" => $message]);
    }

    private function subDestoryTables() : string
    {
        $ctrlRecords = new CtrlRecords();
        $data = $ctrlRecords->clearDB();
        $message = "";
        foreach($data as $tableName => $result) {
            $message .= $tableName . ":" . ($result ? "成功" : "失敗") . "\r\n";
        }
        return $message;
    }

    /**
     * 全テーブルを削除
     */
    public function destoryTables() : void
    {
        $message = $this->subDestoryTables();
        echo json_encode(["message" => $message]);
    }

    /**
     * 操作対象のカテゴリを抽出
     * @param  string $flags [description]
     * @return array         [description]
     */
    private function checkTarget(string $flags) : array
    {
        $list = array(
            false,  //'GameInfo'
            false,  //'User',
            false,  //'PlayerIndex',
            false,  //'Group',
            false,  //'GroupMember',
            false,  //'RegistBooking',
            false,  //'GroupNotices',
        );
        $index = count($list) - 1;
        foreach (str_split($flags) as $flg) {
            if ($flg == 1) {
                $list[$index] = true;
            }
            $index -= 1;
        }
        return $list;
    }

    /**
     * テストデータ生成
     */
    public function buildData()
    {
        $target = $this->input->post("target");
        $list = $this->checkTarget($target);
        $ctrRecode = new CtrlRecords();
        $message = "\r\n";
        // ゲーム情報登録 (GameInfo, Groups_xxxxxxxx, RegistBooking_xxxxxxxx)
        if ($list[0]) {
            $ctrRecode->makeGames();
            $message .= "ゲーム情報登録\r\n";
        }
        // ユーザ登録 (Users, UserInfos, UBoard_xxxxxxxxxxxx)
        if ($list[1]) {
            $ctrRecode->makeUsers();
            $message .= "ユーザ登録\r\n";
        }
        // ユーザのゲーム登録 (PlayerIndex)
        if ($list[2]) {
            $ctrRecode->makePlayerIndex();
            $message .= "ユーザのゲーム登録\r\n";
        }
        // グループ登録 (Groups_xxxxxxxx, GBoard_xxxxxxxx_xxxxxxxxxx,  GNotice_xxxxxxxx_xxxxxxxxxx)
        if ($list[3]) {
            $ctrRecode->makeGroups();
            $message .= "グループ登録\r\n";
        }
        // グループメンバー登録 (GamePlayers_xxxxxxxx, GBoard_xxxxxxxx_xxxxxxxxxx)
        if ($list[4]) {
            $ctrRecode->makeGroupMember();
            $message .= "グループメンバー登録\r\n";
        }
        // グループ予約メンバー登録 (RegistBooking_xxxxxxxx)
        if ($list[5]) {
            $ctrRecode->makeRegistBooking();
            $message .= "グループ予約メンバー登録\r\n";
        }
        // ゲーム告知登録 (GNotices)
        if ($list[6]) {
            $ctrRecode->makeGNotices();
            $msg[6] = "グループ告知登録\r\n";
        }
        $data = array(
            "message" => 'テストデータを生成しました:' . $message
        );
        echo json_encode($data);
    }

    /**
     * データを削除
     * @return [type] [description]
     */
    public function removeData()
    {
        $target = $this->input->post("target");
        $list = $this->checkTarget($target);
        $ctrRecode = new CtrlRecords();
        $message = "\r\n";
        $msg = array();
        // ゲーム告知登録 (GNotices)
        if ($list[6]) {
            $ctrRecode->removeGNotices();
            $msg[6] = "グループ告知削除\r\n";
        }
        // グループ予約メンバー (RegistBooking_xxxxxxxx)
        if ($list[5]) {
            $msg[5] = "グループ予約メンバー削除\r\n";
        }
        // グループメンバー (GamePlayers_xxxxxxxx, GBoard_xxxxxxxx_xxxxxxxxxx)
        if ($list[4]) {
            $ctrRecode->removeGroupMember();
            $msg[4] = "グループメンバー削除\r\n";
        }
        // グループ (Groups_xxxxxxxx, RegistBooking_xxxxxxxx, GBoard_xxxxxxxx_xxxxxxxxxx)
        if ($list[3]) {
            $ctrRecode->removeGroups();
            $msg[3] = "グループ削除\r\n";
        }
        // ユーザのゲーム (PlayerIndex)
        if ($list[2]) {
            $ctrRecode->removePlayerIndex();
            $msg[2] = "ユーザのゲーム削除\r\n";
        }
        // ユーザ (Users, UserInfos, UBoard_xxxxxxxxxxxx)
        if ($list[1]) {
            $ctrRecode->removeUsers();
            $msg[1] = "ユーザ削除\r\n";
        }
        // ゲーム情報削除 (GameInfo)
        if ($list[0]) {
            $ctrRecode->removeGames();
            $msg[0] = "ゲーム情報削除\r\n";
        }
        foreach ($msg as $m) {
            if (!empty($m)) {
                $message .= $m;
            }
        }
        $data = array(
            "message" => 'テストデータを削除しました:' . $message
        );
        echo json_encode($data);
    }

    public function dataReset() : void
    {
        $message = $this->subDestoryTables();
        $message = $this->subBuildTables();
        $ctrRecode = new CtrlRecords();
        $ctrRecode->makeGames();
        $ctrRecode->makeUsers();
        $ctrRecode->makePlayerIndex();
        $ctrRecode->makeGroups();
        $ctrRecode->makeGroupMember();
        $ctrRecode->makeRegistBooking();
        $ctrRecode->makeGNotices();
        $daoCiSessions = new CiSessions();
        $daoCiSessions->flushAll();
        $data = array(
            "message" => '環境をリセットしました。'
        );
        echo json_encode($data);
    }
}
