<?php

class App extends \System\BaseClass {

	private static $config;

	private static $emails;

	private static $dbh = NULL;

	public function __construct($config = NULL) {

        parent::getInstance();

        self::$config = $config;

        self::$config['live'] = LIVE;

		self::$config['log'] = new Components\Logs(self::$config);

		//self::$emails = new Components\Emails(self::$config);

		self::$config['email'] = new Components\Emails(self::$config);

		self::$dbh = new System\Dbh(self::$config);


		self::$config['log']->log_to_file('Application run');

	}

	public static function run() {

		//self::$emails->smtp_test();
		

		echo '<br />Application run';

	}

}