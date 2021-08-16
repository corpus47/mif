<?php

namespace Models;

class LogsModel extends \System\BaseClass {
	
	private static $instance = NULL;

	private $db = NULL;

	public function __construct($config = NULL) {

			parent::getInstance();

	}

}