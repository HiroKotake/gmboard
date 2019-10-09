<?php

/********************************************************
 * グループメンバー関連   lib: GroupMember
 ********************************************************/
class TestGroupMember extends MY_Controller
{
    // グループメンバー追加１
    public function formAddGroupMember()
    {
        $groupId = $this->input->get('GPID');
        $data = array(
            'Message' => '',
            'GroupId' => $groupId,
            'RegistedMembers' => null,
            'BookingMembers' => null
        );
        $this->load->model('test/GroupMember', 'testGroupMember');
        $members = $this->testGroupMember->formAddGroupMember((int)$groupId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formAddGroupMember', $data);
    }
    public function addGroupMember()
    {
        $playerId       = $this->input->post('GPID');
        $authCode       = $this->input->post('ACD');
        $gameNickName   = $this->input->post('GNIN');
        $groupId        = $this->input->post('GID');
        // データ登録
        $this->load->model('test/GroupMember', 'testGroupMember');
        $result = $this->testGroupMember->addGroupMember((int)$groupId, $playerId, $authCode, $gameNickName);
        $data = array(
            'Message' => '',
            'RegistedMembers' => $result['RegistedMembers'],
            'BookingMembers' => $result['BookingMembers'],
            'BookingMember' => $result['BookingMember']
        );
        $this->smarty->testView('addGroupMember', $data);
    }
    // グループメンバー追加２
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
        $this->load->model('test/GroupMember', 'testGroupMember');
        $members = $this->testGroupMember->formSearchGroupMember((int)$groupId);
        $data['GroupInfo'] = $members['GroupInfo'];
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formSearchGroupMember', $data);
    }
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
        $this->load->model('test/GroupMember', 'testGroupMember');
        $result = $this->testGroupMember->resultSearchGroupMember((int)$groupId, $playerId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formResultSearchGroupMember', $data);
    }
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
        $this->load->model('test/GroupMember', 'testGroupMember');
        // メンバーを追加し、追加後のグループの状態情報を取得
        $members = $this->testGroupMember->addSearchGroupMember((int)$groupId, (int)$gameId, $playerId);
        $data['RegistedMembers'] = $members['RegistedMembers'];
        $data['BookingMembers'] = $members['BookingMembers'];
        $this->smarty->testView('formSearchGroupMember', $data);
    }
    // グループメンバー一覧表示
    public function listGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('listGroupMember', $data);
    }
    // グループメンバー除名
    public function formDelGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('formDelGroupMember', $data);
    }
    public function delGroupMember()
    {
        $data = array(
            'Message' => ''
        );
        $this->smarty->testView('delGroupMember', $data);
    }
}
