<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	if (isset($_SESSION['timeout'])) {
	    	if ($_SESSION['timeout'] + 30 * 60 < time()) {
	    		$this->logoutAction();
	    	}  	    		
    	}
    }

    public function indexAction()
    {
    	$redir = $_GET ? $_GET['r'] : "";
        if (isset($_POST['submit'])) {
        	$user_model = new Application_Model_UsersMapper();
        	$auth_user = $user_model->authenticate_user($_POST);        	

			if ($auth_user) {
				$_SESSION['timeout'] = time();
				$_SESSION['auth_user']['username'] = $_POST['username'];
				header( "Location: {$this->view->baseUrl()}".urldecode($redir) );
				$this->view->login_message = "";
			} else {
				$this->view->login_message = "<span class='error'>Invalid username or password!</span>";
			}
        }             
    }
    
    public function logoutAction()
    {
    	unset($_SESSION['timeout']);
    	unset($_SESSION['auth_user']);    	
    	header( "Location: {$this->view->baseUrl()}" );
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
    }    

}

