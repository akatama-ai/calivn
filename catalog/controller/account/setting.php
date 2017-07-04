<?php
class ControllerAccountSetting extends Controller {
	public function index() {

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> load -> model('account/customer');
			$self -> document -> addScript('catalog/view/javascript/setting/setting.js');
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
		$this -> load -> language('account/setting');
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		
		$language -> load('account/setting');
		$this -> load -> model('customize/country');
		$data['lang'] = $language -> data;

		$data['base'] = $server;
		$data['self'] = $this;
		$data['banks'] = $this -> model_account_customer -> getCustomerBank($this -> session -> data['customer_id']);
				
		$customer = $this -> model_account_customer -> getCustomer($this -> session -> data['customer_id']);
		
		$data['countries'] = $this-> model_customize_country ->getCountries();
		$data['customer']= $customer;
		$data['country_id']= $customer['country_id'];
		$data['zone'] = $this-> model_customize_country ->getProvince();
		$data['zone_byid'] = $this-> model_customize_country ->getZonesByCountryId($customer['country_id']);
		$data['zone_id']= $customer['address_id'];
	
		
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/setting.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/setting.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/setting.tpl', $data));
		}

	}
	public function country($country_id) {
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

	public function editpasswd() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/setting/setting.js');
		};
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		if ($this -> request -> server['REQUEST_METHOD'] === 'POST') {
			$this -> load -> model('account/customer');

			$this -> model_account_customer -> editPasswordCustom($this -> request -> post['password']);

			$variableLink = $this -> url -> link('account/setting', '', 'SSL') . '&success=password';

			$this -> response -> redirect($variableLink);
		}
	}

	public function edittransactionpasswd() {
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/setting/setting.js');
		};
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));
		if ($this -> request -> server['REQUEST_METHOD'] === 'POST') {
			$this -> load -> model('account/customer');

			$this -> model_account_customer -> editPasswordTransactionCustom($this -> request -> post['transaction_password']);
			$variableLink = $this -> url -> link('account/setting', '', 'SSL') . '&success=transaction';
			$this -> response -> redirect($variableLink);
		}
	}

	public function edit() {
		//not run for this function
		die();
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/setting/setting.js');
		};
		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		if ($this -> request -> server['REQUEST_METHOD'] === 'POST') {
			$this -> load -> model('account/customer');
			$this -> model_account_customer -> editCustomerCusotm($this -> request -> post);
			$variableLink = $this -> url -> link('account/setting', '', 'SSL') . '&success=account';
			$this -> response -> redirect($variableLink);
		}
	}

	public function account() {
		if ($this -> customer -> isLogged() && $this -> request -> get['id']) {
			$this -> load -> model('account/customer');
			$this -> response -> setOutput(json_encode($this -> model_account_customer -> getCustomerCustomFormSetting($this -> request -> get['id'])));
		}
	}

	public function banks() {
		if ($this -> customer -> isLogged() && $this -> request -> get['id']) {
			$this -> load -> model('account/customer');
			$this -> response -> setOutput(json_encode($this -> model_account_customer -> getCustomerBank($this -> request -> get['id'])));
		}
	}


	public function checkpasswdtransaction() {
		if ($this -> customer -> isLogged() && $this -> request -> get['pwtranction']) {
			$this -> load -> model('account/customer');
			$variable = $this -> model_account_customer -> getPasswdTransaction($this -> request -> get['pwtranction']);
			array_key_exists('number', $variable) && $this -> response -> setOutput(json_encode($variable['number']));
		}
	}

	public function checkpasswd() {
		if ($this -> customer -> isLogged() && $this -> request -> get['passwd']) {
			$this -> load -> model('account/customer');
			$variable = $this -> model_account_customer -> checkpasswd($this -> request -> get['passwd']);
			array_key_exists('number', $variable) && $this -> response -> setOutput(json_encode($variable['number']));
		}
	}
	public function checkwallet_btc($wallet) {
		
			$this -> load -> model('customize/register');
			$validate_address = $this -> check_address_btc($wallet);
		
			if (intval($validate_address) === 1) {
				$json['wallet'] = 1;
			} else {
				$json['wallet'] = -1;
			}
			
			return $json['wallet'];
			// $this -> response -> setOutput(json_encode($json));
		
	}
	public function updatewallet() {

		

		if ($this -> customer -> isLogged() && $this -> request -> get['wallet'] && $this -> request -> get['transaction_password']) {
			$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
			$this -> load -> model('account/customer');
			$variablePasswd = $this -> model_account_customer -> getPasswdTransaction($this -> request -> get['transaction_password']);
			$json['password'] = $variablePasswd['number'] === '0' ? -1 : 1;
			$json['wallet'] = 1;
			$check_wallet = $this -> checkwallet_btc($this -> request -> get['wallet']);
			if (intval($check_wallet) == -1) {
					$json['wallet'] = -1;
			}
			
			$json['ok'] = $json['login'] === 1 && $json['password'] === 1 && $json['wallet'] === 1 ? 1 : -1;

			$json['login'] === 1 && $json['password'] === 1 && $json['wallet'] === 1 && $this -> model_account_customer -> editCustomerWallet($this -> request -> get['wallet']);

			$this -> response -> setOutput(json_encode($json));
		}
	}
	public function updatebanks() {
		$this -> load -> model('account/customer');
		$banks = $this -> model_account_customer -> getCustomerBank($this -> session -> data['customer_id']);

		if($banks['account_holder'] || $banks['bank_name'] || $banks['account_number'] || $banks['branch_bank'] ) {
			die();
		}

		if ($this -> customer -> isLogged() && $this -> request -> get['account_holder'] && $this -> request -> get['bank_name'] && $this -> request -> get['account_number'] && $this -> request -> get['branch_bank']) {
			$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
			
			
			$json['ok'] = $json['login'] === 1 ? 1 : -1;
			$data = array(
					'account_holder' => $this -> request -> get['account_holder'],
					'bank_name' => $this -> request -> get['bank_name'],
					'account_number' => $this -> request -> get['account_number'],
					'branch_bank' => $this -> request -> get['branch_bank'],
				);
				$checkAccountNumber = $this -> checkaccount_number($this -> request -> get['account_number']);
			
			$json['checkAccountNumber'] = intval($checkAccountNumber);

			$json['login'] === 1 && $json['checkAccountNumber'] === 1 && $this -> model_account_customer -> editCustomerBanks($data);
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function checkaccount_number($account_number) {
		
			$this -> load -> model('customize/register');
			$checkExitAccountNumber = intval($this -> model_customize_register -> checkExitAccountNumber($account_number)) < 8 ? 1 : -1;
			return $checkExitAccountNumber;
		
	}
	public function update_profile(){

		$this -> load -> model('account/customer');
		if ($this -> customer -> isLogged() && $this -> request -> post['username'] && $this -> request -> post['email'] && $this -> request -> post['telephone']) {
			$json['login'] = $this -> customer -> isLogged() ? 1 : -1;
			$json['ok'] = $json['login'] === 1 ? 1 : -1;
			$data = array(
					'username' => $this -> request -> post['username'],
					'email' => $this -> request -> post['email'],
					'telephone' => $this -> request -> post['telephone'],
					'country_id' => $this -> request -> post['country_id'],
					'address_id' => $this -> request -> post['zone_id']
				);
			$json['login'] === 1 && $this -> model_account_customer -> editCustomerProfile($data);
			$json['link'] = HTTPS_SERVER . 'index.php?route=account/setting#success';
			$variableLink = HTTPS_SERVER.'index.php?route=account/setting#success';

		$this -> response -> redirect($variableLink);
			// $this -> response -> setOutput(json_encode($json));
			
		}
	}
	public function avatar(){
	$this->load->model('account/customer');
		$check_verify = $this -> model_account_customer -> check_verify($this->session->data['customer_id']);
		($check_verify['img_profile'] != "") && die();
		$filename = html_entity_decode($this->request->files['avatar']['name'], ENT_QUOTES, 'UTF-8');
		
		$filename = str_replace(' ', '_', $filename);
		if(!$filename || !$this->request->files){
			die();
		}

		$file = $filename . '.' . md5(mt_rand()) ;

		
		move_uploaded_file($this->request->files['avatar']['tmp_name'], DIR_UPLOAD . $file);


		//save image profile
		$server = $this -> request -> server['HTTPS'] ? $this -> config -> get('config_ssl') :  $this -> config -> get('config_url');
		
		$linkImage = $server . 'system/upload/'.$file;
	
		$this -> model_account_customer -> update_avatar($this -> session -> data['customer_id'],$linkImage);

		$variableLink = HTTPS_SERVER.'index.php?route=account/setting#success';

		$this -> response -> redirect($variableLink);
	}

	public function validate($address)
    {
        $decoded = $this->decodeBase58($address);
        $d1      = hash("sha256", substr($decoded, 0, 21), true);
        $d2      = hash("sha256", $d1, true);
        if (substr_compare($decoded, $d2, 21, 4)) {
            throw new Exception("bad digest");
        }
        
        return true;
    }
    
    public function decodeBase58($input)
    {
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
        $out      = array_fill(0, 25, 0);
        for ($i = 0; $i < strlen($input); $i++) {
            if (($p = strpos($alphabet, $input[$i])) === false) {
                throw new Exception("invalid character found");
            }
            
            $c = $p;
            for ($j = 25; $j--;) {
                $c += (int) (58 * $out[$j]);
                $out[$j] = (int) ($c % 256);
                $c /= 256;
                $c = (int) $c;
            }
            
            if ($c != 0) {
                throw new Exception("address too long");
            }
        }
        
        $result = "";
        foreach ($out as $val) {
            $result .= chr($val);
        }
        
        return $result;
    }
    
    public function check_address_btc($address_btc)
    {
        $address         = $address_btc;
        $message = 1;
        try {
            $abc = $this->validate($address);
        }
        
        catch (Exception $e) {
            $message = -1;
            
            // $json['message'] = $e->getMessage();
            
        }
        
        // $this->response->setOutput(json_encode($json));
        return $message;

    }

}
