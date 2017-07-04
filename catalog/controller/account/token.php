<?php
class ControllerAccountToken extends Controller {
	private $error = array();

	public function index() {
		
		function myCheckLoign($self) {
			return $self->customer->isLogged() ? true : false;
		};

		function myConfig($self){
			$self -> load -> model('account/customer');
		};


		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this->response->redirect($this->url->link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));		


		//data render website
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;      

		$limit = 10;
		$start = ($page - 1) * 10;
		$history_total = $this->model_account_customer->getTotalTokenHistory($this -> session -> data['customer_id']);
		$history_total = $history_total['number'];

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = $limit; 
		$pagination->num_links = 5;
		$pagination->text = 'text';
		$pagination->url = $this->url->link('account/token', 'page={page}', 'SSL');


		$data['history'] = $this->model_account_customer->getTokenHistoryById($this -> session -> data['customer_id'] , $limit , $start);
		
		$data['pagination'] = $pagination->render();

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$data['language']= $getLanguage;
		
		// $language = new Language($getLanguage);
		// $language -> load('account/dashboard');
		//$data['lang'] = $language -> data;

		$data['base'] = $server;
		$data['self'] = $this;	
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/token.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/token.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/token.tpl', $data));
		}
	}


	public function transfersubmit(){

		$json['login'] = $this->customer->isLogged() ? 1 : -1; 
		if($json['login'] === 1){
			$this -> load -> model('customize/register');
			$this -> load -> model('account/customer');
			//check customer
			$customer = $this -> request -> get['customer'];
			
			$Tree= $this->model_account_customer->checkUserName($this -> session -> data['customer_id']);

			$UTree=explode(',', $Tree);
			unset($UTree[0]);

			//get customer partent
			$customerParent = $this->model_account_customer->getCustomer( $this -> session -> data['customer_id']);
			$customerParent = $customerParent['p_node'];
			if(intval($customerParent) !== 0){
				$customerParent = $this->model_account_customer->getCustomer($customerParent);
				array_push($UTree, $customerParent['username']);
			}
			
		

			$customers=in_array($customer, $UTree) ? 1 : 0;

			$json['customer'] = intval($customers) === 0 ? -1 : 1;

			$variablePasswd = $this->model_account_customer->getPasswdTransaction($this -> request -> get['TransferPassword']);
			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;
			
			$customerSend = $this->model_account_customer->getCustomer($this -> session -> data['customer_id']);
			$pin = $customerSend['ping'];
			$num = $this -> request -> get['pin'];
			$amount= $num;		
			if( is_numeric($num) && intval($num) !== 0 && ($pin - $amount) >= 0 && $amount >=1 ){
				$json['pin'] = 1;
			}else{
				$json['pin'] = -1;
			}

			if($json['pin'] === 1 && $json['customer'] === 1 && $json['password'] === 1){
				
				//up pin
				$pin_update =  $this -> request -> get['pin'];
				$this->model_account_customer->updatePin($this -> session -> data['customer_id'], $pin_update);
			
				//down pin
				$customerReceived = $this->model_account_customer->getCustomerByUsername($this -> request -> get['customer']);
				$ping = intval($customerReceived['ping']);
				
				// if($ping === 0){
				// 	$this->model_account_customer->updatePin($customerReceived['customer_id'], $this -> request -> get['pin']);
				// }else {
					$ping = intval($this -> request -> get['pin']);
					$this->model_account_customer->updatePin($customerReceived['customer_id'], $ping, true);
				// }
				

				//save history cho user chuyen di

				$id_history = $this->model_account_customer->saveHistoryPin(
					$this -> session -> data['customer_id'],  
					'- ' . $this -> request -> get['pin'],
					$this -> request -> get['description'],
					'Transfer',
					$customerReceived['username']
				);

				//save history cho user nhan token
				$id_history = $this->model_account_customer->saveHistoryPin(
					$customerReceived['customer_id'],  
					'+ ' . $this -> request -> get['pin'],
					$this -> request -> get['description'],
					'Transfer',
					$customerSend['username']
				);
				//update 3840000 VND
				//get r-wallet of user received
				$checkR_Wallet = $this -> model_account_customer -> checkR_Wallet($customerReceived['customer_id']);
				if(intval($checkR_Wallet['number'])  === 0){
					if(!$this -> model_account_customer -> insertR_Wallet($customerReceived['customer_id'])){
						die();
					}
				}
				//get r-wallet of user received
				$getRwallet = $this -> model_account_customer -> getR_Wallet($customerReceived['customer_id']);
				$getGDRecived = $this -> model_account_customer -> getTotalGD($customerReceived['customer_id']);

				// if(intval($getGDRecived['number']) === 0 && intval($getRwallet['amount']) === 0 && intval($customerReceived['ping']) === 0){
				// 	$this -> model_account_customer -> updateR_Wallet($customerReceived['customer_id'] , 3840000);
				// 	$this -> model_account_customer -> updateCheckNEwuser($customerReceived['customer_id']);
				// }
				$json['ok'] = $id_history ? 1 : -1;
			}else{
				$json['ok'] = -1;
			}
		}
		
		$this -> response -> setOutput(json_encode($json));	
	}
	public function getaccount(){
		if($this->customer->isLogged() && $this -> request -> post['keyword'] ) {
			$this->load->model('account/customer');

			$tree=explode(',', $this->model_account_customer->getCustomLike($this -> request -> post['keyword'],  $this -> session -> data['customer_id']));
			unset($tree[0]);
			//get customer partent
			$customerParent = $this->model_account_customer->getCustomer( $this -> session -> data['customer_id']);
			$customerParent = $customerParent['p_node'];
			if(intval($customerParent) !== 0){
				$customerParent = $this->model_account_customer->getCustomer($customerParent);
				array_push($tree, $customerParent['username']);
			}
			

			foreach ($tree as $key => $value) {			
				echo $value ? '<li class="list-group-item" onClick="selectU('."'".$value."'".');">'.$value.'</li>' : ''; 
			}
		}
	}


	public function transfer(){

		function myCheckLoign($self) {
			return $self->customer->isLogged() ? true : false;
		};

		function myConfig($self){
			$self -> document -> addScript('catalog/view/javascript/autocomplete/jquery.easy-autocomplete.min.js');
			$self -> document -> addScript('catalog/view/javascript/transfer/transfer.js');
			$self -> document -> addStyle('catalog/view/theme/default/stylesheet/autocomplete/easy-autocomplete.min.css');
		};
		
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this->response->redirect($this->url->link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));	
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}


		$data['base'] = $server;
		$data['self'] = $this;
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/token_transfer.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/token_transfer.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/token_transfer.tpl', $data));
		}

	}

	public function callback() {

		$this -> load -> model('account/token');

		  $invoice_id = array_key_exists('invoice', $this -> request -> get) ? $this -> request -> get['invoice'] : "Error";
		

        $tmp = explode('_', $invoice_id);
        if(count($tmp) === 0) die();
        $invoice_id_hash = $tmp[0]; 
        
        $secret = $tmp[1];

		//check invoice

		$invoice = $this -> model_account_token -> getInvoiceByIdAndSecret($invoice_id_hash, $secret);

		count($invoice) === 0 && die();
		  $block_io = new BlockIo(key, pin, block_version);


        $transactions = $block_io->get_transactions(
            array(
                'type' => 'received', 
                'addresses' => $invoice['input_address']
            )
        );


        $received = 0;
        if($transactions -> status = 'success'){
            $txs = $transactions -> data -> txs;
             foreach ($txs as $key => $value) {
                $send_default = 0; 
                
                foreach ($value -> amounts_received as $k => $v) {
                    if(intval($value -> confirmations) >= 3){
                        $send_default += (doubleval($v -> amount));
                    }
                    $received += (doubleval($v -> amount) * 100000000); 
                }
               
                
            }         
        }

        intval($invoice['confirmations']) >= 3 && die();

        $this -> model_account_token -> updateReceived($received, $invoice_id_hash);
        $invoice = $this -> model_account_token -> getInvoiceByIdAndSecret($invoice_id, $secret);
     	$received = intval($invoice['received']);

		if ($received >= intval($invoice['amount'])) {

			//update Pin User
			$this -> model_account_token -> updatePin($invoice['customer_id'], $invoice['pin']);
			
			//update confirm
			$this -> model_account_token -> updateConfirm($invoice_id_hash, 3);

			

		}
	}
	public function show_invoice_pending() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> load -> model('account/token');
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		$data['notCreate'] = true;
		$data['invoice'] = $this -> model_account_token -> getAllInvoiceByCustomer_notCreateOrder($this -> customer -> getId());
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;
		$data['self'] = $this;
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/checkBTC.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/checkBTC.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/checkBTC.tpl', $data));
		}
	}

	public function show_invoice() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> load -> model('account/token');
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		!array_key_exists('invoice_hash', $this -> request -> get) && die();
		$invoice_hash = $this -> request -> get['invoice_hash'];

		$invoice = $this -> model_account_token -> getInvoceFormHash($invoice_hash, $this -> customer -> getId());
		!$invoice && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));

		$count_invoice = $this -> model_account_token -> countToken($this -> customer -> getId());
		$count_invoice = $count_invoice['number'];
		$data['notCreate'] = false;
		if ($count_invoice > 6) {
			$data['notCreate'] = true;
			$data['invoice'] = $this -> model_account_token -> getAllInvoiceByCustomer_notCreateOrder($this -> customer -> getId());
		} else {
			$data['bitcoin'] = $invoice['amount'];
			$data['wallet'] = $invoice['input_address'];
		}
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;
		$data['self'] = $this;
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/checkBTC.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/checkBTC.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/checkBTC.tpl', $data));
		}
	}

	public function buySubmit() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> load -> model('account/token');
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		!array_key_exists('pin_price', $this -> request -> get) && die();

		//check new user

		$customer_get = $this -> model_account_customer -> getCustomer($this -> session -> data['customer_id']);
		//language
		
		if(intval($this -> request -> get['pin_price'] < 1)){
			
			$json['error'] = true;
			$json['text'] = 'Please buy more than 1 token for new user';
			$this -> response -> setOutput(json_encode($json));
			
		}else{
			//check count customer
			$count_invoice = $this -> model_account_token -> countToken($this -> session -> data['customer_id']);
			$count_invoice = $count_invoice['number'];
			$data['notCreate'] = false;
			if ($count_invoice >=3)
				$data['notCreate'] = true;
			//save invoice

			if (!$data['notCreate']) {
				$pin =$this -> request -> get['pin_price'];
				switch (intval($pin)) {
					case 2:
						$pin_tmp = 10;
						break;
					case 4:
						$pin_tmp = 20;
						break;
					case 8:
						$pin_tmp = 40;
						break;
					case 16:
						$pin_tmp = 80;
						break;
					case 32:
						$pin_tmp = 160;
						break;
					case 64:
						$pin_tmp = 320;
						break;
					
					default:
						die();
						break;
				}
				
				$url = "https://blockchain.info/tobtc?currency=USD&value=" . (string)$pin_tmp;
				$amount = file_get_contents($url);

				$amount = $amount * 100000000;

				$secret = substr(hash_hmac('ripemd160', hexdec(crc32(md5(microtime()))), 'secret'), 0, 16);

				$invoice_id = $this -> model_account_token -> saveInvoice($this -> session -> data['customer_id'], $secret, $amount, $pin);
				$invoice_id === -1 && die('Server error , Please try again !!!!');
				$invoice_id_hash = hexdec(crc32(md5($invoice_id)));

				$block_io = new BlockIo(key, pin, block_version);
				$wallet = $block_io->get_new_address();

	            $my_wallet = $wallet -> data -> address;         
	            $call_back = HTTPS_SERVER.'callback_token.html?invoice=' . $invoice_id_hash . '_' . $secret;

	            $reatime = $block_io -> create_notification(
	                array(
	                    'url' => HTTPS_SERVER.'callback_token.html?invoice=' . $invoice_id_hash . '_' . $secret , 
	                    'type' => 'address', 
	                    'address' => $my_wallet
	                )
	            );


				!$this -> model_account_token -> updateInaddressAndFree($invoice_id, $invoice_id_hash , $my_wallet, 0.0003, $my_wallet) && die('Server Error !!!!');
				
				$json['error'] = false;
				$json['text'] = HTTPS_SERVER . 'confirm&invoice_hash=' . $invoice_id_hash;
				$this -> response -> setOutput(json_encode($json));
			} 
			else {
				$json['error'] = false;
				$json['text'] = HTTPS_SERVER . 'index.php?route=account/token/show_invoice_pending';
				$this -> response -> setOutput(json_encode($json));
			}
		}
	}



	

	public function buypin() {

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/token');
		$self -> load -> model('account/customer');
			// $self -> document -> addScript('catalog/view/javascript/transfer/transfer.js');
			$self -> document -> addScript('catalog/view/javascript/token/token.js');
		};

		//language
		

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}
		$data['wallet'] = $this -> config -> get('config_wallet');
		$data['priceBTC'] = $this->config->get('config_price_btc_now');
		$data['base'] = $server;
		$data['self'] = $this;

		//get all invoice

		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 6;
		$start = ($page - 1) * 6;
		$invoice_total = $this -> model_account_token -> getAllInvoiceByCustomerTotal($this -> session -> data['customer_id']);

		$invoice_total = $invoice_total['number'];
		$pagination = new Pagination();
		$pagination -> total = $invoice_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('account/token/buypin', 'page={page}#order-pin-history', 'SSL');

		$data['pagination'] = $pagination -> render();
		$customer = $this -> model_account_customer-> getCustomer($this -> session -> data['customer_id']);
		$data['pin'] = $customer['ping'];
		$data['invoice'] = $this -> model_account_token -> getAllInvoiceByCustomer($this -> session -> data['customer_id'], $limit, $start);
//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> customer -> getId());
		$language = new Language($getLanguage);
		$language -> load('account/token');
		$data['lang'] = $language -> data;

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/buypin.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/buypin.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/buypin.tpl', $data));
		}

	}
}