<?php
class ControllerAccountGd extends Controller {

	public function index() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));


		//language
		
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;


		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$data['language'] = $getLanguage;
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;
		
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;

		$gd_total = $this -> model_account_customer -> getTotalGD($this -> session -> data['customer_id']);

		$gd_total = $gd_total['number'];


		$pagination = new Pagination();
		$pagination -> total = $gd_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('account/gd', 'page={page}', 'SSL');

		$data['gds'] = $this -> model_account_customer -> getGDById($this -> session -> data['customer_id'], $limit, $start);
		$data['pagination'] = $pagination -> render();

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd.tpl', $data));
		}
	}

	public function create() {
		
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/gd/create.js');
			$self -> load -> model('account/customer');
		};

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//get r_wallet AND c_wallet USER

		$data['r_wallet'] = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
		$data['r_wallet'] = count($data['r_wallet']) > 0 ? $data['r_wallet']['amount'] : 0.0;

		$data['c_wallet'] = $this -> model_account_customer -> getC_Wallet($this -> session -> data['customer_id']);
		$data['c_wallet'] = count($data['c_wallet']) > 0 ? $data['c_wallet']['amount'] : 0.0;

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd_create.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd_create.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd_create.tpl', $data));
		}
	}

	public function submit() {
die();
		if ($this -> customer -> isLogged() && $this -> request -> get['Password2']) {
			$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
			$this -> load -> model('account/customer');

				

			$variablePasswd = $this -> model_account_customer -> getPasswdTransaction($this -> request -> get['Password2']);
			
			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;
			if($json['password'] === -1){
				$json['ok'] = -1;
				$this -> response -> setOutput(json_encode($json));
			}else{
				$customer = $this -> model_account_customer ->getCustomer($this -> session -> data['customer_id']);
			
				if(intval($customer['ping']) <= 1){
					// /$this -> response -> redirect($this -> url -> link('account/token/order', 'token='.$pd['id'].'', 'SSL'));
					$json['pin'] = -1;
				}else{
					$json['pin'] = 1;
				}
				if($json['pin'] === -1){
					$json['ok'] = -1;
					$this -> response -> setOutput(json_encode($json));
				}else{

					$pd_total = $this -> model_account_customer -> getStatusPD();
						$pd_total=$pd_total['pdtotal'];
						$gd_total = $this -> model_account_customer -> getStatusGD();
						$gd_total=$gd_total['gdtotal'];

					$formWallet = $this -> request -> get['FromWallet'];
					$amount = $this -> request -> get['amount'];

					if(intval($formWallet) === 2){
						$json['checkWaiting'] = $pd_total >= $gd_total ? 1 : -1;
						$r_wallet = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
						$r_wallet = floatval($r_wallet['amount']);
						if($r_wallet < $amount ){
							die();
						}
					}
					if(intval($formWallet) === 1){
						$json['checkWaiting'] = 1;
						

						$c_wallet = $this -> model_account_customer -> getC_Wallet($this -> session -> data['customer_id']);
						
						$c_wallet = floatval($c_wallet['amount']);
						if(($c_wallet < $amount) ){
							die();
						}
						
					}

					//check gd for day~~~
					$returnDate = null;
					$date = strtotime(date('Y-m-d'));
					$countGDOfDay = $this -> model_account_customer -> countGdOfDay(date('m',$date) , date('Y',$date)  , date('d',$date));

					$countGDOfDay = intval($countGDOfDay['number']);
					$returnDate = $countGDOfDay < 3 ? true : null;
					
					
					//check account_number
					$account_number = $customer['account_number'];
					if(!empty($account_number)){
						$json['account_number']= 1;
					}else{
						$json['account_number'] = -1;
					}
					if(intval($formWallet) === 1){
						$json['checkWaiting'] = 1;
						$json['account_number']= 1;
					}
					$json['checkWaiting'] = 1;

					if($returnDate === true && $json['checkWaiting'] === 1 && $json['account_number']=== 1){
						//$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], intval($customer['ping']) - 1 );
						$returnDate = $this -> model_account_customer -> createGD($amount);
						
						//$id_history = $this->model_account_customer->saveHistoryPin(
						//	$this -> session -> data['customer_id'],  
						//	'- 1' ,
						//	'Used pin for GD'.$returnDate['gd_number'],
						//	'GD',
						//	'Used pin for GD'.$returnDate['gd_number']
						//);
						//update r_wallet or c_wallet
						$gd_number = $returnDate['gd_number'];
						if($returnDate['query'] === true){
							$returnDate = false;
							if(intval($formWallet) === 2){
								//get R-wallet
								$returnDate = $this -> model_account_customer -> update_R_Wallet($amount, $this -> session -> data['customer_id']);
								// $this -> invoice_gd($amount, $gd_number);
							}

							if(intval($formWallet) === 1){
								$returnDate = $this -> model_account_customer -> update_C_Wallet($amount, $this -> session -> data['customer_id']);
								// $this -> invoice_gd($amount, $gd_number);
							}
						}
					}
					
					$json['ok'] = $returnDate === true && $json['password'] === 1 ? 1 : -1;
					
					$this -> response -> setOutput(json_encode($json));
				}
				
			}
			
		}
	}

	public function invoice_gd($amount, $gd_number){
		
		$this->load->model('account/customer');       	
       	$customer = $this->model_account_customer->getCustomer($this->session->data['customer_id']);
        $html = '';
        $html .= '<p>User ID: <b>'.$customer['username'].'</b></p>';
       	$html .= '<p>Full Name: <b>'.$customer['account_holder'].'</b></p>';
       	$html .= '<p>Bank Name: <b>'.$customer['bank_name'].'</b></p>';
       	$html .= '<p>Account Number: <b>'.$customer['account_number'].'</b></p>';
       	$html .= '<p>Brank Bank: <b>'.$customer['branch_bank'].'</b></p>';
       	$html .= '<p>Phone number: <b>'.$customer['telephone'].'</b></p>';
       	$date_added= date('Y-m-d') ;
        $html .= '<p>Date Created: <b>'.$date_added.'</b></p>';
       
        $html .= '<p>Total Withdrawal: <b>'.(number_format($amount)).' USD</b></p>';
       	
       	$html .= '<p style="font-size:14px;color: black;text-align:center;"><a href="'.HTTPS_SERVER.'index.php?route=account/gd/updatefn_gd&token='.$gd_number.'" style="margin: 0 auto;width: 200px;background: #d14836;    text-transform: uppercase;
    border-radius: 5px;
    font-weight: bold;text-decoration:none;color:#f8f9fb;display:block;padding:12px 10px 10px">Confirmation Payment</a></p>';
       
     
        $html_mail = $html;
     //   echo "<pre>"; print_r($html_mail); echo "</pre>"; die();
        $mail = new Mail();
		$mail -> protocol = $this -> config -> get('config_mail_protocol');
		$mail -> parameter = $this -> config -> get('config_mail_parameter');
		$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
		$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
		$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
		$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

		$mail -> setTo(array(0 => 'Ou.ponlok@gmail.com', 1 => 'mmo.hyipcent@gmail.com@gmail.com'));
		// $mail -> setTo('mmo.hyipcent@gmail.com');
		$mail -> setFrom($this -> config -> get('config_email'));
	
		$mail -> setSender(html_entity_decode("".$customer['username']." - Withdrawal ".number_format($amount)." USD!", ENT_QUOTES, 'UTF-8'));
		$mail -> setSubject("".$customer['username']." - Withdrawal ".number_format($amount)." USD! - ".date('d/m/Y H:i:s')." ");
		$mail -> setHtml(''.$html_mail.'');
		$mail -> send();

	}
	public function updatefn_gd(){
		$this->load->model('account/customer');
		if ($this -> request -> get['token']) {
			$this -> model_account_customer -> update_fn_gd($this -> request -> get['token']);
			$this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		} else{
			die('Error');
		}

	}
	public function transfer(){

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');

		};

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		!$this -> request -> get['token']  && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;
		

		$getGDCustomer = $this -> model_account_customer -> getGDByCustomerIDAndToken($this -> session -> data['customer_id'], $this -> request -> get['token']);
		
		intval($getGDCustomer['number']) === 0 && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
		$getGDCustomer = null;

		$data['transferList'] = $this -> model_account_customer -> getGdFromTransferList($this -> request -> get['token']);
		// print_r($data['transferList']);
		// die();
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd_transfer.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd_transfer.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd_transfer.tpl', $data));
		}
	}

	public function confirm(){
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/confirm/confirm.js');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');

		};

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/gd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		!$this -> request -> get['token']  && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		$data['transferConfirm'] = $this -> model_account_customer -> getGDTranferByID($this -> request -> get['token']);

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/gd_confirm.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/gd_confirm.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/gd_confirm.tpl', $data));
		}
	}

}
