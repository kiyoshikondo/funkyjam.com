<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/DatabaseAction.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/simple/Paginate.php');

class Action extends DatabaseAction {
	var $__defaultPage = null;
	var $__defaultAmount = null;
	var $__defaultPageAmount = null;
	
	function init() {
		DatabaseAction::init();
		
		$this->__defaultPage = 1;
		$this->__defaultAmount = 20;
		$this->__defaultPageAmount = 5;
	}

	function prepare() {
		if (!$this->page) {
			$this->page = $this->__defaultPage;
		}
		if (!isset($this->amount)) {
			$this->amount = $this->__defaultAmount;
		}
		if ($this->item_code) {
			$this->item_code = null;
		}
	}
		
	function execute() {
		$db =& $this->_db;

		//item
		$result = $db->statement('shop/sql/item_list.sql');
		$tree = $db->buildTree($result, 'item_code');
		$this->itemList = $tree;
		
		
		//森大輔CD用特別対応追加フラグ
		unset($this->moriSp);
		if(count($this->cart)=='1'){
			if (in_array("FAMC-218", $this->cart['FAMC-218'])) {
				$this->moriSp=1;
			}
		}

	}
	
	function validate() {
		
		return true;
	}
}
?>