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

        $this->view->table_output		= $this->build_news_data_table();        
    }

    private function build_news_data_table()
    {
    	$news_model = new Application_Model_NewsMapper();
    	$news = $news_model->get_all_news_items_from_db();
    	 
    	$output = "";
    	foreach ($news as $item)
    	{
    		$output .= "<tr> \n";
    		$output .= "    <td>{$item['program_news_title']['S']}</td> \n";
    		$output .= "    <td><img src='{$item['program_news_image']['S']}' alt='Story image.' /></td> \n";
    		$output .= "    <td>{$item['program_news_details']['S']}</td> \n";
    		$output .= "    <td class='action'>
    							<a href='news/' class='show'>Show</a><br />
    							<a href='news/' class='edit'>Edit</a><br />
    							<a href='news/' class='delete'>Delete</a>
    						</td> \n";
    		$output .= "</tr> \n";
    	}
    
    	return $output;
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

