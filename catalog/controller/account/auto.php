<?php
class ControllerAccountAuto extends Controller {

	//Tu dong tao GD $ vi R
	public function auto_create_gd(){
		$this->load->model('account/autopdgd');
		$arrId='2161,2177,2162,2192,2179,2163,2173,2178,2180,2193';
		$date_added ='2016-08-24 02:50:30';
		$all = $this->model_account_autopdgd->get_r_wallet();
 //echo "<pre>"; print_r($all); echo "</pre>"; die();
		foreach ($all as $key => $value) {
			$this->model_account_autopdgd->createGD($value['amount'], $value['customer_id'],$date_added);
			$this->model_account_autopdgd->update_r_wallet($value['customer_id']);
		}
		
	}


	// Tu dong tao GD $ vi C
	public function auto_create_gd_c_wallet(){
		$this->load->model('account/autopdgd');
		$arrId='2161,2177,2162,2192,2179,2163,2173,2178,2180,2193';
		
		$date_added ='2016-08-24 02:50:30';
		$all = $this->model_account_autopdgd->get_c_wallet();
//echo "<pre>"; print_r($all); echo "</pre>"; die();
		// $allC_Wallet = $this->model_account_autopdgd->get_all_c_wallet();
		// $arrId_C='';
		// foreach ($allC_Wallet as $value) {
		// 	$arrId_C .= ','.$value['customer_id'];
		// }
		// $arrId_C = substr($arrId_C, 1);
		//echo "<pre>"; print_r($allC_Wallet); echo "</pre>"; die();
		// 2,2161,2162,2163,2164,2165,2166,2167,2168,2169
		foreach ($all as $key => $value) {
			$amount = $value['amount'];
			$amount10Percent = ($amount*0.1);
			$this -> model_account_autopdgd -> update_insurance_fund($amount10Percent);
			$amountgd = $amount - $amount10Percent;
			$this->model_account_autopdgd->createGD($amountgd, $value['customer_id'],$date_added);
			$this->model_account_autopdgd->update_c_wallet($value['customer_id']);
		}
	}

	//Get customer_id vi_C
	public function get_id_gd(){
		$this->load->model('account/autopdgd');
		$date_added ='2016-08-08';
		$all = $this->model_account_autopdgd->get_id_gd($date_added);
		$arrId_C='';
		foreach ($all as $value) {
			$arrId_C .= ','.$value['customer_id'];
		}
		$arrId_C = substr($arrId_C, 1);
		echo "<pre>"; print_r($arrId_C); echo "</pre>"; die();
	}

	public function activePD(){
	
		$this -> load -> model('account/customer');
		$this -> load -> model('account/auto');
		if (!$_GET['customer_id']) die();
		$customer_id = $_GET['customer_id'];
		// echo "<pre>"; print_r($customer_id); echo "</pre>"; die();
		
		$PDactive = $this -> model_account_auto -> getPdActive($customer_id);
		//echo "<pre>"; print_r($PDactive); echo "</pre>"; die();
		$arrId='';
		foreach ($PDactive as $key => $value) {
			$this -> model_account_auto -> update_package($value['customer_id'], $value['filled']);
			$this -> model_account_customer -> updateStusPDActive($value['id'], 1);
			$this -> model_account_customer -> updateCheck_R_WalletPD($value['id']);
			// $this -> model_account_customer -> updateStusGD($value['gd_id']);
			$customer = $this -> model_account_customer -> getCustomer($value['customer_id']);
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
				<h1><span style="font-size:12px">ID: '.$customer['username'].'kích hoạt gói '.$value['filled'].'USD !</span></h1>
				');
			// $mail -> send();

			$arrId .= ','.$value['customer_id'];
		}
		$arrId = substr($arrId, 1);
		echo $arrId;echo '<br>';
	}
}
