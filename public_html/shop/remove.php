<?phprequire_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/DatabaseAction.php');require_once($_SERVER['DOCUMENT_ROOT'] . '/../lib/simple/Paginate.php');class Action extends DatabaseAction {	function prepare() {		if (!$this->page) {			$this->page = $this->__defaultPage;		}		if (!isset($this->amount)) {			$this->amount = $this->__defaultAmount;		}	}			function execute() {		$cart = $this->cart;				if (is_array($cart)) {			if (is_array($cart[$this->item_code])) {				unset($cart[$this->item_code]);			}			else {				$cart = array();			}		}		$this->cart = $cart;		if (count($cart)) {			$this->__controller->redirectToAction('cart');						return false;		}		else {			$this->__controller->redirectToAction('list');						return false;		}	}}?>