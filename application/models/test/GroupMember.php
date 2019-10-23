<?php

class GroupMember
{
    private $cIns = null;

    public function __construct()
    {
        $this->cIns =& get_instance();
        $this->cIns->load->model('dao/Groups', 'daoGroups');
        $this->cIns->load->model('dao/RegistBooking', 'daoRegistBooking');
        $this->cIns->load->model('dao/GamePlayers', 'daoGamePlayers');
    }

    public function getRegistMember(int $groupId) : array
    {
        return $this->cIns->daoRegistBooking->getByGroupId($groupId);

    }
    public function getBookingMember(int $groupId) : array
    {
        return $this->cIns->daoGamePlayers->getByGroupId($groupId);
    }

    private function getRagistedBookingMember(int $groupId) : array
    {
        $data = array(
            'RegistedMembers' => null,
            'BookingMembers' => null
        );
        // 現在予約中のメンバーリスト取得
        $data['BookingMembers'] = $this->getBookingMember($groupId);
        // 登録済みのメンバーリスト取得
        $data['RegistedMembers'] = $this->getRegistMember($groupId);
        return $data;
    }
    public function formAddGroupMember(int $groupId) : array
    {
        return $this->getRagistedBookingMember($groupId);
    }
    public function addGroupMember(int $groupId, string $playerId, string $authCoce, string $gameNickName) : array
    {
        $data = $this->getRagistedBookingMember($groupId);
        // 登録
        $newId = $this->cIns->daoRegistBooking->addNewBooking($groupId, $playerId, $gameNickName, $authCoce);
        // データ再取得
        $bookingMember = $this->cIns->daoRegistBooking->getByRegistBookingId($newId);
        $data['BookingMember'] = array();
        $data['Message'] = '登録に失敗しました。';
        if (count($bookingMember) > 0) {
            $data['Message'] = '';
            $data['BookingMember'] = $bookingMember[0];
        }
        return $data;
    }

    private function searchRegistBookingPlayer(int $gameId, string $playerId) : array
    {
        $result = $this->cIns->daoGamePlayers->getWithCondition(array('GameId' => $gameId, 'PlayerId' => $playerId));
        if (count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public function formSearchGroupMember(int $groupId) : array
    {
        $data = $this->getRagistedBookingMember($groupId);
        $data['GroupInfo'] = null;
        $groupInfo = $this->cIns->daoGroups->getByGroupId($groupId);
        if (count($groupInfo) > 0) {
            $data['GroupInfo'] = $groupInfo[0];
        }
        return $data;
    }

    public function resultSearchGroupMember(int $groupId, string $playerId) : array
    {
        $data = $this->getRagistedBookingMember($groupId);
        $data['GroupInfo'] = null;
        $groupInfo = $this->cIns->daoGroups->getByGroupId($groupId);
        if (count($groupInfo) > 0) {
            $data['GroupInfo'] = $groupInfo[0];
        }
        $data['PlayerInfo'] = $this->searchRegistBookingPlayer($data['GroupInfo']['GameId'], $playerId);
        return $data;
    }
    public function addSearchGroupMember(int $groupId, int $gameId, string $playerId) : array
    {
        // グループへ編入
        $this->cIns->daoRegistBooking->addNewBooking($groupId, $gameId, $playerId);
        // 新データ取得
        return $this->formAddGroupMember($groupId);
    }

    public function listGroupMember(int $gameId, int $groupId) : array
    {
        $data = array();
        // グループ情報
        $data['GroupInfo'] = $this->cIns->daoGroups->getByGroupId($gameId, $groupId);
        // グループメンバー取得
        $data['MemberList'] = $this->cIns->daoGamePlayers->getByGroupId($gameId, $groupId);
        // 予約メンバー取得
        $data['BookingList'] = $this->cIns->daoRegistBooking->getByGroupId($gameId, $groupId);
        return $data;
    }
/*
    public function formDelGroupMember()
    {
    }
    public function delGroupMember()
    {
    }
*/
}
