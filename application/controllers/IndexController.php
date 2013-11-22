<?php

class IndexController extends Zend_Controller_Action
{
	protected $auth_session_data;

    public function init()
    {
    	$this->auth_session_data = new Zend_Session_Namespace('auth_session_data');    	
    	if (isset($_SESSION['auth_session_data']['timeout'])) {
    		if ($_SESSION['auth_session_data']['timeout'] + 30 * 60 < time()) {
	    		$this->logoutAction();
	    	}  	    		
    	} 
    }

    public function indexAction()
    {
    	$redir = $_GET ? $_GET['r'] : ""; 
    	$this->auth_session_data = new Zend_Session_Namespace('auth_session_data');
        if (isset($_POST['submit'])) {
        	$user_model = new Application_Model_UsersMapper();
        	$auth_user = $user_model->authenticate_user($_POST);        	

			if ($auth_user) {
				$this->auth_session_data->timeout = time();
				$this->auth_session_data->username = $_POST['username'];
				if ($redir!="") {
					header( "Location: {$this->view->baseUrl()}".urldecode($redir) );
				}
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
    		$item_id = urlencode($item['rcc_sss_program_news_data_id']['S']);
    		$hellip = strlen($item['program_news_details']['S']) > 100 ? "&hellip;" : "";
    		$excerpt = substr($item['program_news_details']['S'],-100).$hellip;
    		
    		$prefix = "https://rccsss.s3-us-west-2.amazonaws.com/";
    		$image_name = $item['program_news_image']['S'];
    		
    		if (strpos($image_name,$prefix) === false) {
    			$image_name = $prefix.$image_name;
    		}    		
    		
    		$output .= "<tr data-id='{$item_id}'> \n";
    		$output .= "    <td>{$item['program_news_title']['S']}</td> \n";
    		$output .= "    <td><img src='{$image_name}' alt='Story image.' /></td> \n";
    		$output .= "    <td>{$excerpt}</td> \n";
    		$output .= "    <td class='action'>
    							<a href='news/show/id/{$item_id}' class='show'>Show</a><br />
    							<a href='news/edit/id/{$item_id}' class='edit'>Edit</a><br />
    							<a href='#' class='delete'>Delete</a>
    						</td> \n";
    		$output .= "</tr> \n";
    	}
    
    	return $output;
    }
    
    public function logoutAction()
    {
    	$this->auth_session_data = new Zend_Session_Namespace('auth_session_data'); 	
    	unset($this->auth_session_data->timeout);
    	unset($this->auth_session_data->username);  
    	header( "Location: {$this->view->baseUrl()}" );
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);    	
    }    

}

