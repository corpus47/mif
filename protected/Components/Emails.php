<?php
namespace Components;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;

require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'Components' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'PHPMailer.php');
require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'Components' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'Exception.php');
require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'Components' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'SMTP.php');
require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'Components' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'OAuth.php');

class Emails extends \System\BaseClass {

	private static $instance = NULL;

	private static $config;

	//private $admin_email;

	private static $mail;
	
	public function __construct($config = NULL) {

		parent::getInstance();

		self::$config = $config;

		self::$mail = new PHPMailer(true);


	}

	public function smtp_test() {

		if(self::$config['live'] === 0) {

			self::call_Service("bela.csupor@live.com","Tesztuzenet","Ez egy tesztüzenet");

		} else {

			self::call_PHPMailer("bela.csupor@live.com","Tesztuzenet","Ez egy tesztüzenet");

		}


	}

	public function alert_email($message) {


		$message .= $_SERVER['HTTP_HOST'];

		if(self::$config['live'] === 0) {

			self::call_Service(self::$config['admin_email'],"HIBA!!! - " . $_SERVER['HTTP_HOST'], $message);

		} else {

			self::call_PHPMailer(self::$config['admin_email'],"HIBA!!! - " . $_SERVER['HTTP_HOST'], $message);

		}

	}

	public function call_PHPMailer( $to = "", $subject = "", $message = "" ) {

		self::$mail->isHTML(true);

		self::$mail->isSendmail();                                      // Mail küldés Sendmail használatával
		self::$mail->SMTPAuth = true;                                   // SMTP autentikáció
		self::$mail->Username = self::$config['smtp']['username'];              // SMTP felhasználónév
		self::$mail->Password = self::$config['smtp']['password'];
		self::$mail->CharSet = "UTF-8";

		self::$mail->setFrom('info@wplabor.hu');
		self::$mail->addAddress($to);
		self::$mail->Subject = $subject;
		self::$mail->Body = $message;

		try {
			self::$mail->Send();
		} catch (Exception $e) {
			die("Message could not be sent. Mailer Error: {self::$mail->ErrorInfo}");
			exit;
		}

	}

	public function call_Service( $to = "", $subject = "", $message = "" ) {

		echo '<br />Call Service<br />';
		
		$post = array(
			'password' => 'sc1959op',
			'to'	   => $to,
			'subject'  => $subject,
			'message'  => $message
		);

		$headers = ['Content-Type' => 'application/x-www-form-urlencoded', 'charset' => 'utf-8'];

		ob_start();

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"http://wplabor.hu/serv_Ts4e62Pr.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);

		$result = curl_exec($ch);

		curl_close($ch);

		ob_end_clean();

		var_dump($result);

		return $result;

	}


}