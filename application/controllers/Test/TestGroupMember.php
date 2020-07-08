<?php

use teleios\gmboard\libs\test\GroupMember;

/********************************************************
 * グループメンバー関連   lib: GroupMember
 ********************************************************/
/**
 * テスト環境向グループメンバーコントローラークラス
 *
 * @access public
 * @author Takahiro Kotake <tkotake@teleios.jp>
 * @copyright Teleios All Rights Reserved
 * @category controller
 * @package teleios\gmboard
 */
class TestGroupMember extends MY_Controller
{
    /**
     * グループメンバー追加のフォームを表示
     * [formAddGroupMember description]
     * @return [type] [description]
     */
    public function formAddGroupMember()
    {
        $groupId = $this->input->get('GPID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'RegistedMembers' => null,
            'BookingMembers' => null
        );
        $testGroupMember = new GroupMember();
        $members = $testGroupMember->formAddGroupMember((int)$groupId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('GroupMember/formAddGroupMember', $data);
    }

    /**
     * グループメンバー追加
     */
    public function addGroupMember()
    {
        $playerId       = $this->input->post('GPID');
        $authCode       = $this->input->post('ACD');
        $gameNickName   = $this->input->post('GNIN');
        $groupId        = $this->input->post('GID');
        // データ登録
        $testGroupMember = new GroupMember();
        $result = $testGroupMember->addGroupMember((int)$groupId, $playerId, $authCode, $gameNickName);
        $data = array(
            'Message' => '',
            'RegistedMembers' => $result['RegistedMembers'],
            'BookingMembers' => $result['BookingMembers'],
            'BookingMember' => $result['BookingMember']
        );
        $this->smarty->testView('GroupMember/addGroupMember', $data);
    }

    /**
     * メンバーを検索してグループメンバー追加するフォームを表示
     * @return [type] [description]
     */
    public function formSearchGroupMember()
    {
        $groupId = $this->input->get('GPID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'GroupInfo' => null,
            'RegistedMembers' => null,
            'BookingMembers' => null
        );
        $testGroupMember = new GroupMember();
        $members = $testGroupMember->formSearchGroupMember((int)$groupId);
        $data['GroupInfo'] = $members['GroupInfo'];
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('GroupMember/formSearchGroupMember', $data);
    }

    /**
     * 検索したグループメンバーを表示
     * @return [type] [description]
     */
    public function resultSearchGroupMember()
    {
        $playerId = $this->input->post('GPID');
        $groupId = $this->input->post('GID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'PlayerInfo' => null,
            'RegistedMembers' => null,
            'BookingMembers' => null,
        );
        $testGroupMember = new GroupMember();
        $members = $testGroupMember->resultSearchGroupMember((int)$groupId, $playerId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('GroupMember/formResultSearchGroupMember', $data);
    }

    /**
     * 検索したグループメンバーを追加
     */
    public function addSearchGroupMember()
    {
        $playerId = $this->input->post('PID');
        $groupId = $this->input->post('GID');
        $gameId = $this->input->post('GMID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'RegistedMembers' => null,
            'BookingMembers' => null,
        );
        $testGroupMember = new GroupMember();
        // メンバーを追加し、追加後のグループの状態情報を取得
        $members = $testGroupMember->addSearchGroupMember((int)$groupId, (int)$gameId, $playerId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('GroupMember/formSearchGroupMember', $data);
    }

    /**
     * グループメンバー一覧表示
     * @return [type] [description]
     */
    public function listGroupMember()
    {
        $groupId = (int)$this->input->get('GID');
        $gameId = (int)$this->input->get('GMID');
        $testGroupMember = new GroupMember();
        $members = $testGroupMember->listGroupMember($gameId, $groupId);
        $data = array(
            'Message' => '',
            'GroupInfo' => $members['GroupInfo'],
            'MemberList' => $members['MemberList'],
            'BookingList' => $members['BookingList']
        );
        $this->smarty->testView('GroupMember/listGroupMember', $data);
    }

    /**
     * グループメンバー除名するフォームを表示
     * @return [type] [description]
     */
    public function formDelGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formDelGroupMember', $data);
    }

    /**
     * グループメンバー除名
     * @return [type] [description]
     */
    public function delGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('delGroupMember', $data);
    }
}
