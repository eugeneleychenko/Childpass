<?php

class WebUser extends CWebUser {
    private $_model = null;

//    function getRole() {
//        if($user = $this->getModel()){
//            return $user->role_id;
//        }
//
//        return false;
//    }

    private function getModel() {
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id/*, array('select' => 'role_id')*/);
        }
        return $this->_model;
    }

    function getName() {
        if($user = $this->getModel()){
            return $user->name;
        }

        return false;
    }

    function getIsActive() {
        if($user = $this->getModel()){
            return $user->is_active;
        }

        return false;
    }
}