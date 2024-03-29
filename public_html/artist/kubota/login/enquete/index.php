<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib0904/simple/DefaultController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib0904/simple/DefaultAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib0904/util/mail/SimpleMail.php');

//ini_set('display_errors','on');
//error_reporting(E_ALL);

class Controller extends DefaultController {
	var $units = null;
	var $errors = null;
	
	function init() {
		parent::init();
		$this->__defaultAction = 'input';
		$this->__defaultActionFile = $_SERVER['DOCUMENT_ROOT'] . '/../lib0904/simple/DefaultAction.php';
		$this->__defaultActionClass = 'DefaultAction';

		$this->units["presents"] = array(
			1 => array(
				"name" => "µ×ÊÝÅÄÍ¿­ Ä¾É®¥µ¥¤¥Æ¤ NYÅÚ»º A<br>[URBAN OUTFITTERSÀÞ¤ô¦¿¤¿¤ß¥¥ê§Ã¥¯]¡¡2Ì¾ÍÍ",
				"value" => ""
			),
			2 => array(
				"name" => "µ×ÊÝÅÄÍ¿­ Ä¾É®¥µ¥¤¥Æ¤ NYÅÚ»º B<br>[URBAN OUTFITTERS¥Î¡¼¥È]¡¡3Ì¾ÍÍ",
				"value" => ""
			),
			3 => array(
				"name" => "Ê¸¸ËËÜ¡Ö´Ì¯¤ÊÏÀÍ­µ,­¶¡×Ä¾É®¥µ¥¤¥Æ¤ô£¡1Ì¾ÍÍ",
				"value" => "¢¨¥µ¥¤¥¤Ï½ò¶©¤Î¤ß¤È¤Ê¤ô¦Þ¤¹"
			),
			4 => array(
				"name" => "µ×ÊÝÅÄÍ¿­ Ä¾É®¥Ï¥¬¥­¡¡1Ì¾ÍÍ",
				"value" => ""
			));
	
		$this->auth();
	}

	function input(){}

	function confirm(){
		$this->errors = $this->dataValidation();
		if(count($this->errors))
			return "input";
	}

	function process(){
		$this->errors = $this->dataValidation();
		if(count($this->errors))
			return "input";

		$m = parse_ini_file(dirname(__FILE__).'/mail.ini', true);
		$mail = new SimpleMail();

		$mail->setFrom($m['admin']['fromMail'],$m['admin']['fromName']);
		$mail->setSubject($m['admin']['subject']);
		$mail->setBody(
			dirname(__FILE__).'/mail.html' ,
			array("data" => $this->data,"units"=>$this->units) );

		$mail->setTo($m['admin']['toMail']);
		$mail->send();

		$mail->setSubject($m['customer']['subject']);
		$mail->setTo($this->data['mail'],$this->data['name'] . " ¤µ¤");
		$mail->send();

		$this->data = null;
		$this->redirectToAction('complete');
	}

	function complete(){}

	function dataValidation(){
		$keys = array(
			"enq1" => array("emp"),
			"enq2" => array("emp"),
			"enq3" => array(),
			"name" => array("emp"),
			"mail" => array("emp","mail"),
			"no" => array("emp","^[0-9]{8}$"),
			"present_id" => array("empR"),
		);

		return $this->validationExec($this->data , $keys);
	}

	function auth(){
		session_start();

		if(!$_SESSION["loginID"]){
			$this->redirectToURL('../');
			exit();
		}
	}


	function validationExec($data , $keys , $errorMessages=null , $errors=null){
		$d = $data;
		$errorMessagesD = array(
			'emp' => 'ÆÎÏ¤¤ª´ô¦¤¤·¤Þ¤¹¡£',
			'empR' => 'ÁªÂ¤¤ª´ô¦¤¤·¤Þ¤¹¡£',
			'empS' => 'ÁªÂ¤¤ª´ô¦¤¤·¤Þ¤¹¡£',
			'num' => 'È¾³Ñ¿»¤ÇÆÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡£',
			'cmp' => '³ÎÇ§ÆÎÏ¤È°Ã×¤·¤Æ¤¤¤Þ¤»¤¡£ÆÎÏ¤¤´³ÎÇ§¤¯¤À¤µ¤¤¡£',
			'mail' => '·Á¼°¤³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡£',
			'reg' => '·Á¼°¤³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡£'
		);
		if(count ($errorMessages))
			$errorMessagesD = array_merge($errorMessagesD, $errorMessages);
		$errorMessages = $errorMessagesD;

		$errors = array();
		foreach($keys as $name => $key){
			foreach ($key as $validation){
				switch ($validation) {
					case "emp":
						if(empty ($d[$name])){
							$errors[$name] = $errorMessages[$validation];
							break 2;
						}
						break;
					case "empR":
						if(empty ($d[$name])){
							$errors[$name] = $errorMessages[$validation];
							break 2;
						}
						break;
					case "mail":
						if(!preg_match('/^(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|"[^\\\x80-\xff\n\015"]*(?:\\[^\x80-\xff][^\\\x80-\xff\n\015"]*)*")(?:\.(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|"[^\\\x80-\xff\n\015"]*(?:\\[^\x80-\xff][^\\\x80-\xff\n\015"]*)*"))*@(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\x80-\xff\n\015\[\]]|\\[^\x80-\xff])*\])(?:\.(?:[^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff]+(?![^(\040)<>@,;:".\\\[\]\000-\037\x80-\xff])|\[(?:[^\\\x80-\xff\n\015\[\]]|\\[^\x80-\xff])*\]))*$/', $d[$name])){
							$errors[$name] = $errorMessages[$validation];
							break 2;
						}
						break;
					default:
						if(!mb_ereg_match($validation, $d[$name])){
							$errors[$name] = $errorMessages["reg"];
							break 2;
						}
						break;
				}
			}
		}

		return $errors;
	}
}

$controller = new Controller();
$controller->run();
?>