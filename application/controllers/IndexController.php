<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        if(!isset($_SESSION['auth_user'])) {
            include 'public/login.php';
            die();
        }
    }

    public function indexAction()
    {
        if(!isset($_SESSION['auth_user'])) {
            include 'public/login.php';
            die();
        }
    }


}

