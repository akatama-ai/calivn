<?php
class ControllerAccountEmail extends Controller {

	public function index() {

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/email/email.js');
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//data render website
		//start load country model
		$this -> load -> model('customize/country');
		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}

		$data['base'] = $server;
		$data['self'] = $this;

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/email.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/email.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/email.tpl', $data));
		}
	}

	public function activesubmit(){
		if($this -> request -> get['token'] && $this -> request -> get['email'] && $this -> request -> get['password']) {
			//check account
			$this -> load -> model('account/customer');
			$customer = $this -> model_account_customer -> checkAccountActive($this -> request -> get['email'] , $this -> request -> get['password']);
			$json['req'] = $customer['number'];
			if(intval($json['req']) === 0){
				$this -> response -> setOutput(json_encode($json));
			}else{
				$customer = $this -> model_account_customer ->getCustomer($this -> request -> get['token']);
				$status = $customer['status'];

				if(intval($status) === 3){
					$this -> model_account_customer -> updateDateCustomer($this -> request -> get['token']);
					$this -> model_account_customer -> updateStatus($this -> request -> get['token'] , 4);
					$json['ok'] = '1';
				}else{
					$name = $customer["username"];
					$json['ok'] = 'Error user '.$name.' acctived';
				}

				$name = null;
				$customer = null;
				$this -> response -> setOutput(json_encode($json));
			}


		}
	}

	public function submit() {
		if ($this -> request -> get['Subject'] && $this -> request -> get['description']) {

			$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
			if ($json['login'] === 1) {

				$this -> load -> model('account/customer');
				$customer = $this -> model_account_customer -> getCustomer($this -> session -> data['customer_id']);
				$name = $customer['username'];

				$mail = new Mail();
				$mail -> protocol = $this -> config -> get('config_mail_protocol');
				$mail -> parameter = $this -> config -> get('config_mail_parameter');
				$mail -> smtp_hostname = $this -> config -> get('config_mail_smtp_hostname');
				$mail -> smtp_username = $this -> config -> get('config_mail_smtp_username');
				$mail -> smtp_password = html_entity_decode($this -> config -> get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail -> smtp_port = $this -> config -> get('config_mail_smtp_port');
				$mail -> smtp_timeout = $this -> config -> get('config_mail_smtp_timeout');
				$mail -> setTo($this -> config -> get('config_email_received'));
				$mail -> setFrom($this -> config -> get('config_email'));
				$mail -> setSender(html_entity_decode("S0007 Global", ENT_QUOTES, 'UTF-8'));
				$mail -> setSubject("S0007 Global - " . $this -> request -> get['Subject'] . "");
				$mail -> setText('
User [' . $name . ']  send email to admin!.
' . $this -> request -> get['description'] . '
				');
				$mail -> send();
				$this -> model_account_customer -> updateStatus($customer['customer_id'], 3);
				$json['ok'] = 1;
			}
			$this -> response -> setOutput(json_encode($json));
		}
	}


	public function active() {

		if(!$this -> request -> get['token']){
			$this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		}
		$this -> load -> model('account/customer');
		$customer = $this -> model_account_customer ->getCustomer($this -> request -> get['token']);
		$status = $customer['status'];
		$name = $customer["username"];
		$countFloor = 1;
		$customer_ml_id = $this -> request -> get['token'];
		$amount_E_Wallet = (500 * 2) / 100;
		

		$p_node_pd = $customer['p_node'];
		$customerHHPD = $this -> model_account_customer ->getCustomer($p_node_pd);
		$count_s_pd = $customerHHPD['count_s_pd'];

		

		if(intval($status) === 3){
		
			
			while (true) {
				$customer_ml = $this -> model_account_customer ->getCustomerMLCustomForEmailActive($customer_ml_id);

				$p_node = $customer_ml['p_binary'];
				if($countFloor === 11 || intval($p_node) === 0) break; 
		
				//tao vi cho p_binary 
				$checkE_Wallet = $this -> model_account_customer -> checkE_Wallet($p_node);
				if(intval($checkE_Wallet['number'])  === 0){
					if(!$this -> model_account_customer -> insertE_Wallet($p_node)){
						die('error_log_Server');
					}
				}
				
				//cap nhap vi cho p_binary
				// $customer_ml_p_node = $this -> model_account_customer ->getCustomerMLCustomForEmailActive($p_node);
				$this -> model_account_customer ->update_E_Wallet($amount_E_Wallet, $p_node , true);
				$wallet = 'I-Wallet';
				$text_amount = "+ $10";
				$system_decsription = "User ".$name." actived bitcoin";
				$this -> model_account_customer -> saveTranstionHistory($p_node, $wallet, $text_amount, $system_decsription);
				$customer_ml_id = $p_node;
				
				$countFloor++;	
			}

			//cong don count_pd_level
			$countTMPFORPD =  $this -> model_account_customer -> countActiveCustomer($p_node_pd);
			$countTMPFORPD = intval($countTMPFORPD['number']);
		
			if($countTMPFORPD < 2){
				$this -> model_account_customer -> updateCount_pd_level($customer['customer_id'], 10);
			}
			if($countTMPFORPD >= 2 && $countTMPFORPD < 5){
				$this -> model_account_customer -> updateCount_pd_level($customer['customer_id'], 15);
			}
			if($countTMPFORPD >= 5){
				$this -> model_account_customer -> updateCount_pd_level($customer['customer_id'], 20);
			}
			
			//cong don den khi login thi co PD
			$this -> model_account_customer ->updateCount_s_pd($p_node_pd);

			$this -> model_account_customer -> updateDateCustomer($this -> request -> get['token']);
			$this -> model_account_customer -> updateStatus($this -> request -> get['token'] , 4);
			// if(intval($count_s_pd) < 2 && intval($customerHHPD['status']) === 4){
			// 	$this -> model_account_customer ->updateCount_s_pd($p_node_pd);
			// }
			// $customerHHPD_ = $this -> model_account_customer ->getCustomer($p_node_pd);
			// $count_s_pd_ = $customerHHPD_['count_s_pd'];
			
			// if(intval($count_s_pd_) === 2 && intval($customerHHPD['status']) === 4){
			// 	//create PD
			// 	$this -> model_account_customer -> createPD($p_node_pd);
			// 	$mail = new Mail();
			// 	$mail->protocol = $this->config->get('config_mail_protocol');
			// 	$mail->parameter = $this->config->get('config_mail_parameter');
			// 	$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			// 	$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			// 	$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			// 	$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			// 	$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			// 	$mail->setTo($customer['email']);
			// 	$mail->setFrom($this->config->get('config_email'));
			// 	$mail->setSender(html_entity_decode("S0007 Global Remind the PD is order", ENT_QUOTES, 'UTF-8'));
			// 	$mail->setSubject('S0007 Global - Remind the PD is order');
			// 	$mail->setHTML('
			// 		<p>Congratulation on your turn to help community, you need to proud of doing such a good job. </p>
			// 		<p> You need to aware to confirm PD-s order so that we know you are ready to go with S0007 Club. </p>
			// 		<p> If you do not confirm PD is order, you are break the rule of this Club and we force to block your ID permanently </p>
			// 	');
			// 	$mail->send();

			// 	$this -> model_account_customer ->updateCount_s_pd_Custom($p_node_pd);
			// }
			die('Update '.$name.' Successfull');
		}else{
			
			die('Error user '.$name.' acctived');
		}
	}



}
