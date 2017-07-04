<?php
class ModelAccountCustomer extends Model {
	
	public function addCustomer($data) {
		$this -> event -> trigger('pre.customer.add', $data);

		if (isset($data['customer_group_id']) && is_array($this -> config -> get('config_customer_group_display')) && in_array($data['customer_group_id'], $this -> config -> get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this -> config -> get('config_customer_group_id');
		}

		$this -> load -> model('account/customer_group');

		$customer_group_info = $this -> model_account_customer_group -> getCustomerGroup($customer_group_id);

		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this -> config -> get('config_store_id') . "', firstname = '" . $this -> db -> escape($data['firstname']) . "', lastname = '" . $this -> db -> escape($data['lastname']) . "', email = '" . $this -> db -> escape($data['email']) . "', telephone = REPLACE('" . $this -> db -> escape($data['telephone']) . "', ' ', ''), cmnd = '" . $this -> db -> escape($data['cmnd']) . "', account_bank = '" . $this -> db -> escape($data['account_bank']) . "', address_bank = '" . $this -> db -> escape($data['address_bank']) . "', p_node = '" . $this -> db -> escape($data['p_node']) . "', custom_field = '" . $this -> db -> escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this -> db -> escape($this -> request -> server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");

		$customer_id = $this -> db -> getLastId();

		$this -> load -> language('mail/customer');

		$subject = sprintf($this -> language -> get('text_subject'), html_entity_decode($this -> config -> get('config_name'), ENT_QUOTES, 'UTF-8'));

		$message = sprintf($this -> language -> get('text_welcome'), html_entity_decode($this -> config -> get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this -> language -> get('text_login') . "\n";
		} else {
			$message .= $this -> language -> get('text_approval') . "\n";
		}

		$message .= $this -> url -> link('account/login', '', 'SSL') . "\n\n";
		$message .= $this -> language -> get('text_services') . "\n\n";
		$message .= $this -> language -> get('text_thanks') . "\n";
		$message .= html_entity_decode($this -> config -> get('config_name'), ENT_QUOTES, 'UTF-8');
		/*
		 $mail = new Mail();
		 $mail->protocol = $this->config->get('config_mail_protocol');
		 $mail->parameter = $this->config->get('config_mail_parameter');
		 $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		 $mail->smtp_username = $this->config->get('config_mail_smtp_username');
		 $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		 $mail->smtp_port = $this->config->get('config_mail_smtp_port');
		 $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		 $mail->setTo($data['email']);
		 $mail->setFrom($this->config->get('config_email'));
		 $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		 $mail->setSubject($subject);
		 $mail->setText($message);
		 $mail->send();

		 // Send to main admin email if new account email is enabled
		 if ($this->config->get('config_account_mail')) {
		 $message  = $this->language->get('text_signup') . "\n\n";
		 $message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
		 $message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
		 $message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
		 $message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
		 $message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
		 $message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

		 $mail = new Mail();
		 $mail->protocol = $this->config->get('config_mail_protocol');
		 $mail->parameter = $this->config->get('config_mail_parameter');
		 $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		 $mail->smtp_username = $this->config->get('config_mail_smtp_username');
		 $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		 $mail->smtp_port = $this->config->get('config_mail_smtp_port');
		 $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		 $mail->setTo($this->config->get('config_email'));
		 $mail->setFrom($this->config->get('config_email'));
		 $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		 $mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
		 $mail->setText($message);
		 $mail->send();

		 // Send to additional alert emails if new account email is enabled
		 $emails = explode(',', $this->config->get('config_mail_alert'));

		 foreach ($emails as $email) {
		 if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
		 $mail->setTo($email);
		 $mail->send();
		 }
		 }
		 }
		 */
		$this -> event -> trigger('post.customer.add', $customer_id);

		return $customer_id;
	}
	public function update_fn_gd($gd_number) {
		
		$query = $this -> db -> query("UPDATE " . DB_PREFIX . "customer_get_donation SET status = '2' WHERE gd_number = '" . $this->db->escape($gd_number) . "'");
		return $query;
	}
	public function editCustomerProfile($data) {

		$data_arr = $data;
		$this -> event -> trigger('pre.customer.edit', $data_arr);
		$customer_id = $this -> session -> data['customer_id'];
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET address_id = '". $data_arr['address_id'] ."',country_id = '". $data_arr['country_id'] ."', username = '". $data_arr['username'] ."',email = '". $data_arr['email'] ."',telephone = '". $data_arr['telephone'] ."' WHERE customer_id = '" . (int)$customer_id . "'");
		$this -> event -> trigger('post.customer.edit', $customer_id);
	}
	public function saveTranstionHistory($customer_id, $wallet, $text_amount, $system_decsription){
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."customer_transaction_history SET
			customer_id = '".$customer_id."',
			wallet = '".$wallet."',
			text_amount = '".$text_amount."',
			system_decsription = '".$this->db->escape($system_decsription)."',
			date_added = NOW()
		");
		return $query;
	}
	
	public function getGdFromTransferList($gd_id){
		$query = $this -> db -> query("
			SELECT ctl.* , c.username
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.pd_id_customer = c.customer_id
			WHERE ctl.gd_id = '".$this->db->escape($gd_id)."'
		");
		return $query -> rows;
	}
	public function updateCheck_R_WalletPD($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				check_R_Wallet = 1
				WHERE id = '".$pd_id."'
			");
		return $query;
	}

	public function getGDTranferByID($transacion_id){

		$query = $this -> db -> query("
			SELECT c.username, ctl.*
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.pd_id_customer = c.customer_id
			WHERE ctl.id = '".$this->db->escape($transacion_id)."' AND gd_id_customer = ".$this -> session -> data['customer_id']."
		");
		return $query -> row;
	}

	/*public function getPNode($customer_id){
		$query = $this -> db -> query("
			SELECT * FROM sm_customer_provide_donation pd JOIN sm_customer_get_donation gd on pd.customer_id = gd.customer_id WHERE pd.customer_id in 
			(SELECT customer_id FROM sm_customer WHERE p_node = ".$customer_id.") AND pd.status = 2 AND gd.status = 2 GROUP BY pd.customer_id		");
		return $query -> rows;
	} */
	public function getPNode($customer_id){
		$query = $this -> db -> query("
			SELECT * FROM sm_customer_provide_donation where customer_id in 
			(SELECT customer_id FROM sm_customer WHERE p_node = ".$customer_id.") AND status IN(1, 2)  GROUP BY customer_id");
		return $query -> rows;
	}
	
	public function getPDByTranferID($transacion_id){
		$query = $this -> db -> query("
			SELECT pd_id, gd_id
			FROM ". DB_PREFIX . "customer_transfer_list
			WHERE id = ".$transacion_id."
		");
		return $query -> row;
	}
	public function countStatusPDTransferList($pd_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_transfer_list
			WHERE pd_id = '". $pd_id ."' AND pd_satatus = 0
			");
		return $query -> row;
	}
	public function countStatusGDTransferList($pd_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_transfer_list
			WHERE gd_id = '". $pd_id ."' AND pd_satatus = 0
			");
		return $query -> row;
	}
	public function updateStusPD($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				status = 2
				WHERE id = '".$pd_id."'
			");
		return $query;
	}
	public function updateStusPDActive($pd_id,$status){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				status = '".$status."',
				date_finish = DATE_ADD(NOW(),INTERVAL + 19 DAY)
				WHERE id = '".$pd_id."'
			");
		return $query;
	}
	
	public function updateStusGD($gd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				status = 2
				WHERE id = '".$gd_id."'
			");
		return $query;
	}

	public function updateStatusPDTransferList($transferID, $linkImage){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = 1,
				gd_status = 1,
				image = '".$linkImage."'
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}

	public function getPDTranferByID($transacion_id){
		$query = $this -> db -> query("
			SELECT c.*, ctry.name, ctl.*
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.gd_id_customer = c.customer_id
			JOIN ". DB_PREFIX ."country AS ctry
				ON ctry.country_id = c.country_id
			WHERE ctl.id = '".$this->db->escape($transacion_id)."'
		");
		return $query -> row;
	}

	public function getPdFromTransferList($pd_id){

		$query = $this -> db -> query("
			SELECT ctl.* , c.username
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.gd_id_customer = c.customer_id
			WHERE ctl.pd_id = '".$this->db->escape($pd_id)."'
		");
		return $query -> rows;
	}
	
	public function getGDByCustomerIDAndToken($customer_id, $token){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_get_donation
			WHERE customer_id = '". $customer_id ."' AND id = '".$token."'
			");
		return $query -> row;
	}
	public function getPD($iod_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."'
		");
		return $query -> rows;
	}
	public function getPD_byid($id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = '".$this->db->escape($id)."'
		");
		return $query -> row;
	}
	public function getPDLimit1($iod_customer){
		$query = $this -> db -> query("
			SELECT filled
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."' ORDER BY date_added DESC LIMIT 1
		");
		return $query -> row;
	}
	public function getPDfilled($iod_customer){
		$query = $this -> db -> query("
			SELECT filled
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."' AND status = 1
		");
		return $query -> row;
	}
	public function getPDById($id_customer, $limit, $offset){

		$query = $this -> db -> query("
			SELECT pd.*, c.username
			FROM  ".DB_PREFIX."customer_provide_donation AS pd
			JOIN ". DB_PREFIX ."customer AS c
			ON pd.customer_id = c.customer_id
			WHERE pd.customer_id = '".$this -> db -> escape($id_customer)."' 
			ORDER BY pd.date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		
		return $query -> rows;
	}

	public function getPDByCustomerIDAndToken($customer_id, $token){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_provide_donation
			WHERE customer_id = '". $customer_id ."' AND pd_number = '".$token."'
			");
		return $query -> row;
	}

	public function createPD($amount, $max_profit){
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '+15 day' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$date_finish_active = strtotime ( '+1 day' , strtotime ( $date_added ) ) ;
		$date_finish_active= date('Y-m-d H:i:s',$date_finish_active) ;
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET 
			customer_id = '".$this -> session -> data['customer_id']."',
			date_added = '".$date_added."',
			date_finish_forAdmin = '".$date_finish_active."',
			filled = '".$amount."',
			date_finish = '".$date_finish."',
			status = 0
		");
		//update max_profit and pd_number
		$pd_id = $this->db->getLastId();

		//$max_profit = (float)($amount * $this->config->get('config_pd_profit')) / 100;
		
		$pd_number = hexdec( crc32($pd_id) );
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET 
				max_profit = '".$max_profit."',
				pd_number = '".$pd_number."'
				WHERE id = '".$pd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['pd_number'] = $pd_number;
		$data['pd_id'] = $pd_id;
		return $data;
	}

	public function insertR_Wallet($id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_r_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = '0.0'
		");
		return $query;
	}
	public function insertR_WalletR($amount, $id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_r_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = ".$amount."
		");
		return $query;
	}

	public function insertC_Wallet($id_customer){
		$query = $this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer_c_wallet SET
			customer_id = '".$this -> db -> escape($id_customer)."',
			amount = '0'
		");
		return $query;
	}

	public function checkR_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_r_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}

	public function updateR_Wallet($id_customer, $amount){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_r_wallet SET
			amount = '" . $this -> db -> escape((float)$amount) . "'
			WHERE customer_id = '" . (int)$id_customer . "'");

		return $query;
	}

	public function checkC_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM  ".DB_PREFIX."customer_c_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}

	public function getTotalPD($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");

		return $query -> row;
	}

	public function getTableCustomerMLByUsername($customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_ml
			WHERE customer_id = '".$customer_id."'
		");

		return $query -> row;
	}

	public function getR_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT amount
			FROM  ".DB_PREFIX."customer_r_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}

	public function getC_Wallet($id_customer){
		$query = $this -> db -> query("
			SELECT amount
			FROM  ".DB_PREFIX."customer_c_wallet
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");
		return $query -> row;
	}

	public function getLikeMember($name = '', $idUserLogin){
		if($name === ''){
			$customer_query = $this->db->query("
				SELECT username AS name , customer_id AS code FROM " . DB_PREFIX . "customer WHERE customer_id <> ". $this->db->escape($idUserLogin) ."
				LIMIT 8");
			return $customer_query -> rows;
		}
		if($name !== ''){
			$customer_query = $this->db->query("
				SELECT username AS name , customer_id AS code FROM " . DB_PREFIX . "customer
				WHERE customer_id <> ". $idUserLogin ." AND username Like '%".$this->db->escape($name)."%'
				LIMIT 8");
			return $customer_query -> rows;
		}
	}

	public function getCountFloor($id_user) {
		$query = $this -> db -> query("SELECT customer_id 
			FROM " . DB_PREFIX . "customer_ml 
			WHERE p_binary IN (". $id_user.")");
		return $query -> rows;	
		
	}
	public function getTotalCustomerFloor($arrId){
		$query = $this -> db -> query("
			SELECT count(*) AS number 
			FROM " . DB_PREFIX . "customer c JOIN " . DB_PREFIX . "customer_ml ml
			ON c.customer_id = ml.customer_id
			WHERE c.customer_id IN (".$arrId.")
		");

		return $query -> row;
	}
	public function getCustomerFloor($arrId, $limit, $offset){
		$query = $this->db->query("SELECT c.customer_id, c.username AS name, c.account_holder as fullname, c.email, 
			c.telephone, ml.level, c.p_node
			FROM " . DB_PREFIX . "customer c JOIN " . DB_PREFIX . "customer_ml ml
			ON c.customer_id = ml.customer_id
			WHERE c.customer_id IN (".$arrId.") LIMIT ".$limit." OFFSET ".$offset."");
		return $query -> rows;
	}
	public function getParrent($customer_id){
		$query = $this->db->query("SELECT username
			FROM " . DB_PREFIX . "customer WHERE customer_id = ".$customer_id."");
		return $query -> row['username'];
	}
	public function get_date_deposite($customer_id){
		$query = $this->db->query("SELECT date_added
			FROM " . DB_PREFIX . "customer_provide_donation WHERE customer_id = ".$customer_id." ORDER BY date_added ASC  LIMIT 1");
		return $query -> row['date_added'];
	}
	
	public function checkPD($customer_id){
		$query = $this->db->query("SELECT *
			FROM " . DB_PREFIX . "customer_provide_donation WHERE status IN (1,2) AND customer_id = ".$customer_id."");
		return $query -> rows;
	}
	public function getPasswdTransaction($password=''){
		if($password !== ''){
			$customer_query = $this->db->query("
				SELECT COUNT(*) AS number FROM " . DB_PREFIX . "customer
				WHERE customer_id = '". $this -> session -> data['customer_id'] ."' AND 
				transaction_password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR transaction_password_custom = '" . $this->db->escape(md5($password)) . "'");
			return $customer_query -> row;
		}
	}

	public function countGdOfDay($month, $year, $day){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE customer_id = '".$this -> session -> data['customer_id']."'
				  AND MONTH(date_added) = '".$month."'
				  AND YEAR(date_added) = '".$year."'
				  AND DAY(date_added) = '".$day."'
		");

		return $query -> row;
	}

	public function update_C_Wallet($amount , $customer_id, $add = false){
		if(!$add){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_c_wallet SET
				amount = amount - ".floatval($amount)."
				WHERE customer_id = '".$customer_id."'
			");
			return $query === true ? true : false;
		}
		
	}

	public function update_R_Wallet($amount , $customer_id, $add = false){
		if(!$add){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_r_wallet SET
				amount = amount - ".floatval($amount)."
				WHERE customer_id = '".$customer_id."'
			");
		}
		return $query === true ? true : false;
	}

	public function createGD($amount){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET
			customer_id = '".$this -> session -> data['customer_id']."',
			date_added = NOW(),
			amount = '".$amount."',
			status = 0
		");

		$gd_id = $this->db->getLastId();

		$gd_number = hexdec(crc32($gd_id));

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				gd_number = '".$gd_number."'
				WHERE id = '".$gd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		return $data;
	}

	public function editPasswordCustomForEmail($data, $password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $data['customer_id'];
		$salt = $data['salt'];

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}
	public function getCustomLike($name, $id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.username AS name, c.customer_id AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node = ". $id_user ." AND c.username Like '%".$this->db->escape($name)."%'");
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['name'];
			$listId .= $this -> getCustomLike($name,$item['code']);
		}
		return $listId;
	}
	public function checkUserName($id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.username AS name, c.customer_id AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node = ". $id_user ."");
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['name'];
			$listId .= $this -> checkUserName($item['code']);
		}
		return $listId;
	}

public function checkUserName_In_username($username) {
		echo "
			SELECT COUNT(*) as number FROM ". DB_PREFIX ."customer WHERE username IN ('". $username ."')";
		$query = $this -> db -> query("
			SELECT COUNT(*) as number FROM ". DB_PREFIX ."customer WHERE username IN ('". $username ."')");
		
		return $query -> row['number'];
	}


	public function getTotalGD($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_get_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
		");

		return $query -> row;
	}

	public function getGDById($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_get_donation
			WHERE customer_id = '".$this -> db -> escape($id_customer)."'
			ORDER BY date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function checkpasswd($password=''){
		if($password !== ''){
			$customer_query = $this->db->query("
				SELECT COUNT(*) AS number FROM " . DB_PREFIX . "customer
				WHERE customer_id = '". $this -> session -> data['customer_id'] ."' AND password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) AND status <> 0 ");
			return $customer_query -> row;
		}
	}

	public function updatePin($id_customer, $pin, $add = false){
		if ($add) {
			$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			ping = ping + " . $this -> db -> escape((int)$pin) . "
			WHERE customer_id = '" . (int)$id_customer . "'");
		}else{
			$this -> event -> trigger('pre.customer.edit', $data);
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			ping = ping - " . $this -> db -> escape((int)$pin) . "
			WHERE customer_id = '" . (int)$id_customer . "'");

		$this -> event -> trigger('post.customer.edit', $id_customer);
		}
		

	}

	public function updateStatus($id_customer,  $status){
		if($id_customer && $status){
			$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				status = '" . $this -> db -> escape((int)$status) . "'
				WHERE customer_id = '" . (int)$id_customer. "'");
			if($query){
				$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer_ml SET
				status = '" . $this -> db -> escape((int)$status) . "'
				WHERE customer_id = '" . (int)$id_customer. "'");
			}else{
				$query = false;
			}

			return $query;
		}
	}

	public function getLevel($customer_id, $level){
		$query =  $this -> db -> query("
			SELECT * 
					FROM " . DB_PREFIX . "customer_ml
					WHERE customer_id
					IN ( SELECT customer_id FROM " . DB_PREFIX . "customer WHERE p_node = ".$customer_id." )
					AND level >= ".$level."
				");
		return $query -> rows;
	}
	public function getLevel_id($customer_id){
		$query =  $this -> db -> query("
			SELECT * 
					FROM " . DB_PREFIX . "customer_ml
					WHERE customer_id = ".$customer_id."
				");
		return $query -> row;
	}

	public function updateLevel($customer_id, $level){
		echo "UPDATE " . DB_PREFIX . "customer_ml SET
				level = ".$level."
				WHERE customer_id = '" . (int)$customer_id. "'"; echo '<br>';
		$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer_ml SET
				level = ".$level."
				WHERE customer_id = '" . (int)$customer_id. "'");
		return $query;
	}

	public function updateCheckNEwuser($id_customer){
		if($id_customer){
			$query =  $this -> db -> query("
				UPDATE " . DB_PREFIX . "customer SET
				check_Newuser = 0
				WHERE customer_id = '" . (int)$id_customer. "'");
			return $query;
		}
	}
	


	public function saveHistoryPin($id_customer, $amount, $user_description, $type , $system_description){
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "ping_history SET
			id_customer = '" . $this -> db -> escape($id_customer) . "',
			amount = '" . $this -> db -> escape( $amount ) . "',
			date_added = NOW(),
			user_description = '" .$this -> db -> escape($user_description). "',
			type = '" .$this -> db -> escape($type). "',
			system_description = '" .$this -> db -> escape($system_description). "'
		");
		return $this -> db -> getLastId();
	}

	public function getTotalRefferalByID($id_customer){

		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM ".DB_PREFIX."customer_ml
			WHERE p_node =  '".$this -> db -> escape($id_customer)."'
		");

		return $query -> row;
	}

	public function getRefferalByID($id_customer ,$limit, $offset){
		$query = $this -> db -> query("
			SELECT c.email , c.username,c.telephone,c.cmnd,c.wallet,c.country_id,c.address_id, c.customer_id, ml.level, c.date_added
			FROM ".DB_PREFIX."customer_ml AS ml
			JOIN ". DB_PREFIX ."customer AS c
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node =  '".$this -> db -> escape($id_customer)."'
			ORDER BY ml.level DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");

		return $query -> rows;
	}

	public function getTotalTokenHistory($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."ping_history
			WHERE id_customer = ".$this -> db -> escape($id_customer)." AND amount <> '- 0' AND amount <> '+ 0'
		");

		return $query -> row;
	}

	public function getTokenHistoryById($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."ping_history
			WHERE id_customer = ".$this -> db -> escape($id_customer)." AND amount <> '- 0' AND amount <> '+ 0'
			ORDER BY date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."

		");

		return $query -> rows;
	}

	public function editCustomerWallet($wallet) {

		$data['wallet'] = $wallet;
		$this -> event -> trigger('pre.customer.edit', $data);
		$customer_id = $this -> customer -> getId();
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET wallet = '". $wallet ."' WHERE customer_id = '" . (int)$customer_id . "'");
		$this -> event -> trigger('post.customer.edit', $customer_id);
	}

	public function editCustomerBanks($data) {

		$data_arr = $data;
		$this -> event -> trigger('pre.customer.edit', $data_arr);
		$customer_id = $this -> customer -> getId();
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET account_holder = '". $data_arr['account_holder'] ."',bank_name = '". $data_arr['bank_name'] ."',account_number = '". $data_arr['account_number'] ."',branch_bank = '". $data_arr['branch_bank'] ."' WHERE customer_id = '" . (int)$customer_id . "'");
		$this -> event -> trigger('post.customer.edit', $customer_id);
	}

	public function editCustomer($data) {

		$this -> event -> trigger('pre.customer.edit', $data);

		$customer_id = $this -> customer -> getId();

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this -> db -> escape($data['firstname']) . "', lastname = '" . $this -> db -> escape($data['lastname']) . "', email = '" . $this -> db -> escape($data['email']) . "', telephone = '" . $this -> db -> escape($data['telephone']) . "', account_bank = '" . $this -> db -> escape($data['account_bank']) . "', address_bank = '" . $this -> db -> escape($data['address_bank']) . "', custom_field = '" . $this -> db -> escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");

		$this -> event -> trigger('post.customer.edit', $customer_id);
	}

	public function editCustomerCusotm($data) {


		$this -> event -> trigger('pre.customer.edit', $data);

		$customer_id = $this -> customer -> getId();
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			email = '" . $this -> db -> escape($data['email']) . "',
			telephone = '" . $this -> db -> escape($data['telephone']) . "'
			WHERE customer_id = '" . (int)$customer_id . "'");

		$this -> event -> trigger('post.customer.edit', $customer_id);
	}

	public function editPasswordCustom($password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $this -> customer -> getId();

		$salt = $this -> getCustomer($customer_id);
		$salt = $salt['salt'];

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function editPasswordTransactionCustom($password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $this -> customer -> getId();

		$salt = $this -> getCustomer($customer_id);
		$salt = $salt['salt'];

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function editPassword($email, $password) {
		$this -> event -> trigger('pre.customer.edit.password');

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function editNewsletter($newsletter) {
		$this -> event -> trigger('pre.customer.edit.newsletter');

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this -> customer -> getId() . "'");

		$this -> event -> trigger('post.customer.edit.newsletter');
	}

	public function getCustomer($customer_id) {
		$query = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}
	public function check_verify($customer_id) {
		$query = $this -> db -> query("SELECT img_profile  FROM " . DB_PREFIX . "customer WHERE customer_id = '".$customer_id."'");
		return $query -> row;
	}
	public function update_avatar($customer_id, $image){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
				img_profile = '".$image."'
				WHERE customer_id = '".$customer_id."'
			");
		return $query;
	}
	public function getCustomerPDForPD($p_node) {
		$query = $this -> db -> query("
			SELECT c.customer_id 
			FROM " . DB_PREFIX . "customer c  
			JOIN sm
			WHERE c.p_node = '" . (int)$p_node . "'"
		);
		return $query -> row;
	}

	public function getTotalHistory($customer_id){
		$query = $this -> db -> query("
			SELECT count(*) AS number 
			FROM ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".intval($customer_id)."'
		");

		return $query -> row;
	}

	public function getTransctionHistory($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transaction_history
			WHERE customer_id = '".$this -> db -> escape($id_customer)."' 
			ORDER BY date_added DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		
		return $query -> rows;
	}

	public function getCustomerCustom($customer_id) {
		$query = $this -> db -> query("SELECT c.customer_id, c.username, c.telephone, c.customer_id , ml.level, c.p_node, c.cycle
			FROM ". DB_PREFIX ."customer AS c
				JOIN ". DB_PREFIX ."customer_ml AS ml
				ON ml.customer_id = c.customer_id
				WHERE c.customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}

	public function getCustomerBank($customer_id) {
		$query = $this -> db -> query("SELECT account_holder, bank_name, account_number,branch_bank   FROM ". DB_PREFIX ."customer WHERE customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}

	public function editPasswordTransactionCustomForEmail($data, $password) {
		$this -> event -> trigger('pre.customer.edit.password');
		$customer_id = $data['customer_id'];
		$salt = $data['salt'];
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($password)))) . "'
			WHERE customer_id = '" . $this -> db -> escape($customer_id) . "'");

		$this -> event -> trigger('post.customer.edit.password');
	}

	public function getCustomerCustomFormSetting($customer_id) {
		$query = $this -> db -> query("SELECT c.username, c.telephone , c.email , c.wallet , ml.level FROM ". DB_PREFIX ."customer AS c
				JOIN ". DB_PREFIX ."customer_ml AS ml
				ON ml.customer_id = c.customer_id
				WHERE c.customer_id = '" . (int)$customer_id . "'");
		return $query -> row;
	}

	public function getUserOff($listIdChild) {
		if ($listIdChild != '') {
			$query = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 0");
			return $query -> rows;
		}
		return array();
	}

	public function getUserNotHP($listIdChild) {
		if ($listIdChild != '') {
			$date = strtotime(date('Y-m-d'));
			$month = date('m', $date);
			$year = date('Y', $date);
			$arrNotHP = array();
			$query = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 1");
			$arrUser = $query -> rows;
			foreach ($arrUser as $user) {
				$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "profit WHERE  user_id = " . $user['customer_id'] . " and type_profit = 1 and year = '" . $year . "' AND month = '" . $month . "'");
				if (!$query -> row) {
					array_push($arrNotHP, $user);
				}
			}
			return $arrNotHP;
		} else {
			return array();
		}
	}

	public function getListChild($id_package) {
		$query = $this -> db -> query("SELECT cm.*,c.username,c.telephone,c.status AS status_cus,c.firstname,c.cmnd,CONCAT(c.firstname, ' ', c.lastname) as name_customer,ml.name_vn as package_vn FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id)  WHERE cm.p_node = '" . (int)$id_package . "'");

		return $query -> rows;
	}

	public function getListChildCustom($id_package) {
		$query = $this -> db -> query("
				SELECT cm.level, c.username, c.telephone , c.customer_id
				FROM ". DB_PREFIX ."customer_ml cm LEFT JOIN ". DB_PREFIX ."customer c ON (c.customer_id = cm.customer_id)
				WHERE cm.p_node = '2'
			");

		return $query -> rows;
	}

	public function getListChildNotPackage($id_user) {
		$id_user = $id_user * (-1);
		$query = $this -> db -> query("SELECT cm.*,c.username,c.firstname,c.cmnd,CONCAT(c.firstname, ' ', c.lastname) as name_customer,ml.name_vn as package_vn FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id)  WHERE cm.p_node = '" . $id_user . "'");

		return $query -> rows;
	}

	public function getCustomerByEmail($email) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		return $query -> row;
	}

	public function getCustomerByUsername($username) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(username) = '" . $this -> db -> escape(utf8_strtolower($username)) . "'");

		return $query -> row;
	}

	public function getCustomerByToken($token) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this -> db -> escape($token) . "' AND token != ''");

		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query -> row;
	}

	public function getTotalCustomersById($customer_id) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query -> row['total'];
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		return $query -> row['total'];
	}

	public function getTotalCustomersByTelephone($telephone) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(telephone) = REPLACE('" . $this -> db -> escape(utf8_strtolower($telephone)) . "'" . ", ' ', '')");

		return $query -> row['total'];
	}

	public function getIps($customer_id) {
		$query = $this -> db -> query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query -> rows;
	}

	public function isBanIp($ip) {
		$query = $this -> db -> query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this -> db -> escape($ip) . "'");

		return $query -> num_rows;
	}

	public function addLoginAttempt($email) {
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this -> db -> escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this -> db -> escape($this -> request -> server['REMOTE_ADDR']) . "'");

		if (!$query -> num_rows) {
			$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this -> db -> escape(utf8_strtolower((string)$email)) . "', ip = '" . $this -> db -> escape($this -> request -> server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this -> db -> escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this -> db -> escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this -> db -> escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query -> row['customer_login_id'] . "'");
		}
	}

	public function getLoginAttempts($email) {
		$query = $this -> db -> query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");

		return $query -> row;
	}

	public function deleteLoginAttempts($email) {
		$this -> db -> query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this -> db -> escape(utf8_strtolower($email)) . "'");
	}

	public function getPackages($customer_id) {
		$query = $this -> db -> query("SELECT cm.*,ml.name_vn AS package_vn FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id) WHERE cm.customer_id = '" . (int)$customer_id . "' ORDER BY cm.date_added");

		return $query -> rows;
	}

	public function getInfoPackages($id_package) {
		$query = $this -> db -> query("SELECT cm.*,ml.name_vn AS package_vn,c.username,c.firstname FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) LEFT JOIN " . DB_PREFIX . "member_level ml ON (cm.level = ml.id) WHERE cm.id_package = '" . (int)$id_package . "'");

		return $query -> row;
	}

	public function getNameParent($customer_id) {
		$query = $this -> db -> query("SELECT c.firstname AS name_parent FROM " . DB_PREFIX . "customer_ml cm LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = cm.customer_id) WHERE cm.customer_id = '" . (int)$customer_id . "'");
		if (isset($query -> row['name_parent'])) {
			return $query -> row['name_parent'];
		} else
			return "";
	}

	public function getMonthRegister($customer_id) {
		$date = strtotime(date('Y-m-d'));
		$yearNow = date('Y', $date);
		$monthNow = date('m', $date);
		$query = $this -> db -> query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$rowCus = $query -> row;
		$dateRegis = strtotime($rowCus['date_added']);
		$yearRegis = date('Y', $dateRegis);
		$monthRegis = date('m', $dateRegis);
		$numYear = $yearNow - $yearRegis;
		if ($numYear > 0) {
			$monthNow = $monthNow + (12 * $numYear);
		}
		return $monthNow - $monthRegis;
	}

	public function getAllProfitByType($user_id, $type) {
		$query = $this -> db -> query("SELECT SUM(receive) AS total FROM " . DB_PREFIX . "profit WHERE user_id = '" . (int)$user_id . "' and type_profit in (" . $type . ")");
		return $query -> row['total'];
	}

	public function countProfitByType($user_id, $type) {
		$query = $this -> db -> query("SELECT count(*) AS total FROM " . DB_PREFIX . "profit WHERE user_id = '" . (int)$user_id . "' and type_profit in (" . $type . ")");
		return $query -> row['total'];
	}

	function getBParent($id) {
		$query = $this -> db -> query("select p_binary from " . DB_PREFIX . "customer as u1 INNER join " . DB_PREFIX . "customer_ml AS u2 ON u1.customer_id = u2.customer_id where u1.customer_id = " . (int)$id);
		return $query -> row['p_binary'];
	}

	function getInfoUsers($id_ids) {
		if (!is_array($id_ids))
			$ids = array($id_ids);
		else
			$ids = $id_ids;
		$array_id = "( " . implode(',', $ids) . " )";
		$query = $this -> db -> query("select u.*,mlm.level, l.name_vn as level_member from " . DB_PREFIX . "customer as u Left Join " . DB_PREFIX . "customer_ml as mlm ON mlm.customer_id = u.customer_id  Left Join " . DB_PREFIX . "member_level as l ON l.id = mlm.level  Where u.customer_id IN " . $array_id);
		if (!is_array($id_ids)) {
			$return = $query -> row;
		} else {
			$return = $query -> rows;
		}
		return $return;
	}

	//	lay tong so thanh vien
	function getSumNumberMember($node) {
		$result = 0;
		return $result;
	}

	function getLeftO($id) {
		$query = $this -> db -> query('select mlm.customer_id as id, mlm.level,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as text, CONCAT( "level1"," left") as iconCls,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as name,l.name_vn as level_user,u2.username,u2.status,u2.date_added  from ' . DB_PREFIX . 'customer AS u2 LEFT join ' . DB_PREFIX . 'customer_ml AS mlm ON u2.customer_id = mlm.customer_id INNER join ' . DB_PREFIX . 'customer_ml AS u1 ON u1.left = mlm.customer_id left Join ' . DB_PREFIX . 'member_level as l ON l.id = mlm.level where mlm.p_binary = ' . (int)$id);
		//	return json_decode(json_encode($query->row), false);
		return $query -> row;
	}

	function getRightO($id) {
		$query = $this -> db -> query('select mlm.customer_id as id, mlm.level,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as text, CONCAT( "level1"," right") as iconCls,CONCAT(u2.firstname," (ĐT: ",u2.telephone,")") as name,l.name_vn as level_user,u2.username,u2.status,u2.date_added from ' . DB_PREFIX . 'customer AS u2 LEFT join ' . DB_PREFIX . 'customer_ml AS mlm ON u2.customer_id = mlm.customer_id INNER join ' . DB_PREFIX . 'customer_ml AS u1 ON u1.right = mlm.customer_id left Join ' . DB_PREFIX . 'member_level as l ON l.id = mlm.level where mlm.p_binary = ' . (int)$id);
		//return json_decode(json_encode($query->row), false);
		return $query -> row;
	}

	function getLeft($id) {
		$query = $this -> db -> query("select u2.left from " . DB_PREFIX . "customer as u1 INNER JOIN " . DB_PREFIX . "customer_ml AS u2 ON u1.customer_id = u2.customer_id where u1.customer_id = " . (int)$id);
		return null;
	}

	function getRight($id) {
		$query = $this -> db -> query("select u2.right from " . DB_PREFIX . "customer as u1 INNER JOIN " . DB_PREFIX . "customer_ml AS u2 ON u1.customer_id = u2.customer_id where u1.customer_id = " . (int)$id);
		return null;
	}

	function getSumLeft($id) {
		$result = 0;
		$left = $this -> getLeft($id);
		if ($left) {
			$result += 1;
			$result += $this -> getSumMember($left);
		}
		return $result;
	}

	//Get sum right node binarytree
	function getSumRight($id) {
		$result = 0;
		$right = $this -> getRight($id);
		if ($right) {
			$result += 1;
			$result += $this -> getSumMember($right);
		}
		return $result;
	}

	//Get sum left node and right node for any node bynary
	function getSumMember($id) {

		$result = 0;
		$left = $this -> getLeft($id);
		$right = $this -> getRight($id);
		if ($left) {
			$result += 1;
			$result += $this -> getSumMember($left);
		}
		if ($right) {
			$result += 1;
			$result += $this -> getSumMember($right);
		}

		//print_r($result);
		return $result;
	}

	function getSumFloor($arrId) {
		$floor = 0;
		$query = $this -> db -> query("select mlm.customer_id from " . DB_PREFIX . "customer as u Left Join " . DB_PREFIX . "customer_ml as mlm ON mlm.customer_id = u.customer_id  Where mlm.p_binary IN (" . $arrId . ")");
		$arrChild = $query -> rows;

		if (!empty($arrChild)) {
			$floor += 1;
			$arrId = '';
			foreach ($arrChild as $child) {
				$arrId .= ',' . $child['customer_id'];
			}
			$arrId = substr($arrId, 1);
			$floor += $this -> getSumFloor($arrId);
		}
		return $floor;
	}

	function checkActiveUser($id_user = 0) {
		$query = $this -> db -> query("select u1.status from " . DB_PREFIX . "customer as u1 where u1.customer_id = " . (int)$id_user);
		return $query -> row['status'];
	}

	function getCountTreeCustom($id_user) {
		$listId = 0;
		$query = $this -> db -> query("select customer_id from " . DB_PREFIX . "customer_ml where p_node = " . (int)$id_user);
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId ++;
			$listId = $listId + $this -> getCountTreeCustom($item['customer_id']);
		}
		return $listId;
	}

	function getCountLevelCustom($id_user, $level) {
		$listId = 0;

		$query = $this -> db -> query("select customer_id , level from " . DB_PREFIX . "customer_ml where p_node = " . (int)$id_user);
		$array_id = $query -> rows;

		foreach ($array_id as $item) {
			intval($item['level']) === intval($level) && $listId ++;
			$listId = $listId + $this -> getCountLevelCustom($item['customer_id'], $level);
		}
		return $listId;
	}

	function getListIdChild($id_user) {
		$listId = '';
		$query = $this -> db -> query("select customer_id from " . DB_PREFIX . "customer_ml where p_binary = " . (int)$id_user);
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['customer_id'];
			$listId .= $this -> getListIdChild($item['customer_id']);
		}
		return $listId;
	}

	function getListCTP($id_user) {
		$dateEnd = date("Y-m-d H:i:s");
		$monthEnd = date('m', strtotime($dateEnd));
		$yearEnd = date('Y', strtotime($dateEnd));
		$arrCTP = array();
		$query = $this -> db -> query("select * from " . DB_PREFIX . "customer where customer_id = " . (int)$id_user);
		$infoUser = $query -> row;
		$dateStar = $infoUser['date_added'];

		$monthRegister = $this -> getMonthRegister($id_user);
		$numHP = $this -> countProfitByType($id_user, 1);
		$config_congtacphi = $this -> config -> get('config_congtacphi');
		for ($n = 1; $n <= 12; $n++) {
			$monthStar = date('m', strtotime($dateStar));
			$yearStar = date('Y', strtotime($dateStar));
			if ($monthStar == "12") {
				$monthNext = 1;
				$yearNext = $yearStar + 1;
			} else {
				$monthNext = $monthStar + 1;
				$yearNext = $yearStar;
			}
			$dateNext = date("Y-m-d", strtotime("01-" . $monthNext . "-" . $yearNext));
			if (strtotime($dateNext) <= strtotime($dateEnd)) {
				$node = new stdClass();
				$queryHVTT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "customer_ml where p_binary = " . (int)$id_user . " AND date_added >= '" . $dateStar . "' AND date_added < '" . $dateNext . "'");
				$numHVTT = $queryHVTT -> row['total'];
				$CTP_HVTT = $numHVTT * $config_congtacphi;
				$node -> numHVTT = $numHVTT;
				$node -> CTP_HVTT = $CTP_HVTT;
				$queryHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND receive > 0 AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateNext) . "'");
				$numHVGT = $queryHVGT -> row['total'] - $numHVTT;
				$CTP_HVGT = $numHVGT * $config_congtacphi;
				$queryTotalHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateNext) . "'");
				$numTotalHVGT = $queryTotalHVGT -> row['total'] - $numHVTT;
				$node -> numHVGT = $numHVGT;
				$node -> numTotalHVGT = $numTotalHVGT;
				$node -> CTP_HVGT = $CTP_HVGT;
				$node -> CTP_DuKien = $CTP_HVTT + $CTP_HVGT;
				$queryHPFromCTP = $this -> db -> query("select SUM(receive) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 1 AND hp_from_ctp = 1 AND date_hpdk >= '" . strtotime($dateStar) . "' AND date_hpdk < '" . strtotime($dateNext) . "'");
				$numHPFromCTP = $queryHPFromCTP -> row['total'];

				$numUserOff = 0;
				$listIdChild = $this -> getListIdChild($id_user);
				$listIdChild = substr($listIdChild, 1);

				if ($listIdChild != '') {
					$queryUserOff = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 0 AND MONTH(c.date_off ) = '" . $monthStar . "' AND YEAR(c.date_off ) = '" . $yearStar . "' AND c.num_off = 1 and c.type_off = 1");
					$numUserOff = count($queryUserOff -> rows);
				}

				if (($monthRegister >= $n && $numHP > $n) || ($monthRegister == 11 && $n == 12 && $numHP == 12)) {
					$node -> CTP_Thuc = $node -> CTP_DuKien - $numHPFromCTP - ($numUserOff * $config_congtacphi);
				} else {
					$node -> CTP_Thuc = 0;
				}
				$dateStar = $dateNext;
				array_push($arrCTP, $node);
			} else {
				$node = new stdClass();
				$queryHVTT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "customer_ml where p_binary = " . (int)$id_user . " AND date_added >= '" . $dateStar . "' AND date_added < '" . $dateEnd . "'");
				$numHVTT = $queryHVTT -> row['total'];
				$CTP_HVTT = $numHVTT * $config_congtacphi;
				$node -> numHVTT = $numHVTT;
				$node -> CTP_HVTT = $CTP_HVTT;
				$queryHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . "  AND receive > 0 AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateEnd) . "'");
				$numHVGT = $queryHVGT -> row['total'] - $numHVTT;
				$CTP_HVGT = $numHVGT * $config_congtacphi;
				$queryTotalHVGT = $this -> db -> query("select count(*) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 2 AND `date` >= '" . strtotime($dateStar) . "' AND `date` < '" . strtotime($dateEnd) . "'");
				$numTotalHVGT = $queryTotalHVGT -> row['total'] - $numHVTT;
				$node -> numHVGT = $numHVGT;
				$node -> numTotalHVGT = $numTotalHVGT;
				$node -> CTP_HVGT = $CTP_HVGT;
				$node -> CTP_DuKien = $CTP_HVTT + $CTP_HVGT;
				$queryHPFromCTP = $this -> db -> query("select SUM(receive) AS total from " . DB_PREFIX . "profit where user_id = " . (int)$id_user . " AND type_profit = 1 AND hp_from_ctp = 1 AND date_hpdk >= '" . strtotime($dateStar) . "' AND date_hpdk < '" . strtotime($dateNext) . "'");
				$numHPFromCTP = $queryHPFromCTP -> row['total'] + 0;
				$numUserOff = 0;
				$listIdChild = $this -> getListIdChild($id_user);
				$listIdChild = substr($listIdChild, 1);

				if ($listIdChild != '') {
					$queryUserOff = $this -> db -> query("SELECT c.* FROM " . DB_PREFIX . "customer c  WHERE c.customer_id IN (" . $listIdChild . ") AND c.status = 0 AND MONTH(c.date_off) = '" . $monthStar . "' AND YEAR(c.date_off ) = '" . $yearStar . "' AND c.num_off = 1 and c.type_off = 1");
					$numUserOff = count($queryUserOff -> rows);
				}
				if ($monthRegister >= $n && $numHP > $n || ($monthRegister == 11 && $n == 12 && $numHP == 12)) {
					$node -> CTP_Thuc = $node -> CTP_DuKien - $numHPFromCTP - ($numUserOff * $config_congtacphi);
				} else {
					$node -> CTP_Thuc = 0;
				}

				array_push($arrCTP, $node);
				break;
			}
		}

		if ($n < 12) {
			for ($n; $n <= 12; $n++) {
				$node = new stdClass();
				$node -> numHVTT = 0;
				$node -> CTP_HVTT = 0;
				$node -> numHVGT = 0;
				$node -> numTotalHVGT = 0;
				$node -> CTP_HVGT = 0;
				$node -> CTP_DuKien = 0;
				$node -> CTP_Thuc = 0;
				array_push($arrCTP, $node);
			}
		}

		return $arrCTP;
	}

	public function getCheckPD($id_customer){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE customer_id = '".$this->db->escape($id_customer)."'
		");
		return $query -> row;
	}
	public function UpdateCheckPD($id_customer){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			 SET check_Pd = check_Pd + 1 WHERE customer_id = '".$this->db->escape($id_customer)."'
		");
	}
	public function UpdateResetPD($id_customer){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer
			 SET check_Pd = '0' WHERE customer_id = '".$this->db->escape($id_customer)."'
		");
	}
	public function CountGDDay(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id= '".$this -> session -> data['customer_id']."'
			AND (SELECT date_added FROM ". DB_PREFIX . "customer_provide_donation 
				WHERE customer_id= '".$this -> session -> data['customer_id']."' ORDER BY date_added DESC LIMIT 1) <= DATE_ADD(NOW(), INTERVAL -10 DAY) 
			ORDER BY date_added ASC LIMIT 1
		");

		return $query->row;
	}	
	public function getStatusPD(){
		$query = $this -> db -> query("
			SELECT COUNT(*) as pdtotal
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE status = '0' AND customer_id = '".$this -> session -> data['customer_id']."'
		");
		return $query -> row;
	}
	public function getStatusGD(){
		$query = $this -> db -> query("
			SELECT COUNT(*) as gdtotal
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE status = '0' AND customer_id = '".$this -> session -> data['customer_id']."'
		");
		return $query -> row;
	}
	

	public function getLanguage($customer_id){
		$query = $this -> db -> query("
			SELECT language 
			FROM ". DB_PREFIX . "customer
			WHERE customer_id = ".$customer_id."
		");
		return $query -> row['language'];
	}

	public function updateLanguage($customer_id, $language){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer SET
			language = '".$language."'
			WHERE customer_id = ".$customer_id."			
		");
		return $query;
	}
public function countPD($customer_id){
		$query = $this -> db -> query("
			SELECT * FROM sm_customer_provide_donation where customer_id in 
			(SELECT customer_id FROM sm_customer WHERE p_node = ".$customer_id.") AND status = 0 GROUP BY customer_id");
		return $query -> rows;
	}
public function countPDINProvide(){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE customer_id = '".$this->session->data['customer_id']."'
		");
		return $query -> rows;
	}
public function get_insurance_fund(){
		
		$query = $this -> db -> query("
			SELECT amount
			FROM  ".DB_PREFIX."customer_insurance_fund
			WHERE id = 1
		");
		return $query -> row;
	}
	public function update_insurance_fund($amount){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer_insurance_fund SET
			amount = amount + ".$amount."
			WHERE id = 1			
		");
		return $query;
	}

	
	public function getCustomOfNode($id_user) {
		$listId = '';
		$query = $this -> db -> query("
			SELECT c.username AS name, c.customer_id AS code FROM ". DB_PREFIX ."customer AS c
			JOIN ". DB_PREFIX ."customer_ml AS ml
			ON ml.customer_id = c.customer_id
			WHERE ml.p_node = ". $id_user."");
		$array_id = $query -> rows;
		foreach ($array_id as $item) {
			$listId .= ',' . $item['code'];
			$listId .= $this -> getCustomOfNode($item['code']);
		}
		return $listId;
	}
	public function getalltransfer(){
		$query = $this -> db -> query("
			SELECT pd_id_customer, amount,date_added,transfer_code
			FROM  ".DB_PREFIX."customer_transfer_list WHERE
			pd_id_customer IN ( 2166, 2205, 2249, 2250, 2251, 2252, 2253, 2254, 2255, 2256, 2257, 2258, 2259, 2289, 2290, 2292, 2293, 2294, 2295, 2298, 2309, 2310, 2311, 2312, 2313, 2391, 2392, 2394, 2402, 2406, 2407, 2408, 2414, 2416, 2417, 2418, 3893, 3900, 3902, 3903, 3907, 3908, 3910, 3911, 3912 ) 
		");
		return $query -> rows;
	}
	public function createGDTransfer($customer_id,$amount,$date){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET
			customer_id = '".$customer_id."',
			date_added = '".$date."',
			amount = '".$amount."',
			status = 0
		");

		$gd_id = $this->db->getLastId();

		$gd_number = hexdec(crc32($gd_id));

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				gd_number = '".$gd_number."'
				WHERE id = '".$gd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		return $data;
	}
	public function updateRwallet($id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_r_wallet SET
				amount = '0'
				WHERE customer_id = '".$id."'
			");
	}
	public function getall_user()
	{
		$query = $this -> db -> query("
			SELECT *,B.name,B.iso_code_2
			FROM ". DB_PREFIX . "customer A INNER JOIN ". DB_PREFIX . "country B ON A.country_id = B.country_id ORDER BY A.date_added DESC
		");
		return $query -> rows;
	}
	public function countPD_by_customer_id($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_provide_donation
			WHERE customer_id = '". $id_customer ."'
		");
		return $query -> row;
	}
}
