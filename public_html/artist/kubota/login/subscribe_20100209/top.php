<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib0904/simple/DefaultAction.php');

class Action extends DefaultAction {
	function validate() {
		if(!$_SESSION["loginID"]['subscribe_20100209']) {
			$this->__controller->redirectToURL('../');
			exit();
		}

		return true;
	}
}
?>