<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiViewBankCardInfo
 *
 * @author shuming
 */
class ApiViewBankCardInfo extends EApiViewService {

    private $id;
    private $userId;
    private $card;
    private $userMgr;

    public function __construct($id, $userId) {
        parent::__construct();
        $this->id = $id;
        $this->userId = $userId;
        $this->userMgr = new UserManager();
        $this->card = null;
    }

    protected function createOutput() {
        $this->output = array(
            'status' => self::RESPONSE_OK,
            'errorCode' => 0,
            'errorMsg' => 'success',
            'results' => $this->results,
        );
    }

    protected function loadData() {
        $this->loadCard();
    }

    private function loadCard() {
        $card = $this->userMgr->loadCardByUserIdAndId($this->userId, $this->id);
        if (isset($card)) {
            $this->setCard($card);
        }
        $this->results->card = $this->card;
    }

    private function setCard(DoctorBankCard $card) {
        $data = new stdClass();
        $data->id = $card->id;
        $data->name = $card->name;
        $data->cardNo = $card->card_no;
        $data->stateId = $card->state_id;
        $data->stateName = $card->state_name;
        $data->cityId = $card->city_id;
        $data->cityName = $card->city_name;
        $data->bank = $card->bank;
        $data->subbranch = $card->subbranch;
        $data->isDefault = $card->is_default;
        $this->card = $data;
    }

}
