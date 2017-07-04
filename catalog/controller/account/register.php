<?php
class ControllerAccountRegister extends Controller {
	private $error = array();

	public function index() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));

		$this -> document -> addScript('catalog/view/javascript/register/register.js');

		if ($this -> request -> server['REQUEST_METHOD'] === 'POST') {

		}

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/register');
		$data['lang'] = $language -> data;
		
		//start load country model
		$this -> load -> model('customize/country');
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		//$data['province'] = $this -> model_customize_country -> getProvince();
		$data['country'] = $this -> model_customize_country -> getCountry();
		//end load country model
		$data['countries'] = $this-> model_customize_country ->getCountries();
		//data render website
		$data['self'] = $this;

		//error validate
		$data['error'] = $this -> error;
		$data['country'] = $this -> model_customize_country -> getCountry();
		
		$data['actionCheckUser'] = $this -> url -> link('account/registers/checkuser', '', 'SSL');
		$data['actionCheckEmail'] = $this -> url -> link('account/registers/checkemail', '', 'SSL');
		$data['actionCheckPhone'] = $this -> url -> link('account/registers/checkphone', '', 'SSL');
		$data['actionCheckCmnd'] = $this -> url -> link('account/registers/checkcmnd', '', 'SSL');

		if ($this->request->server['REQUEST_METHOD'] === 'POST'){
			$this -> load -> model('customize/register');
			$this -> load -> model('account/auto');
			$this -> load -> model('account/customer');
			$tmp = $this -> model_customize_register -> addCustomer($this->request->post);

			$cus_id= $tmp;
			$amount = 0;
			
			$checkC_Wallet = $this -> model_account_customer -> checkR_Wallet($cus_id);
			if(intval($checkC_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertR_WalletR($amount, $cus_id)){
					die();
				}
			}
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
				<h1><span style="font-size:12px">Congratulations Your Registration is Confirmed!</span></h1>
				<p><span style="font-size:12px"><strong>What is Next?</strong></span></p>
				<p><span style="font-size:12px">You can now&nbsp;<a href="' . $this -> url -> link("account/login", "", "SSL") . '" style="color:rgb(0,72,153);background:transparent" target="_blank">login</a>&nbsp;using your chosen&nbsp;<strong>user name and&nbsp;</strong><strong>password</strong>, and begin to use this website.</span></p>
				<p><span style="font-size:12px">Please assess our website via:&nbsp;<a href="' . $server . '" target="_blank">caligroup.info</a> for the next step</span></p>
				<p><span style="font-size:12px">- Your user name : ' . $this -> request -> post["username"] . '</span></p>
				<p><span style="font-size:12px">- Your Password : ' . $this -> request -> post["password"] . '</span></p>
				<p><span style="font-size:12px">- Your Transaction Password : ' . $this -> request -> post["transaction_password"] . '</span></p>
				<p><span style="font-size:12px"><span style="font-family:arial,helvetica,sans-serif">If you have any questions, feel free to contact us by using our support center in the adress belov</span></span></p>
				<p><strong><span style="font-size:12px">Caligroup Global support team!</span></strong></p>
			');
			$mail -> send();

			$this -> response -> redirect($this -> url -> link('account/register', '#success', 'SSL'));
		}


		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/register.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/register.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/register.tpl', $data));
		}

	}
	public function country() {
		$json = array();

		$this->load->model('customize/country');

		$country_info = $this->model_customize_country->getCountrys($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_customize_country->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function checkuser() {
		if ($this -> request -> get['username']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitUserName($this -> request -> get['username'])) === 1 ? 1 : 0;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function checkemail() {
		if ($this -> request -> get['email']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitEmail($this -> request -> get['email'])) < 7 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}
	public function checkphone() {
		if ($this -> request -> get['phone']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitPhone($this -> request -> get['phone'])) < 7 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function checkcmnd() {
		if ($this -> request -> get['cmnd']) {
			$this -> load -> model('customize/register');
			$json['success'] = intval($this -> model_customize_register -> checkExitCMND($this -> request -> get['cmnd'])) < 7 ? 0 : 1;
			$this -> response -> setOutput(json_encode($json));
		}
	}



}
