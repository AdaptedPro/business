<?php

class Application_Model_Images
{
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}
}

