<?php
class ControllerAccountCredit extends Controller {
	private $error = array();

	public function index() {
		
		function myCheckLoign($self) {
			return $self->customer->isLogged() ? true : false;
		};

		function myConfig($self){
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/token/credit.js');
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
		$data['r_wallet'] = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
		$data['r_wallet'] = count($data['r_wallet']) > 0 ? $data['r_wallet']['amount'] : 0.0;

		$data['c_wallet'] = $this -> model_account_customer -> getC_Wallet($this -> session -> data['customer_id']);
		$data['c_wallet'] = count($data['c_wallet']) > 0 ? $data['c_wallet']['amount'] : 0.0;
		$this -> load -> model('account/credit');

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;      

		$limit = 10;
		$start = ($page - 1) * 10;
		$history_total = $this->model_account_credit->getTotalTransferCredit($this -> session -> data['customer_id']);
		$history_total = $history_total['number'];

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = $limit; 
		$pagination->num_links = 5;
		$pagination->text = 'text';
		$pagination->url = $this->url->link('account/credit', 'page={page}', 'SSL');


		$data['history'] = $this->model_account_credit->getTransferHistoryById($this -> session -> data['customer_id'] , $limit , $start);
		$data['history_received'] = $this->model_account_credit->getReceivedHistoryById($this -> session -> data['customer_id'] , $limit , $start);
		
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
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/credit.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/credit.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/credit.tpl', $data));
		}
	}
	public function get_username($customer_id){
		$this -> load -> model('account/credit');
		$username = $this -> model_account_credit -> get_username($customer_id);
		return $username;
	}

	public function transfersubmit(){

		$json['login'] = $this->customer->isLogged() ? 1 : -1; 
		if($json['login'] === 1){
			$this -> load -> model('account/credit');
			$this -> load -> model('account/customer');
			$this -> load -> model('account/auto');
			//check customer
			$customer = $this -> request -> get['customer'];
			$check_user = $this -> model_account_credit -> checkUsername($customer);
			
			$json = array();
			!$this->request->get['FromWallet'] ? $json['FromWallet'] = -1 : $json['FromWallet'] = 1;
			intval(count($check_user)) === 0 ? $json['customer'] = -1 : $json['customer'] = 1;
			
			$variablePasswd = $this->model_account_customer->getPasswdTransaction($this -> request -> get['TransferPassword']);
			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;
			$wallet = $this -> request-> get['FromWallet'];
			$amount = $this -> request -> get['amount'];
			$json['amount'] = 1;
			if (intval($wallet) === 1 ) {
				$c_wallet = $this -> model_account_customer -> getC_Wallet($this -> session -> data['customer_id']);
				$c_wallet = floatval($c_wallet['amount']);
				if(($c_wallet < $amount) ){
					$json['amount'] = -1;
				}
			}
			if (intval($wallet) === 2 ) {
				$r_wallet = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
				$r_wallet = floatval($r_wallet['amount']);
				if(($r_wallet < $amount) ){
					$json['amount'] = -1;
				}
			}
			if ($json['FromWallet'] == 1 && $json['customer'] == 1 && $json['amount'] == 1 && $json['password'] == 1) {
				
				
				if (intval($wallet) === 1 ) {
					$returnDate = $this -> model_account_customer -> update_C_Wallet($amount, $this -> session -> data['customer_id']);
					$this -> model_account_auto -> update_C_Wallet($amount, $check_user['customer_id']);
					//History Send
					$this -> model_account_credit -> Save_history_transfer_credit(
						$this -> session -> data['customer_id'],
						$check_user['customer_id'],
						$amount,
						'C-Wallet',
						'Send'
					);
					$json['ok'] = 1;
				}
				if (intval($wallet) === 2 ) {
					$returnDate = $this -> model_account_customer -> update_R_Wallet($amount, $this -> session -> data['customer_id']);
					$this -> model_account_auto -> update_R_Wallet($amount, $check_user['customer_id']);
					//History Send
					$this -> model_account_credit -> Save_history_transfer_credit(
						$this -> session -> data['customer_id'],
						$check_user['customer_id'],
						$amount,
						'R-Wallet',
						'Send'
					);
					$json['ok'] = 1;
				}
				
			}else{
				$this -> response -> setOutput(json_encode($json));	
			}

		}
		
		$this -> response -> setOutput(json_encode($json));	
	}
	public function getaccount(){
		if($this->customer->isLogged() && $this -> request -> post['keyword'] ) {
			$this->load->model('account/customer');

			$tree=explode(',', $this->model_account_customer->getCustomLike($this -> request -> post['keyword'],  1));
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


	
}