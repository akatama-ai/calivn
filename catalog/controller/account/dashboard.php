<?php
class ControllerAccountDashboard extends Controller {

	public function index() {

		// $mail = new Mail();
		// $mail->protocol = $this->config->get('config_mail_protocol');
		// $mail->parameter = $this->config->get('config_mail_parameter');
		// $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		// $mail->smtp_username = $this->config->get('config_mail_smtp_username');
		// $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		// $mail->smtp_port = $this->config->get('config_mail_smtp_port');
		// $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		// $mail->setTo('phucnguyen@icsc.vn');
		// $mail->setFrom($this->config->get('config_email'));
		// $mail->setSender(html_entity_decode("test test", ENT_QUOTES, 'UTF-8'));
		// $mail->setSubject("asd11111111fssd");
		// $mail->setText("fddsasfsffsds");
		// $mail->send();

		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/dashboard/dashboard.js');
			$self -> load -> model('simple_blog/article');
		};
		

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$data['language']= $getLanguage;
		$language = new Language($getLanguage);
		$language -> load('account/dashboard');
		
		$data['lang'] = $language -> data;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//data render website
		//start load country model

		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}
		$insurance_fund= $this->model_account_customer->get_insurance_fund();
		$data['insurance_fund'] = $insurance_fund['amount'];
		$data['base'] = $server;
		$data['self'] = $this;

		// getArticles
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;      

		$limit = 5;
		$start = ($page - 1) * 5;
		$article_total = $this->model_simple_blog_article->getTotalArticle();

		$pagination = new Pagination();
		$pagination->total = $article_total;
		$pagination->page = $page;
		$pagination->limit = $limit; 
		$pagination->num_links = 5;
		//$pagination->text = 'text';
		$pagination->url = $this->url->link('account/dashboard', 'page={page}', 'SSL');
		if ($getLanguage == 'vietnamese') {
			$Language_id = 2;
		}else{
			$Language_id = 1;
		}
		$data['article_limit'] = $this -> model_simple_blog_article -> getArticleLimit($limit,$start, $Language_id);
		
		$data['pagination'] = $pagination->render();
$data['getall_user'] = $this->model_account_customer->getall_user();

		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/dashboard.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/dashboard.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/login.tpl', $data));
		}
	}
	public function viewBlogs(){
		function myCheckLoign($self) {
			return $self -> customer -> isLogged() ? true : false;
		};

		function myConfig($self) {
			$self -> document -> addScript('catalog/view/javascript/dashboard/dashboard.js');
			$self -> load -> model('simple_blog/article');
		};
		

		//language
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$data['language']= $getLanguage;
		$language = new Language($getLanguage);
		$language -> load('account/dashboard');
		
		$data['lang'] = $language -> data;

		//method to call function
		!call_user_func_array("myCheckLoign", array($this)) && $this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		call_user_func_array("myConfig", array($this));

		//data render website
		//start load country model

		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}

		$data['base'] = $server;
		$data['self'] = $this;
			//method to call function

			!$this -> request -> get['token']  && $this -> response -> redirect($this -> url -> link('account/dashboard', '', 'SSL'));
			$id_ = $this -> request -> get['token'];

if ($getLanguage == 'vietnamese') {
			$Language_id = 2;
		}else{
			$Language_id = 1;
		}
			$this->load->model('simple_blog/article');
			$data['detail_articles'] = $this->model_simple_blog_article->getArticlesBlogs($id_, $Language_id);        	
		
			if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/showblog.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/showblog.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/showblog.tpl', $data));
		}
		}

	public function changeLange(){
		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');
			$json['success'] = $this -> model_account_customer -> updateLanguage( $this -> session -> data['customer_id'], $this -> request -> get['lang'] ) ;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	/*
	 *
	 * ajax count total tree member
	 */
	public function totaltree() {
		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');
			$json['success'] = intval($this -> model_account_customer -> getCountTreeCustom($this -> session -> data['customer_id']));
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function totalpin() {
		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');
			$pin = $this -> model_account_customer -> getCustomer($this -> session -> data['customer_id']);
			$pin = $pin['ping'];
			$json['success'] = intval($pin);
			$pin = null;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function analytics() {

		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');
			$json['success'] = intval($this -> model_account_customer -> getCountLevelCustom($this -> session -> data['customer_id'], $this -> request -> get['level']));
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function countPD(){
		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');
			$total = $this -> model_account_customer -> getTotalPD($this -> session -> data['customer_id']);
			$total = $total['number'];
			$json['success'] = intval($total);
			$total = null;
			$this -> response -> setOutput(json_encode($json));
		}
	}


	public function countGD(){
		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');
			$total = $this -> model_account_customer -> getTotalGD($this -> session -> data['customer_id']);
			$total = $total['number'];
			$json['success'] = intval($total);
			$total = null;
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function getRWallet(){
		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');
			$checkR_Wallet = $this -> model_account_customer -> checkR_Wallet($this -> session -> data['customer_id']);
			if(intval($checkR_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertR_Wallet($this -> session -> data['customer_id'])){
					die();
				}
			}
			$total = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
			$total = count($total) > 0 ? $total['amount'] : 0;
			$json['success'] = $total;
			$total = null;

			$checkR_Wallet = $this -> model_account_customer -> checkR_Wallet($this -> session -> data['customer_id']);
			if(intval($checkR_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertR_Wallet($this -> session -> data['customer_id'])){
					die();
				}
			}
			//get r-wallet of user received
			//$customerReceived = $this->model_account_customer->getCustomer($this -> session -> data['customer_id']);
			$getRwallet = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
			$getGDRecived = $this -> model_account_customer -> getTotalGD($this -> session -> data['customer_id']);
			// if(intval($getGDRecived['number']) === 0 && intval($getRwallet['amount']) === 0 && intval($customerReceived['ping']) >= 6){
			// 	$this -> model_account_customer -> updateR_Wallet($customerReceived['customer_id'] , 3840000);
			// 	$this -> model_account_customer -> updateCheckNEwuser($customerReceived['customer_id']);
			// }
			$total = $this -> model_account_customer -> getR_Wallet($this -> session -> data['customer_id']);
			$total = count($total) > 0 ? $total['amount'] : 0;
			$json['success'] = $total;
			$json['success'] = number_format($json['success']);
			$this -> response -> setOutput(json_encode($json));
		}
	}

	public function getCWallet(){

		if ($this -> customer -> isLogged() && $this -> session -> data['customer_id']) {
			$this -> load -> model('account/customer');

			$checkC_Wallet = $this -> model_account_customer -> checkC_Wallet($this -> session -> data['customer_id']);


			if(intval($checkC_Wallet['number'])  === 0){
				if(!$this -> model_account_customer -> insertC_Wallet($this -> session -> data['customer_id'])){
					die();
				}
			}
			$total = $this -> model_account_customer -> getC_Wallet($this -> session -> data['customer_id']);
			$total = count($total) > 0 ? $total['amount'] : 0;
			$json['success'] = $total;
			$total = null;
			$json['success'] = number_format($json['success']);
			$this -> response -> setOutput(json_encode($json));
		}
	}

}
