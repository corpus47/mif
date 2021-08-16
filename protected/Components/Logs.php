<?php

namespace Components;

class Logs extends \System\BaseClass {

	private static $instance = NULL;

	private $emails = NULL;

	private $logs_dir = ROOT_PATH . DIRECTORY_SEPARATOR . "protected" . DIRECTORY_SEPARATOR . "Logs" . DIRECTORY_SEPARATOR;

	private $model = NULL;
	
	public function __construct($config = NULL) {

		parent::getInstance();

		if(!is_dir($this->logs_dir)) {
			die("ERROR! Logs dir not found.");
			exit;
		} else {
			if(!file_exists($this->logs_dir . 'messages.log')) {

				$log = fopen($this->logs_dir . 'messages.log',"w");

                $message = date('Y.m.d H:i:s', time()) . ' - ' . 'Logfile létrehozva' . "\r\n";

                try {

                    fwrite($log,$message);

                    fclose($log);

                } catch(Exception $ex) {

                    die( 'Logfile létrehozási hiba! '. $ex->getMessage() );

                    exit;

                }

			}
		}

		$this->emails = new Emails($config);

		$this->model = new \Models\LogsModel($config);
		
	}

	public function log_to_file($msg = NULL) {

		if($msg != NULL || $msg != "") {

			$log = fopen($this->logs_dir . 'messages.log',"a");

        	$message = date('Y.m.d H:i:s', time()) . ' - ' . $msg . "\r\n";

        	fwrite($log, $message);

        	fclose($log);

		}

	}


}