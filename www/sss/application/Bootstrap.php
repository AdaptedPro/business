<?php
require 'library/AWS/aws.phar';
require 'library/vendor/autoload.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('HTML5');
	}

}

