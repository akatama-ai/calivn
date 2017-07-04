<?php
class ControllerAccountAccount extends Controller {
	
	public function index() {
		$this -> response -> redirect(HTTPS_SERVER . 'login');
		if (!$this -> customer -> isLogged()) {
			$this -> session -> data['redirect'] = $this -> url -> link('account/account', '', 'SSL');

			$this -> response -> redirect(HTTPS_SERVER . 'login');
		}

		$this -> load -> language('account/account');

		$this -> document -> setTitle($this -> language -> get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array('text' => $this -> language -> get('text_home'), 'href' => $this -> url -> link('common/home'));

		$data['breadcrumbs'][] = array('text' => $this -> language -> get('text_account'), 'href' => $this -> url -> link('account/account', '', 'SSL'));

		if (isset($this -> session -> data['success'])) {
			$data['success'] = $this -> session -> data['success'];

			unset($this -> session -> data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] = $this -> language -> get('heading_title');

		$data['text_my_account'] = $this -> language -> get('text_my_account');
		$data['text_my_orders'] = $this -> language -> get('text_my_orders');
		$data['text_my_newsletter'] = $this -> language -> get('text_my_newsletter');
		$data['text_edit'] = $this -> language -> get('text_edit');
		$data['text_password'] = $this -> language -> get('text_password');
		$data['text_address'] = $this -> language -> get('text_address');
		$data['text_wishlist'] = $this -> language -> get('text_wishlist');
		$data['text_order'] = $this -> language -> get('text_order');
		$data['text_download'] = $this -> language -> get('text_download');
		$data['text_reward'] = $this -> language -> get('text_reward');
		$data['text_return'] = $this -> language -> get('text_return');
		$data['text_transaction'] = $this -> language -> get('text_transaction');
		$data['text_newsletter'] = $this -> language -> get('text_newsletter');
		$data['text_recurring'] = $this -> language -> get('text_recurring');

		$data['edit'] = $this -> url -> link('account/edit', '', 'SSL');
		$data['password'] = $this -> url -> link('account/password', '', 'SSL');
		$data['address'] = $this -> url -> link('account/address', '', 'SSL');
		$data['wishlist'] = $this -> url -> link('account/wishlist');
		$data['order'] = $this -> url -> link('account/order', '', 'SSL');
		$data['download'] = $this -> url -> link('account/download', '', 'SSL');
		$data['return'] = $this -> url -> link('account/return', '', 'SSL');
		$data['transaction'] = $this -> url -> link('account/transaction', '', 'SSL');
		$data['newsletter'] = $this -> url -> link('account/newsletter', '', 'SSL');
		$data['recurring'] = $this -> url -> link('account/recurring', '', 'SSL');

		if ($this -> config -> get('reward_status')) {
			$data['reward'] = $this -> url -> link('account/reward', '', 'SSL');
		} else {
			$data['reward'] = '';
		}

		$data['column_left'] = $this -> load -> controller('common/column_left');
		$data['column_right'] = $this -> load -> controller('common/column_right');
		$data['content_top'] = $this -> load -> controller('common/content_top');
		$data['content_bottom'] = $this -> load -> controller('common/content_bottom');
		$data['footer'] = $this -> load -> controller('common/footer');
		$data['header'] = $this -> load -> controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/account.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/account.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/account.tpl', $data));
		}
	}
	public function sendmail($mess){
		
		$mail = new Mail();
			$mail -> protocol = $this -> config -> get('config_mail_protocol');
			$mail -> parameter = $this -> config -> get('config_mail_parameter');
			$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
			$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
			$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
			$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');

			$mail -> setTo('appnanas0001@gmail.com');
			$mail -> setFrom($this -> config -> get('config_email'));
			$mail -> setSender(html_entity_decode("Caligroup ".$mess."", ENT_QUOTES, 'UTF-8'));
			$mail -> setSubject("HN Global - ".$mess."!");
			$mail -> setHtml('
				<h1><span style="font-size:12px"Caligroup '.$mess.'!</span></h1>
				');
			$mail -> send();
	}
	public function country() {
		$json = array();

		$this -> load -> model('localisation/country');

		$country_info = $this -> model_localisation_country -> getCountry($this -> request -> get['country_id']);

		if ($country_info) {
			$this -> load -> model('localisation/zone');

			$json = array('country_id' => $country_info['country_id'], 'name' => $country_info['name'], 'iso_code_2' => $country_info['iso_code_2'], 'iso_code_3' => $country_info['iso_code_3'], 'address_format' => $country_info['address_format'], 'postcode_required' => $country_info['postcode_required'], 'zone' => $this -> model_localisation_zone -> getZonesByCountryId($this -> request -> get['country_id']), 'status' => $country_info['status']);
		}

		$this -> response -> addHeader('Content-Type: application/json');
		$this -> response -> setOutput(json_encode($json));
	}

	public function autoRunFirstMonth() {
		require ('admin/model/sale/customer.php');
		$adminCustomerModel = new ModelSaleCustomer($this -> registry);
		//H?i phí d? ki?n(ð? tháng 30 ngày) ch?y t?ng ngày(ðóng h?i phí trý?c)
		$results_HPDuKien = $adminCustomerModel -> makeHPDuKien();

	}

	public function autoRunEveryDate() {
		require ('admin/model/sale/customer.php');
		$adminCustomerModel = new ModelSaleCustomer($this -> registry);
		//Off h?i viên vào ngày 10 ch?y t?ng ngày
		$results_checkOffUser = $adminCustomerModel -> checkOffUser();
		// Off HV quá 12 tháng(ð? tháng 30 ngày)ch?y t?ng ngày
		$results_OffUser12Thang = $adminCustomerModel -> OffUser12Thang();

	}
	public function autoGD(){
		$this -> load -> model('account/auto');
		$this -> load -> model('customize/register');
		//get first GD
		$loop = true;
		// $count = 0;
		
		while ($loop) {
			//$gdList = $this -> model_account_auto -> getGD7Before();

			$gdList = $this -> model_account_auto -> getGD7Befores();
			if (count($gdList) === 0) {			
				$loop = false;
				break;
			}
			if(count($gdList) > 0){
				//get customer in inventory
				$gdResiver = floatval($gdList['amount'] - $gdList['filled']);

				$inventory = $this -> model_account_auto ->getCustomerInventoryGD();

				$inventoryID = $inventory['customer_id'];
				$pdList = $this -> model_account_auto -> createPDInventory($gdResiver, $inventoryID);

				// continue;	
				$data['pd_id'] = $pdList['pd_id'];
				$data['gd_id'] = $gdList['id'];
				$data['pd_id_customer'] = $inventoryID;
				$data['gd_id_customer'] = $gdList['customer_id'];
				$data['amount'] = $gdResiver;
				$this -> model_account_auto -> createTransferList($data);
				$this -> model_account_auto -> updateStatusPD($pdList['pd_id'], 1);
				$this -> model_account_auto -> updateStatusGD($gdList['id'], 1);
				$this -> model_account_auto -> updateAmountPD($pdList['pd_id'], $gdResiver);
				$this -> model_account_auto -> updateFilledGD($gdList['id'], $gdResiver);
			}
			
			
			
		}
	}
	public function autoPD(){
		$this -> load -> model('account/auto');
		$this -> load -> model('customize/register');
		$this -> load -> model('account/customer');
		//get first GD
		$loop = true;
		// $count = 0;
		// 
		$mess= 'Auto PD';
		$this-> sendmail($mess);
		while ($loop) {
			//$gdList = $this -> model_account_auto -> getGD7Before();

			$pdList = $this -> model_account_auto -> getPD7Befores();
			//echo "<pre>"; print_r($pdList); echo "</pre>"; die();
			
			if (count($pdList) === 0) {			
				$loop = false;
				break;
			}
			if(count($pdList) > 0){
				//get customer in inventory
				$inventory = $this -> model_account_auto ->getCustomerInventory();
				$pdSend = floatval($pdList['filled'] - $pdList['amount']);
				$inventoryID = $inventory['customer_id'];
				//create GD cho inventory
				//$this -> updateLevel();
				//$this -> updatePercent($pdList['customer_id'], $pdList['filled'], $pdList['pd_number']);
				$gdList = $this -> model_account_auto -> createGDInventory($pdSend, $inventoryID);
				echo $pdList['customer_id'].'<br />';
				// continue;	
				$data['pd_id'] = $pdList['id'];
				$data['gd_id'] = $gdList['gd_id'];
				$data['pd_id_customer'] = $pdList['customer_id'];
				$data['gd_id_customer'] = $inventoryID;
				$data['amount'] = $pdSend;
				$this -> model_account_auto -> createTransferList($data);
				$this -> model_account_auto -> updateStatusPD($pdList['id'], 1);
				$this -> model_account_auto -> updateStatusGD($gdList['gd_id'], 2);
				$this -> model_account_auto -> updateAmountPD($pdList['id'], $pdSend);
				$this -> model_account_auto -> updateFilledGD($gdList['gd_id'], $pdSend);
				//$this -> model_account_auto -> updateStusPDActive($pdList['id'], 1);
				// $this -> model_account_customer -> updateStusPD($PDCustomer);
				$this -> model_account_customer -> updateCheck_R_WalletPD($pdList['id']);
			}
			
			
			
		}
	}
	public function autofnPD() {
		$this -> load -> model('account/auto');
		$this -> load -> model('account/pd');
		$this -> load -> model('account/customer');
		$allPD = $this -> model_account_auto -> getDayFnPD();
		// echo "<pre>"; print_r($allPD); echo "</pre>"; die();
		$tmp = null;
		foreach ($allPD as $key => $value) {
			$customer = $this -> model_account_customer ->getCustomer($value['customer_id']);
			if ($tmp != $value['customer_id']) {
				$this -> model_account_auto -> updateStatusPD($value['id'], 2);
				$checkR_Wallet = $this -> model_account_customer -> checkR_Wallet($value['customer_id']);
				if (intval($checkR_Wallet['number']) === 0) {
					if (!$this -> model_account_customer -> insertR_Wallet($value['customer_id'])) {
						die();
					}
				}
				
				
				// $this -> model_account_customer -> saveTranstionHistory($value['customer_id'], 'R-wallet', '- ' . number_format($value['max_profit']) . ' USD', "finish Deposite" . $value['pd_number']);
				//$this -> model_account_auto -> updatePDcheck_R_Wallet($value['id']);
				//Create PD
				$amountpd	= $value['filled'];
				
				switch (intval($amountpd)) {
					case 200:
						$max_profit= 30;
						$amountPin = 1;
						break;
					case 400:
						$max_profit= 60;
						$amountPin =2;
					
						break;
					case 800:
						$max_profit= 120;
						$amountPin =4;
			
						break;
					case 1600:
						$max_profit= 240;
						$amountPin =8;
					
						break;
					case 3200:
						$max_profit= 480;
						$amountPin =16;
				
						break;
					case 6400:
						$max_profit= 960;
						$amountPin = 32;
					
						break;
					default:
						$amountPin =8;
						break;
				}
				$pd_query = $this -> model_account_auto -> createPD($value['customer_id'],$amountpd ,$max_profit);
				$this -> model_account_auto -> updateStatusPD($pd_query['pd_id'],1);
				$this -> model_account_auto -> updateStusPDActive($pd_query['pd_id'],1);

				// $this -> model_account_customer -> saveTranstionHistory($value['customer_id'], 'R-wallet', '- ' . number_format($amountgd) . ' USD', "Withdrawal from R - Wallet");
				//Create GD
				// $gdList = $this -> model_account_auto -> createGD($amountgd, $value['customer_id']);
				$this -> model_account_auto -> update_R_Wallet($max_profit, $value['customer_id']);
				$this -> updatePercent($value['customer_id'], $value['filled'], $value['pd_number']);
				//$this -> get_c_wallet($value['customer_id']);
			}

		}
		$mess= 'Auto Fn PD';
		// $this-> sendmail($mess);
	}
	public function get_c_wallet($customer_id){
		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');
		$c_wallet = $this -> model_account_auto -> get_c_wallet($customer_id);
		
		if (!empty($c_wallet)) {
			$amount = $c_wallet['amount'];
			if (intval($amount) < 3000) {
				$amount10Percent = ($amount*0.1);
				$percent = 10;
			}
			if (intval($amount) >= 3000) {
				$amount10Percent = ($amount*0.11);
				$percent = 11;
			}
			if (intval($amount) >= 6000) {
				$amount10Percent = ($amount*0.12);
				$percent = 12;
			}
			if (intval($amount) >= 9000) {
				$amount10Percent = ($amount*0.13);
				$percent = 13;
			}
			
			// $this -> model_account_customer -> saveTranstionHistory($customer_id, 'Insurance Fund', '+ ' . number_format($amount10Percent) . ' USD', "Fee ".$percent."% Withdrawal from ".number_format($amount)." USD");
			// $this -> model_account_auto -> update_insurance_fund($amount10Percent);
			// $amountgd = $amount - $amount10Percent;
			// // $amountgd = $amount - $amount10Percent;
			// $gdList = $this -> model_account_auto -> createGD($amount, $customer_id);
			$this -> model_account_auto -> update_R_Wallet($amount, $customer_id);
			// $this-> model_account_auto -> update_c_wallet_($customer_id);
			// continue;	
			
		}
			
	}

	public function get_c_wallets(){
		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');
		$c_wallet = $this -> model_account_auto -> get_c_wallets();
		foreach ($c_wallet as $key => $value) {
			$amount = $value['amount'];
			$amount10Percent = ($amount*0.1);
			$this -> model_account_auto -> update_insurance_fund($amount10Percent);
			$amountgd = $amount - $amount10Percent;
			$gdList = $this -> model_account_auto -> createGD($amountgd, $value['customer_id']);
			$this-> model_account_auto -> update_c_wallet_($value['customer_id']);
			$inventory = $this -> model_account_auto ->getCustomerInventoryGD();
			$inventoryID = $inventory['customer_id'];
			$pdList = $this -> model_account_auto -> createPDInventory($amountgd, $inventoryID);
			// continue;	
			$data['pd_id'] = $pdList['pd_id'];
			$data['gd_id'] = $gdList['gd_id'];
			$data['pd_id_customer'] = $inventoryID;
			$data['gd_id_customer'] = $value['customer_id'];
			$data['amount'] = $amountgd;
			$this -> model_account_auto -> createTransferList($data);
			$this -> model_account_auto -> updateStatusPD($pdList['pd_id'], 2);
			$this -> model_account_auto -> updateStatusGD($gdList['gd_id'], 1);
			$this -> model_account_auto -> updateAmountPD($pdList['pd_id'], $amountgd);
			$this -> model_account_auto -> updateFilledGD($gdList['gd_id'], $amountgd);
		}
		
			
	}

	public function ActivePD()
	{
		
	}

	//Ham cap nhAt Hoa hong
	public function auto_add_r_wallet(){
		$this -> load -> model('account/auto');
		$allPD = $this -> model_account_auto -> get_all_pd_add_r_wallet();
		$arrId='';
		if (!empty($allPD)) {
			foreach ($allPD as $key => $value) {
				$this -> model_account_auto -> updatePDcheck_R_Wallet($value['id']);
				$this -> updateDirectCommission($value['customer_id'], $value['filled'], $value['pd_number']);
				// $this -> updatePercent($value['customer_id'], $value['filled'], $value['pd_number']);
			}
			// $mess= 'Auto R Wallet';
			// $this-> sendmail($mess);
		}
		
	}
	public function updateDirectCommission($customer_id, $amount, $pd_number)
    {
        $this->load->model('account/customer');
   		$this->load->model('account/auto');
        $customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
        $partent = $this -> model_account_customer -> getCustomerCustom($customer['p_node']);
        if (!empty($partent)) {
	        $checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($partent['customer_id']);

				if (intval($checkC_Wallet['number']) === 0) {
					if (!$this -> model_account_customer -> insertC_Wallet($partent['customer_id'])) {
						die();
					}
				}

				$price = ($amount * 8) / 100;
				if (intval($customer['cycle']) == 0) {
					$this -> model_account_auto -> update_C_Wallet($price, $partent['customer_id']);
					$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' USD', "Sponsor 8% from F1 <span style='color:#1200ff;'> ".$customer['username']."</span> finish package #" . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
				}

				$partent2 = $this -> model_account_customer -> getCustomerCustom($partent['p_node']);
				if (!empty($partent2)) {
					$checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($partent2['customer_id']);

					if (intval($checkC_Wallet['number']) === 0) {
						if (!$this -> model_account_customer -> insertC_Wallet($partent2['customer_id'])) {
							die();
						}
					}

					$price = ($amount * 4) / 100;
					if (intval($customer['cycle']) == 0) {
						$this -> model_account_auto -> update_C_Wallet($price, $partent2['customer_id']);
						$this -> model_account_customer -> saveTranstionHistory($partent2['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' USD', "Sponsor 4% from F2 <span style='color:#1200ff;'> ".$customer['username']."</span> finish  package #" . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
					}
				}
				

				$this -> model_account_auto -> updateCycleAddCustomer($customer['customer_id']);
        }
    }
	//Ham cap nhat level
	public function auto_update_level(){
		$this-> load ->model('account/auto');
		$all_customer = $this -> model_account_auto -> get_all_customer_update_level();
		$arrId ='';
		foreach ($all_customer as $key => $value) {
			$arrId .= ','.$value['customer_id'];
			
		}
		
		
		$arrId = substr($arrId, 1);
		

	$this -> updateLevel($arrId);
	}
	public function autofnGD(){
		$this -> load -> model('account/auto');
		$this -> model_account_auto -> auto_find_gd_update_status_finish();
	}

	public function auto_check_no_send_pd(){
		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');
		$allPD = $this -> model_account_auto -> find_no_send_pd();

		foreach ($allPD as $key => $value) {
			$this -> model_account_auto -> delete_pd($value['pd_id']);
			$this -> model_account_auto -> delete_gd($value['gd_id']);
			$this -> model_account_auto -> delete_transfer($value['id']);
			$this -> model_account_customer -> saveTranstionHistory($value['customer_id'], 'Warning', 'Warning', "Your transaction has been removed because you did not pay when time-expired");
		}
	}
	
	public function updateLevel($customer_id){	
		$this -> load -> model('account/customer');
		$this -> load -> model('account/auto');
		$customer_level = $this -> model_account_auto -> get_customer_update_level($customer_id);

		foreach ($customer_level as $key => $value) {
			$customer_id = $value['customer_id'];
			$customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
			//level 0 
			if(intval($customer['level']) === 1){
				$rows =  $this -> model_account_customer ->getPNode($customer_id);
				if(count($rows) >= 4){
					//uupdate level 2;
					$this -> model_account_customer ->updateLevel($customer_id, 2);
				}
			}
			//level 1
			if(intval($customer['level']) === 2){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 2);
				if(count($getLevel) >= 3){
					$this -> model_account_customer ->updateLevel($customer_id, 3);
				}
			}
			//level 2
			if(intval($customer['level']) === 3){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 3);
				if(count($getLevel) >= 3){
					$this -> model_account_customer ->updateLevel($customer_id, 4);
				}
			}
			//level 3
			if(intval($customer['level']) === 4){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 4);
				if(count($getLevel) >= 3){
					$this -> model_account_customer ->updateLevel($customer_id, 5);
				}
			}
			//level 4
			if(intval($customer['level']) === 5){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 5);
				if(count($getLevel) >= 3){
					$this -> model_account_customer ->updateLevel($customer_id, 6);
				}
			}
			//level 5
			if(intval($customer['level']) === 6){
				$getLevel = $this -> model_account_customer ->getLevel($customer_id, 6);
				if(count($getLevel) >= 3){
					$this -> model_account_customer ->updateLevel($customer_id, 7);
				}
			}
		}
	}

	public function importInventory(){
		$this->load->model('customize/register');
		// die('11');
		$customer = $this->model_customize_register -> getTableCustomerTmp();

		foreach ($customer as $key => $value) {
			$data['p_node'] = -1;
			$data['email'] = 'aiclinksg@gmail.com';
			$data['username'] = $value['username'];
			$data['telephone'] = $value['telephone'];
			$data['salt'] = '5c5d0d927';
			$data['password'] = 'cbbf11c085ccd5191b1d9946fc7fa5800a446649';
			$data['cmnd'] = '345643124';
			$data['country_id'] = '230';
			
			$data['account_holder'] = 'Nguyen Xuan Phuong Nam';
		    $data['account_number'] = '0071005252695';
		    $data['bank_name'] ='Vietcombank';
		    $data['branch_bank'] = 'Tan Binh';
			$data['transaction_password'] = 'cbbf11c085ccd5191b1d9946fc7fa5800a446649';
			$p_node = $this->model_customize_register -> addCustomerInventory($data);

		}

		die('ok');

	}

	public function autoAddCustomer(){
		
		$this->load->model('customize/register');
		$i=1;
		while ( $i <= 50) {
			$data = array(
		    'username' => 'iops'.$i,
		    'email' => 'iops@gmail.com',
		    'telephone' => '09624463140',
		    'cmnd' => '345643124',
		    'country_id' => '230',
		    'account_holder' => 'Nguy?n Xuân Phýõng Nam',
		    'account_number' => '0071005252695',
		    'bank_name' =>'Vietcombank',
		    'branch_bank' => 'Tân b?nh'

		);
			$this-> model_customize_register -> addCustomerCustom($data, 0);
			$i++;
		}
		die('OK');
	}

	

	public function autoNode(){
		$this -> load -> model('account/auto');
		$this -> load -> model('account/customer');
		$CustomerOfNode = $this -> model_account_customer -> getCustomOfNode(55);
		$CustomerOfNode=explode(',', $CustomerOfNode);
		
		unset($CustomerOfNode[0]);
		//echo "<pre>"; print_r($CustomerOfNode); echo "</pre>"; die();
		$arrUsername='';
		foreach ($CustomerOfNode as $value) {
			$arrUsername .= ','.$value;
		}
		$arrUsername = substr($arrUsername, 1);
		echo "<pre>"; print_r($arrUsername); echo "</pre>"; die();
	 	 // $this -> model_account_customer -> DeleteCustomer($arrUsername);
	 	 // $this -> model_account_customer -> DeleteCustomerML($arrUsername);
			
		
		
	}
	
	//hoa hong
	public function updatePercent($customer_id, $amount, $pd_number)
    {
        $this->load->model('account/customer');
   		$this->load->model('account/auto');
        $customer = $this -> model_account_customer -> getCustomerCustom($customer_id);
        $partent = $this -> model_account_customer -> getCustomerCustom($customer['p_node']);
    //     if (!empty($partent)) {
	   //      $checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($partent['customer_id']);

				// if (intval($checkC_Wallet['number']) === 0) {
				// 	if (!$this -> model_account_customer -> insertC_Wallet($partent['customer_id'])) {
				// 		die();
				// 	}
				// }

				// $price = ($amount * 8) / 100;
				// if (intval($customer['cycle']) == 0) {
				// 	$this -> model_account_auto -> update_C_Wallet($price, $partent['customer_id']);
				// 	$this -> model_account_customer -> saveTranstionHistory($partent['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' USD', "Sponsor 8% from F1 <span style='color:#1200ff;'> ".$customer['username']."</span> finish  " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
				// }

				// $partent2 = $this -> model_account_customer -> getCustomerCustom($partent['p_node']);
				// $checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($partent2['customer_id']);

				// if (intval($checkC_Wallet['number']) === 0) {
				// 	if (!$this -> model_account_customer -> insertC_Wallet($partent2['customer_id'])) {
				// 		die();
				// 	}
				// }

				// $price = ($amount * 4) / 100;
				// if (intval($customer['cycle']) == 0) {
				// 	$this -> model_account_auto -> update_C_Wallet($price, $partent2['customer_id']);
				// 	$this -> model_account_customer -> saveTranstionHistory($partent2['customer_id'], 'C-wallet', '+ ' . number_format($price) . ' USD', "Sponsor 4% from F2 <span style='color:#1200ff;'> ".$customer['username']."</span> finish  " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
				// }

				// $this -> model_account_auto -> updateCycleAddCustomer($customer['customer_id']);
    //     }

        

		
		
        
        $priceCurrent = $amount; 
        $levelCustomer = intval($customer['level']);
        $pNode_ID = $partent['customer_id'];
        //F1
        $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
    	if (intval(count($customerGET)) > 0) {
       
        	
        	$percent = 1;
            $percentcommission = $percent / 100;
            $this->model_account_auto->update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
            $this->model_account_customer->saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' USD', " <span style='color:#1200ff;'> ".$customerGET['username']."</span> Earn ".$percent."% commission for (F1) <span style='color:#1200ff;'> ".$customer['username']."</span> finish Deposite " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");


            $pNode_ID = $customerGET['p_node'];
            //F2
            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);

        	if (intval(count($customerGET)) > 0) {
	        	// ==========================								
				$percent = 1;
				$percentcommission =$percent/100;
				$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
				$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' USD', " <span style='color:#1200ff;'> ".$customerGET['username']."</span> Earn ".$percent." % commission  for (F2) - <span style='color:#1200ff;'> ".$customer['username']."</span> finish Deposite " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");

	        	// =========================
	            $pNode_ID = $customerGET['p_node'];
	            //F3
	            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
            	if (intval(count($customerGET)) > 0) {
	        	// ==========================
					$percent = 1;
					$percentcommission =$percent/100;

					$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
					$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' USD', " <span style='color:#1200ff;'> ".$customerGET['username']."</span> Earn ".$percent." % commission  for (F3) - <span style='color:#1200ff;'> ".$customer['username']."</span> finish Deposite " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
		        	// =========================
		            $pNode_ID = $customerGET['p_node'];
		            //F4
		            $customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
	            	if (intval(count($customerGET)) > 0) {
		        	// ==========================										
						$percent = 1;
						$percentcommission =$percent/100;
						$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
						$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' USD', " <span style='color:#1200ff;'> ".$customerGET['username']."</span> Earn ".$percent." % commission  for (F4) - <span style='color:#1200ff;'> ".$customer['username']."</span> finish Deposite " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
			        	// =========================
			        	// =========================
		            	$pNode_ID = $customerGET['p_node'];
		           	 	//F5
		            	$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
			            if (intval(count($customerGET)) > 0) {
				        	// ==========================
							$percent = 1;
							$percentcommission =$percent/100;
							$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
							$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' USD', " <span style='color:#1200ff;'> ".$customerGET['username']."</span> Earn ".$percent." % commission  for (F5) - <span style='color:#1200ff;'> ".$customer['username']."</span> finish Deposite " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
		        	// =========================
			           		$pNode_ID = $customerGET['p_node'];
			            //F6
			            	$customerGET = $this->model_account_customer->getCustomerCustom($pNode_ID);
			            	if (intval(count($customerGET)) > 0) {
					        	// ==========================
					        	$percent = 1;
								$percentcommission =$percent/100;
								$this -> model_account_auto -> update_C_Wallet($priceCurrent * $percentcommission, $customerGET['customer_id']);
								$this -> model_account_customer -> saveTranstionHistory($customerGET['customer_id'], 'C-wallet', '+ ' . number_format($priceCurrent * $percentcommission) . ' USD', " <span style='color:#1200ff;'> ".$customerGET['username']."</span> Earn ".$percent." % commission  for (F3) - <span style='color:#1200ff;'> ".$customer['username']."</span> finish Deposite " . $pd_number."<span style='color:#f00;'>  (".number_format($amount)." USD)</span>");
					        	// =========================
					    	}
				        }
			        }
		        }
	        }
        }
   	}	        

	//end hoa hong
}
