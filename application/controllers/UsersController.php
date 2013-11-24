<?php

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function logoutAction()
    { 	 
		unset($_SESSION['auth_session_data']);
    	header( "Location: {$this->view->baseUrl()}" );
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
    }

}

