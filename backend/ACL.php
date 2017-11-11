<?php

class ACL
{
    public $username;
    public $autoCheck = false;
    public $mainTitle = 'Consul-Tree';
    private $list = [];
    private $userExists = false;
    private $hashed_password = 0;
    private $options = ['cost' => 12];
    private $userRights;

    function __construct(){
        $list = '';
        require ("../config/auth.php");
        $this->list = $list;
        if (isset($mainTitle)){
            $this->mainTitle = $mainTitle;
        }
        $this->getAuto();
    }

    function getAuto(){
        $list = $this->list;
        foreach ($list as $users){
            if ($users['auto'] || $users['auto'] === 1){
                $this->username = $users['user'];
                $this->autoCheck = true;
                break;
            }
        }
    }

    function setUser($username){
        $this->username = $username;
    }

    function setRights($rights){
        $newRights = "000";
        if ($rights == 'read'){$newRights = "100";};
        if ($rights == 'write'){$newRights = "110";};
        if ($rights == 'full'){$newRights = "111";};
        $this->userRights = $newRights;
    }

    function checkUser ($username){
        $list = $this->list;
        foreach ($list as $users){
            if ($users["user"] === $username){
                $this->userExists = true;
                $this->setUser($username);
            }
        }
        return $this->userExists;
    }

    function checkPass ($password){
        $list = $this->list;
        $options = $this->options;
        $username = $this->username;
        foreach ($list as $users) {
            if ($users["user"] === $username){
                $this->hashed_password = password_hash($users["pass"], PASSWORD_BCRYPT, $options);
            }
        }
        $passCheck = password_verify($password, $this->hashed_password);
        return $passCheck;
    }

    function getRights (){
        $list = $this->list;
        $username = $this->username;
        $rights = [];
        foreach ($list as $users) {
            if ($users["user"] === $username) {
                $rights = $users["rights"];
            }
        }
        $this->setRights($rights);
        return $this->userRights;
    }
}