<?php
class ControllerAccountPd extends Controller {

	public function index() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

			function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
		};
		$this -> load -> model('account/customer');
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;

		$customer = $this -> model_account_customer ->getCustomer($this -> session -> data['customer_id']);
			
			
		$rows =  $this -> model_account_customer ->countPDINProvide();
		
		$data['countPd'] = count($rows);

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;
		$pd_total = $this -> model_account_customer -> getTotalPD($this -> session -> data['customer_id']);

		$pd_total = $pd_total['number'];

		$pagination = new Pagination();
		$pagination -> total = $pd_total;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text';
		$pagination -> url = $this -> url -> link('account/pd', 'page={page}', 'SSL');

		$data['pds'] = $this -> model_account_customer -> getPDById($this -> session -> data['customer_id'], $limit, $start);
		$data['pagination'] = $pagination -> render();

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd.tpl', $data));
		}
	}

	public function create(){
		
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/pd/create.js');
			$self -> load -> model('account/customer');
			
		};

		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
	
		$CheckPDUpdate = $this -> model_account_customer -> getCheckPD($this -> session -> data['customer_id']);	
		$data['self'] = $this;
        $page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;
		$limit = 10;
        $start = (intval($page) - 1) * 10;
        $pd_total = $this -> model_account_customer -> getTotalPD($this -> session -> data['customer_id']);

        $pd_total = $pd_total['number'];

        $pagination = new Pagination();
        $pagination -> total = $pd_total;
        $pagination -> page = $page;
        $pagination -> limit = $limit;
        $pagination -> num_links = 5;
        $pagination -> text = 'text';
        $pagination -> url = str_replace('/index.php?route=', "/", $this -> url -> link('investment-detail.html', 'page={page}', 'SSL'));

        $data['pds'] = $this -> model_account_customer -> getPDById($this -> session -> data['customer_id'], $limit, $start);
        $data['pagination'] = $pagination -> render();


        //get all PD
        $data['pd_all'] = $this -> model_account_customer ->getPD($this -> session -> data['customer_id']);
        
		$data['count'] = $CheckPDUpdate['check_PD'];
	// $rows =  $this -> model_account_customer ->countPD($this -> session -> data['customer_id']);
	// 	count($rows) >= 1 && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		$countPD_Day = $this -> model_account_customer -> countPD_by_customer_id($this -> session -> data['customer_id']);

		// intval($countPD_Day['number']) >= 1 &&$this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		
		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd_create.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd_create.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd_create.tpl', $data));
		}
	}
	public function submit() {
		
		$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
		
		if ($this -> customer -> isLogged() && $this -> request -> get['amount'] && $this -> request -> get['Password2']) {
			$this -> load -> model('account/customer');
			$variablePasswd = $this -> model_account_customer -> getPasswdTransaction($this -> request -> get['Password2']);

			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;

			$customer = $this -> model_account_customer ->getCustomer($this -> session -> data['customer_id']);
			
			$checkAccount_holder = $customer['account_holder'];

			$amount	= $this -> request -> get['amount'];
			switch (intval($amount)) {
				case 500:
					$amountPin = 1;
					break;
				case 1000:
					$amountPin =2;
					break;
				case 2000:
					$amountPin =4;
					break;
				case 4000:
					$amountPin =8;
					break;
				default:
					$amountPin =8;
					break;
			}
			
			if(intval($customer['ping']) >= $amountPin){
				// /$this -> response -> redirect($this -> url -> link('account/token/order', 'token='.$pd['id'].'', 'SSL'));
				$json['pin'] = 1;
			}else{
				$json['pin'] = -1;
			}
			
			$pd_total = $this -> model_account_customer -> getStatusPD();
			$pd_total = $pd_total['pdtotal'];
			$gd_total = $this -> model_account_customer -> getStatusGD();
			$gd_total = $gd_total['gdtotal'];

			$json['checkWaiting'] = $pd_total > $gd_total ? 1 : -1;

			$CountDay = $this -> model_account_customer ->CountGDDay();

			$json['checkCountDay']= $CountDay ? 1 : -1;
			$GDTMP = $this -> model_account_customer -> getPDById($this -> session -> data['customer_id'], 1, 0);

			if (count($GDTMP) === 0) {
				$json['checkCountDay'] = 1;
			}
			
			$account_number = $customer['account_number'];
			$account_wallet = $customer['wallet'];
			if(!empty($account_number) || !empty($account_wallet)){
				$json['account_number']= 1;
			}else{
				$json['account_number'] = -1;
			}
			$pd_total = $this -> model_account_customer -> getTotalPD($this -> session -> data['customer_id']);
			if ($pd_total['number'] > 0) {
				$json['password'] = -1;
			}

			//pdwaiting
			$json['checkWaiting'] = 1;
			//$json['checkCountDay'] = 1;
			if ($json['password'] === 1 && $json['pin'] === 1 && $json['checkCountDay'] === 1 && $json['checkWaiting'] === 1 && $json['account_number']=== 1 ) {
					$amount	= $this -> request -> get['amount'];

					switch (intval($amount)) {
						case 500:
							$max_profit= 37.5;
							$amountPin = 1;
							break;
						case 1000:
							$max_profit= 75;
							$amountPin =2;
						
							break;
						case 2000:
							$max_profit= 150;
							$amountPin =4;
				
							break;
						case 4000:
							$max_profit= 300;
							$amountPin =8;
						
							break;
						
						default:
							$amountPin =8;
							break;
					}
					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], $amountPin );	
					
				 
					$pd_query = $this -> model_account_customer -> createPD($amount ,$max_profit);
													
					$id_history = $this->model_account_customer->saveHistoryPin(
					$this -> session -> data['customer_id'],  
					'- '.$amountPin,
					'Used pin for Deposite #'.$pd_query['pd_number'],
					'Deposite',
					'Used pin for Deposite #'.$pd_query['pd_number']
				);


					$this -> load -> model('account/pd');
					//10% cho admin
//=======================================================

					// $pdsendAdmin = $amount;
					// $inventory = $this -> model_account_pd ->getCustomerInventory();
					// $gd_query = $this -> model_account_pd -> createGDInventory($pdsendAdmin, $inventory['customer_id']);
					// $data['pd_id'] = $pd_query['pd_id'];
					// $data['gd_id'] = $gd_query['gd_id'];
					// $data['pd_id_customer'] = $this -> session -> data['customer_id'];
					// $data['gd_id_customer'] = $inventory['customer_id'];
					// $data['amount'] = $pdsendAdmin;
					// $transfer_id = $this -> model_account_pd -> createTransferList10percent($data, 1);
					// $this -> model_account_pd -> updateTransferList($transfer_id);
					// $this -> model_account_pd -> updateStatusGD($gd_query['gd_id'], 1);
					// $this -> model_account_pd -> updateTotalAmountPD($pd_query['pd_id'], $pdsendAdmin);
					// $this -> model_account_pd -> updateFilledGD($gd_query['gd_id'], $pdsendAdmin);

					$json['data_link']= $this->url->link('account/pd/');
					$json['ok'] = 1;
			}else{
				$json['ok'] = -1;
			}
					
			$this -> response -> setOutput(json_encode($json));
		}
	}
	
	public function transfer() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
		};

		!$this -> request -> get['token']  && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;
		
		$getPDCustomer = $this -> model_account_customer -> getPDByCustomerIDAndToken($this -> session -> data['customer_id'], $this -> request -> get['token']);

		$getPDCustomer['number'] === 0 && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
		$getPDCustomer = null;


		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		//get pd form transfer list
		$PdUser = $this -> model_account_customer -> getPD($this -> session -> data['customer_id']);

		$checkPdOfUser = null;
		foreach ($PdUser as $key => $value) {
			if($value['id'] === $this -> request -> get['token']){
				$checkPdOfUser = true;
				break;
			}
		}

		!$checkPdOfUser && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));

		$data['transferList'] = $this -> model_account_customer -> getPdFromTransferList($this -> request -> get['token']);

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd_transfer.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd_transfer.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd_transfer.tpl', $data));
		}
	}
	public function confirm() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/countdown/jquery.countdown.min.js');
			$self -> document -> addScript('catalog/view/javascript/pd/countdown.js');
			$self -> document -> addScript('catalog/view/javascript/pd/confirm.js');
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/pd');
		$data['lang'] = $language -> data;
		$data['getLanguage'] = $getLanguage;
		//get image


		!$this -> request -> get['token']  && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));

		$server = $this -> request -> server['HTTPS'] ? $server = $this -> config -> get('config_ssl') : $server = $this -> config -> get('config_url');
		$data['base'] = $server;
		$data['self'] = $this;

		$data['transferConfirm'] = $this -> model_account_customer -> getPDTranferByID($this -> request -> get['token']);

		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/pd_confirm.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/pd_confirm.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/pd_confirm.tpl', $data));
		}
	}

	public function confirmSubmit() {
		$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
		$json['ok'] = -1;

		if ($this -> customer -> isLogged() && $this -> request -> post['token']) {
			$this -> load -> model('account/customer');
	
			$filename = html_entity_decode($this->request->files['avatar']['name'], ENT_QUOTES, 'UTF-8');

			$filename = str_replace(' ', '_', $filename);
			if(!$filename || !$this->request->files){
				die();
			}

			$file = $this -> request -> post['token'].'_'.$filename . '.' . md5(mt_rand()) ;

	
			move_uploaded_file($this->request->files['avatar']['tmp_name'], DIR_UPLOAD . $file);

	
			//save image profile
			$server = $this -> request -> server['HTTPS'] ? $this -> config -> get('config_ssl') :  $this -> config -> get('config_url');
			$linkImage = $server . 'system/upload/'.$file;
		
			$this -> model_account_customer -> updateStatusPDTransferList($this -> request -> post['token'],$linkImage);
			// die('999');
			//get PDID
			$Customer_Tranferlist = $this -> model_account_customer -> getPDByTranferID($this -> request -> post['token']);

			$PDCustomer = $Customer_Tranferlist['pd_id'];
			//count PD status tu transfer list check xem con dong du lieu nao chua finish
			//neu chua finish thi chua cho finish
			$GDCustomer = $Customer_Tranferlist['gd_id'];

			$pd_detail = $this -> model_account_customer ->getPD_byid($PDCustomer);

			//count status
			$countNotPDFinsh = $this -> model_account_customer -> countStatusPDTransferList($PDCustomer);
			
			$countNotGDFinish = $this -> model_account_customer -> countStatusGDTransferList($GDCustomer);

			if(count($countNotPDFinsh) > 0 && intval($countNotPDFinsh['number']) === 0){
				$this -> model_account_customer -> updateStusPDActive($PDCustomer, 1);
				// $this -> model_account_customer -> updateStusPD($PDCustomer);
				$this -> model_account_customer -> updateCheck_R_WalletPD($PDCustomer);
				//=======================
				$customer = $this -> model_account_customer ->getCustomer($pd_detail['customer_id']);
				$partent = $this->model_account_customer->getCustomer($customer['p_node']);
			$checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($partent['customer_id']);

			if (intval($checkC_Wallet['number']) === 0) {
				if (!$this -> model_account_customer -> insertC_Wallet($partent['customer_id'])) {
					die();
				}
			}	

			$price = ($pd_detail['filled'] * 10) / 100;
			$this -> load -> model('account/auto');
			$this -> model_account_auto -> update_C_Wallet($price, $partent['customer_id']);
			$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' VND', "".$partent['username']." Sponsor 10% for F1 ".$customer['username']." finish PD" . $pd_detail['pd_number']." (".number_format($pd_detail['filled'])." VND)");
			
			$this -> updatePercent($pd_detail['customer_id'], $pd_detail['filled'], $pd_detail['pd_number']);
				//======================
			}
			if(count($countNotGDFinish) > 0 && intval($countNotGDFinish['number']) === 0){
				$this -> model_account_customer -> updateStusGD($GDCustomer);
			}
			$json['ok'] = 1;
		}
		$this -> response -> setOutput(json_encode($json));
	}
	public function updatePercent($customer_id, $amount, $pd_number)
    {
        $this->load->model('account/customer');
   
        $customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
        $partent = $this -> model_account_customer -> getCustomerCustom($customer['p_node']);
        
        $priceCurrent = $amount; //(*100 000 000)
        // $price        = ($amount * 0.05);
        
        // $this->model_account_auto->update_C_Wallet($price, $partent['customer_id']);
      
        // $this->model_account_customer->saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' VND', "Sponsor 5% for ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
        $levelCustomer = intval($customer['level']);
        $pNode_ID = $partent['customer_id'];
        //F1
        $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
        if (intval(count($customerGET)) > 0) {
        	$levelCustomer1 = intval($customerGET['level']);
        	// ==========================
        	if (intval($customerGET['level']) >= 2) {
							
				switch (intval($customerGET['level'])) {
					case 2 :
						if($levelCustomer < 2){
							$percent = 2 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 2){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 3 :	
						if($levelCustomer < 3){
							$percent = 3 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 3){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 4 :	
						if($levelCustomer < 4){
							$percent = 4 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 4){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 5 :	
						if($levelCustomer < 5){
							$percent = 5 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 5){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 6 :	
						if($levelCustomer < 6){
							$percent = 6 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 6){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
					case 7 :	
						if($levelCustomer < 7){
							$percent = 7 - $levelCustomer;
							$percentcommission =$percent/100;

							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						}elseif($levelCustomer >= 7){
							$percent = 0.5;
							$percentcommission =0.5/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F1) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
						} 
						break;
				}

			}
        	// =========================
        	// $percent           = 3;
         //    $percentcommission = $percent / 100;
         //    $this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
         //    $this->model_account_customer->saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent."% for ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");


            $pNode_ID = $customerGET['p_node'];
            //F2
            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);

	        if (intval(count($customerGET)) > 0) {
	        	$levelCustomer2 = intval($customerGET['level']);
	        	// ==========================
	        	if (intval($customerGET['level']) >= 2) {
								
					switch (intval($customerGET['level'])) {
						case 2 :
							if($levelCustomer1 < 2){
								$percent = 2 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 2){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']."  Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 3 :	
							if($levelCustomer1 < 3){
								$percent = 3 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 3){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 4 :	
							if($levelCustomer1 < 4){
								$percent = 4 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 4){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 5 :	
							if($levelCustomer1 < 5){
								$percent = 5 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 5){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 6 :	
							if($levelCustomer1 < 6){
								$percent = 6 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 6){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
						case 7 :	
							if($levelCustomer1 < 7){
								$percent = 7 - $levelCustomer1;
								$percentcommission =$percent/100;

								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							}elseif($levelCustomer1 >= 7){
								$percent = 0.5;
								$percentcommission =0.5/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F2) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
							} 
							break;
					}

				}
	        	// =========================
	            $pNode_ID = $customerGET['p_node'];
	            //F3
	            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
	            if (intval(count($customerGET)) > 0) {
	            	$levelCustomer3 = intval($customerGET['level']);
		        	// ==========================
		        	if (intval($customerGET['level']) >= 2) {
									
						switch (intval($customerGET['level'])) {
							case 2 :
								if($levelCustomer2 < 2){
									$percent = 2 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 2){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 3 :	
								if($levelCustomer2 < 3){
									$percent = 3 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 3){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 4 :	
								if($levelCustomer2 < 4){
									$percent = 4 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 4){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 5 :	
								if($levelCustomer2 < 5){
									$percent = 5 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 5){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 6 :	
								if($levelCustomer2 < 6){
									$percent = 6 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 6){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
							case 7 :	
								if($levelCustomer2 < 7){
									$percent = 7 - $levelCustomer2;
									$percentcommission =$percent/100;

									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}elseif($levelCustomer2 >= 7){
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F3) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								} 
								break;
						}

					}
		        	// =========================
		            $pNode_ID = $customerGET['p_node'];
		            //F4
		            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
		             if (intval(count($customerGET)) > 0) {
		             	$levelCustomer4 = intval($customerGET['level']);
			        	// ==========================
			        	if (intval($customerGET['level']) >= 2) {
										
							switch (intval($customerGET['level'])) {
								case 2 :
									if($levelCustomer3 < 2){
										$percent = 2 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 2){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 3 :	
									if($levelCustomer3 < 3){
										$percent = 3 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 3){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 4 :	
									if($levelCustomer3 < 4){
										$percent = 4 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 4){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 5 :	
									if($levelCustomer3 < 5){
										$percent = 5 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 5){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 6 :	
									if($levelCustomer3 < 6){
										$percent = 6 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 7 :	
									if($levelCustomer3 < 7){
										$percent = 7 - $levelCustomer3;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer3 >= 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F4) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
							}

						}
			        	// =========================
			            $pNode_ID = $customerGET['p_node'];
			            //F5
			            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
			            if (intval(count($customerGET)) > 0) {
			            	$levelCustomer5 = intval($customerGET['level']);
				        	// ==========================
				        	if (intval($customerGET['level']) >= 2) {
											
								switch (intval($customerGET['level'])) {
									case 2 :
										if($levelCustomer4 < 2){
											$percent = 2 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 2){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 3 :	
										if($levelCustomer4 < 3){
											$percent = 3 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 3){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 4 :	
										if($levelCustomer4 < 4){
											$percent = 4 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 4){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 5 :	
										if($levelCustomer4 < 5){
											$percent = 5 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 5){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 6 :	
										if($levelCustomer4 < 6){
											$percent = 6 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 6){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
									case 7 :	
										if($levelCustomer4 < 7){
											$percent = 7 - $levelCustomer4;
											$percentcommission =$percent/100;

											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										}elseif($levelCustomer4 >= 7){
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F5) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
										} 
										break;
								}

							}
				        	// =========================
				        	// =========================
		            $pNode_ID = $customerGET['p_node'];
		            //F6
		            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
		             if (intval(count($customerGET)) > 0) {
		             	$levelCustomer6 = intval($customerGET['level']);
			        	// ==========================
			        	if (intval($customerGET['level']) >= 2) {
										
							switch (intval($customerGET['level'])) {
								case 2 :
									if($levelCustomer5 < 2){
										$percent = 2 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 2){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 3 :	
									if($levelCustomer5 < 3){
										$percent = 3 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 3){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 4 :	
									if($levelCustomer5 < 4){
										$percent = 4 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 4){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 5 :	
									if($levelCustomer5 < 5){
										$percent = 5 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 5){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 6 :	
									if($levelCustomer5 < 6){
										$percent = 6 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 6){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
								case 7 :	
									if($levelCustomer5 < 7){
										$percent = 7 - $levelCustomer5;
										$percentcommission =$percent/100;

										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									}elseif($levelCustomer5 >= 7){
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F6) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									} 
									break;
							}

						}
			        	// =========================
				            $pNode_ID = $customerGET['p_node'];
				            //F7
				            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        	if (intval($customerGET['level']) >= 4) {
												
									$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']."  Sponsor ".$percent." % for (F7) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");

							}
					        	// =========================
				            $pNode_ID = $customerGET['p_node'];
				            //F8
				            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        	if (intval($customerGET['level']) >= 4) {
									$percent = 0.5;
									$percentcommission =0.5/100;
									$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
									$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F8) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								}
					        	// =========================
					           $pNode_ID = $customerGET['p_node'];
				            //F9
				            	$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				           		 if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        		if (intval($customerGET['level']) >= 4) {
					
										$percent = 0.5;
										$percentcommission =0.5/100;
										$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
										$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F9) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

									}
					        	// =========================
					             	$pNode_ID = $customerGET['p_node'];
				            //F10
				           			 $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            		if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        			if (intval($customerGET['level']) >= 4) {
					
											$percent = 0.5;
											$percentcommission =0.5/100;
											$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
											$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F10) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
									

										}
					        	// =========================
					            
					        			$pNode_ID = $customerGET['p_node'];
				            //F11
							            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
							            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        				if (intval($customerGET['level']) >= 4) {
					
												$percent = 0.5;
												$percentcommission =0.5/100;
												$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
												$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F11) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

											}
					        	// =========================
					             			$pNode_ID = $customerGET['p_node'];
				            //F12
								            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
								            if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        					if (intval($customerGET['level']) >= 4) {
					
													$percent = 0.5;
													$percentcommission =0.5/100;
													$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
													$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F12) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

												}
					        	// =========================
					             				$pNode_ID = $customerGET['p_node'];
				            //F13
				            					$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            					if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        						if (intval($customerGET['level']) >= 4) {
					
														$percent = 0.5;
														$percentcommission =0.5/100;
														$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
														$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F13) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
								

													}
					        	// =========================
					             					$pNode_ID = $customerGET['p_node'];
				            //F14
				            						$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
				            						if (intval(count($customerGET)) > 0) {
					        	// ==========================
											        	if (intval($customerGET['level']) >= 4) {
											
															$percent = 0.5;
															$percentcommission =0.5/100;
															$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
															$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F14) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
														

														}
											        	// =========================
											             $pNode_ID = $customerGET['p_node'];
				            //F15
											            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
											            if (intval(count($customerGET)) > 0) {
												        	// ==========================
												        	if (intval($customerGET['level']) >= 4) {
												
																	$percent = 0.5;
																	$percentcommission =0.5/100;
																	$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																	$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F15) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
															

															}
												        	// =========================
												             $pNode_ID = $customerGET['p_node'];
				            //F16
													            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
													            if (intval(count($customerGET)) > 0) {
														        	// ==========================
														        	if (intval($customerGET['level']) >= 4) {
														
																			$percent = 0.5;
																			$percentcommission =0.5/100;
																			$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																			$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F16) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																	

																	}
														        	// =========================
														            
														        	 $pNode_ID = $customerGET['p_node'];
				            //F17
														            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
														            if (intval(count($customerGET)) > 0) {
															        	// ==========================
															        	if (intval($customerGET['level']) >= 4) {
															
																				$percent = 0.5;
																				$percentcommission =0.5/100;
																				$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																				$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F17) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																		

																		}
															        	// =========================
															             $pNode_ID = $customerGET['p_node'];
				            //F18
															            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
															            if (intval(count($customerGET)) > 0) {
																        	// ==========================
																        	if (intval($customerGET['level']) >= 4) {
																
																					$percent = 0.5;
																					$percentcommission =0.5/100;
																					$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																					$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F18) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																			

																			}
																        	// =========================
																            
																        	$pNode_ID = $customerGET['p_node'];
				            //F19
																            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
																            if (intval(count($customerGET)) > 0) {
																	        	// ==========================
																	        	if (intval($customerGET['level']) >= 4) {
																	
																						$percent = 0.5;
																						$percentcommission =0.5/100;
																						$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																						$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F19) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																				

																				}
																	        	// =========================
																	             $pNode_ID = $customerGET['p_node'];
				            //F20
																	            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
																	            if (intval(count($customerGET)) > 0) {
																		        	// ==========================
																		        	if (intval($customerGET['level']) >= 4) {
																		
																							$percent = 0.5;
																							$percentcommission =0.5/100;
																							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
																							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' VND', "".$customerGET['username']." Sponsor ".$percent." % for (F20) - ".$customer['username']." finish PD" . $pd_number." (".number_format($amount)." VND)");
																					

																					}
					        	// =========================        
					        	
						      	  												}
					        	
						      	  											}
						      	  										}
					        	
						      	  									}
						      	  								}
					        	
						      	 							}
					        	
						      	  						}
					        	
						      	  					}
					        	
						      	  				}
								        	
									      	 }
						      	  		}
					        	
						      	  	}
					        	
						      	  }
						    	}
					        }
				        }
			        }
		        }
	        }	        
        }      
    }
    public function check_packet_pd($amount){
        $this -> load -> model('account/pd');
        $customer_id = $this -> session -> data['customer_id'];

        return $this -> model_account_pd -> check_packet_pd($customer_id, $amount);
    }
 	public function check_filled_pd(){
        $this -> load -> model('account/customer');
        $customer_id = $this -> session -> data['customer_id'];
        $filled = $this -> model_account_customer -> getPDfilled($customer_id);

        if (count($filled) == 0) {
        	return 0;
        }
        else{
        	 return intval($filled['filled']);
        }
       
    }

    public function check_payment(){
        $this -> load -> model('account/pd');
        
        return $this -> model_account_pd -> check_payment($this -> session ->data['customer_id'])['investment'];
    }
    public function pd_investment(){
    	
        /*check ---- sql*/
            $filter_wave2 = Array('"', "'");
            foreach($_POST as $key => $value)
            $_POST[$key] = $this -> replace_injection($_POST[$key], $filter_wave2);
            foreach($_GET as $key => $value)
            $_GET[$key] = $this -> replace_injection($_GET[$key], $filter_wave2);
        /*check ---- sql*/
        $this -> load -> model('account/customer');
        if(array_key_exists("invest",  $this -> request -> get) && $this -> customer -> isLogged()){
            $this -> load -> model('account/pd');
            
            $amount = doubleval($_GET['invest']);
           	$customer = $this -> model_account_customer ->getCustomer($this -> session -> data['customer_id']);
			if ($amount == 200):
				$amountPin = 1;	
			elseif ($amount == 400):
				$amountPin =2;
			elseif ($amount == 800):
				$amountPin =4;
			elseif ($amount == 1600):
				$amountPin =8;
			elseif ($amount == 3200):
				$amountPin =16;
			elseif ($amount == 6400):
				$amountPin =32;
			endif;	
		
			if(intval($customer['ping']) <= $amountPin){
				$json['pin'] = -1;
			}else{
				$json['pin'] = 1;
			}
           
	        if(intval($customer['ping']) > $amountPin){   

				if ($amount == 200):
					$max_profit= 40;
				$amountPin = 1;
				$subPin = intval($customer['ping']) - 1;
					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], $subPin );
				elseif ($amount == 400):
					$max_profit= 80;
					$amountPin =2;
					$subPin = intval($customer['ping']) - 2;
					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], $subPin );	
				elseif ($amount == 800):
					$max_profit= 160;
					$amountPin =4;
					$subPin = intval($customer['ping']) - 4;
					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], $subPin );	
				elseif ($amount == 1600):
					$max_profit= 320;
					$amountPin =8;
					$subPin = intval($customer['ping']) - 8;
					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], $subPin );	
				elseif ($amount == 3200):
					$max_profit= 640;
					$amountPin =16;
					$subPin = intval($customer['ping']) - 16;
					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], $subPin );		
				elseif ($amount == 6400):
					$max_profit= 1280;
					$amountPin = 32;
					$subPin = intval($customer['ping']) - 32;
					$this -> model_account_customer ->updatePin($this -> session -> data['customer_id'], $subPin );	
				endif;	

	            
	            //create PD
	           $pd_query = $this -> model_account_customer -> createPD($amount ,$max_profit);
														
				$id_history = $this->model_account_customer->saveHistoryPin(
						$this -> session -> data['customer_id'],  
						'- '.$amountPin,
						'Used pin for Deposite #'.$pd_query['pd_number'],
						'Deposite',
						'Used pin for Deposite #'.$pd_query['pd_number']
					);
				
				$url = "https://blockchain.info/tobtc?currency=USD&value=".$amount;
	            $amount_btc = file_get_contents($url);
	            $amount_btc = doubleval($amount_btc)*100000000;	
	            //create invoide
	            $secret = substr(hash_hmac('ripemd160', hexdec(crc32(md5(microtime()))), 'secret'), 0, 16);


	            $invoice_id = $this -> model_account_pd -> saveInvoice($this -> session -> data['customer_id'], $secret, $amount_btc, $pd_query['pd_id']);

	            $invoice_id_hash = hexdec(crc32(md5($invoice_id)));

	            $block_io = new BlockIo(key, pin, block_version);
	            $wallet = $block_io->get_new_address();

	            $my_wallet = $wallet -> data -> address;         
	            $call_back = HTTPS_SERVER.'callback.html?invoice=' . $invoice_id_hash . '_' . $secret;

	            $reatime = $block_io -> create_notification(
	                array(
	                    'url' => HTTPS_SERVER.'callback.html?invoice=' . $invoice_id_hash . '_' . $secret , 
	                    'type' => 'address', 
	                    'address' => $my_wallet
	                )
	            );

	            $this -> model_account_pd -> updateInaddressAndFree($invoice_id, $invoice_id_hash, $my_wallet, 0, $my_wallet, $call_back );
	            $json['pin'] = $amountPin;
	            $json['input_address'] = $my_wallet;
	            $json['package'] = $amount;
	            $json['amount_btc'] = $amount_btc;
	        }
            $this->response->setOutput(json_encode($json));
            
        }

    }

    public function callback() {

        $this -> load -> model('account/pd');
        $this -> load -> model('account/auto');
        $this -> load -> model('account/customer');
        /*check ---- sql*/
            $filter_wave2 = Array('"', "'");
            foreach($_POST as $key => $value)
            $_POST[$key] = $this -> replace_injection($_POST[$key], $filter_wave2);
            foreach($_GET as $key => $value)
            $_GET[$key] = $this -> replace_injection($_GET[$key], $filter_wave2);
        /*check ---- sql*/
        $invoice_id = array_key_exists('invoice', $this -> request -> get) ? $_GET['invoice'] : "Error";
        $tmp = explode('_', $invoice_id);
        if(count($tmp) === 0) die();
        $invoice_id_hash = $tmp[0]; 
        
        $secret = $tmp[1];

        //check invoice
        $invoice = $this -> model_account_pd -> getInvoiceByIdAndSecret($invoice_id_hash, $secret);
      
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

        $this -> model_account_pd -> updateReceived($received, $invoice_id_hash);
        $invoice = $this -> model_account_pd -> getInvoiceByIdAndSecret($invoice_id, $secret);

        $received = intval($invoice['received']);
        if ($received >= intval($invoice['amount'])) {      
            $this -> model_account_pd -> updateConfirm($invoice_id_hash, 3, '', '');
            //update PD
            // $this -> model_account_pd -> updateStatusPD($invoice['transfer_id'], 1);
            // $this -> model_account_pd -> updateStatusPD_fn($invoice['customer_id']);
            $this -> model_account_customer -> updateStusPDActive($invoice['transfer_id'], 1);
			$this -> model_account_customer -> updateCheck_R_WalletPD($invoice['transfer_id']);
            // $this -> auto_add_r_wallet(); 
            $PD = $this -> model_account_customer -> getPD_byid($invoice['transfer_id']);
             $customer = $this -> model_account_customer -> getCustomer($invoice['customer_id']);
            
            $mail = new Mail();
			$mail -> protocol = $this -> config -> get('config_mail_protocol');
			$mail -> parameter = $this -> config -> get('config_mail_parameter');
			$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
			$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
			$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
			$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

			$mail -> setTo('info@caligroup.info');
			$mail -> setFrom($this -> config -> get('config_email'));
			$mail -> setSender(html_entity_decode("Caligroup Global", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("Caligroup Global - Congratulations Your Registration is Confirmed!");
			$mail -> setHtml('
				<h1><span style="font-size:12px">ID: '.$customer['username'].'kích hoạt gói '.$PD['filled'].'USD !</span></h1>
				');
			$mail -> send();
            file_get_contents('https://caligroup.info/index.php?route=account/account/auto_add_r_wallet');
        }
        

    }
    public function auto_add_r_wallet(){
		$this -> load -> model('account/auto');
		$allPD = $this -> model_account_auto -> get_all_pd_add_r_wallet();
		$arrId='';
		if (!empty($allPD)) {
			foreach ($allPD as $value) {
				$arrId .= ','.$value['customer_id'];
			}

			$arrId = substr($arrId, 1);

			echo $arrId;echo '<br>';

			$this -> updateLevel($arrId);
			foreach ($allPD as $key => $value) {
				$this -> model_account_auto -> updatePDcheck_R_Wallet($value['id']);
				$this -> updatePercent($value['customer_id'], $value['filled'], $value['pd_number']);
			}
		}
		
	}
	public function packet_invoide(){
        $this -> load -> model('account/pd');
      

        $check_status = $this -> model_account_pd -> get_StatusPD($this -> request -> get ['invest']);
       
        if (intval($check_status['status']) === 1 || intval($check_status['status']) === 2) {
           $json['success'] = 1;
        }else
        {
          $package = $this -> model_account_pd -> get_invoide($this -> request -> get ['invest']);	
          if (count($package) > 0) {
          $json['input_address'] = $package['input_address'];



	        $json['amount'] =  $package['amount_inv'];
	        $json['pin'] = $package['amount_inv'] - $package['pd_amount'];
	        $json['package'] = $package['pd_amount'];
	        $json['received'] =  $package['received'];
          }
        
        }
        
        $this->response->setOutput(json_encode($json));
    }
    public function replace_injection($str, $filter)
    {
        foreach($filter as $key => $value)
        $str = str_replace($filter[$key], "", $str);
        return $str;
    }
}
