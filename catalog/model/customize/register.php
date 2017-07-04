<?php
class ModelCustomizeRegister extends Model {
	public function checkExitUserName($username) {
		$query = $this -> db -> query("
			SELECT EXISTS(SELECT 1 FROM " . DB_PREFIX . "customer WHERE username = '" . $username . "')  AS 'exit'
			");

		return $query -> row['exit'];
	}

	public function checkExitUserNameForToken($username, $idUserNameLogin) {
		$query = $this -> db -> query("
			SELECT EXISTS(SELECT 1 FROM " . DB_PREFIX . "customer WHERE customer_id <> '". $idUserNameLogin ."' AND  username = '" . $username . "')  AS 'exit'
			");

		return $query -> row['exit'];
	}

	public function checkExitEmail($email) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE email = '" . $email . "'
			");

		return $query -> row['number'];
	}
public function checkExitAccountNumber($account_number) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE account_number = '" . $account_number . "'
			");

		return $query -> row['number'];
	}
	public function checkExitPhone($telephone) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE telephone = '" . $telephone . "'
			");

		return $query -> row['number'];
	}

	public function checkExitCMND($cmnd) {
		$query = $this -> db -> query("
			SELECT count(*) AS number FROM " . DB_PREFIX . "customer WHERE cmnd = '" . $cmnd . "'
			");

		return $query -> row['number'];
	}

	public function addCustomer($data) {
		
		
		$data['p_node'] = $this -> session -> data['customer_id'];

		$this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer SET
			p_node = '" . $this -> db -> escape($data['p_node']) . "', 
			email = '" . $this -> db -> escape($data['email']) . "', 
			username = '" . $this -> db -> escape($data['username']) . "', 
			telephone = '" . $this -> db -> escape($data['telephone']) . "', 
			salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '1', 
			cmnd = '" . $this -> db -> escape($data['cmnd']) . "', 
			country_id = '". $this -> db -> escape($data['country_id']) ."',
			address_id = '". $this -> db -> escape($data['zone_id']) ."',
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['transaction_password'])))) . "',
			date_added = NOW(),
			check_Newuser = 1,
			language = 'english'
		");

		$customer_id = $this -> db -> getLastId();

		$totalChild = $this -> getTotalChild($data['p_node']);
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_ml SET customer_id = '" . (int)$customer_id . "',p_binary = '" . $data['p_node'] . "', level = '1', p_node = '" . $data['p_node'] . "', date_added = NOW()");
		if ($totalChild == 0) {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `left` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_node'] . "'");
		} else {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `right` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_node'] . "'");
		}

		return $customer_id;

	}
	public function addCustomerByToken($data, $p_node) {
		$this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer SET
			p_node = '" . $this -> db -> escape($p_node) . "', 
			email = '" . $this -> db -> escape($data['email']) . "', 
			username = '" . $this -> db -> escape($data['username']) . "', 
			telephone = '" . $this -> db -> escape($data['telephone']) . "', 
			salt = '" . $this -> db -> escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '1', 
			cmnd = '" . $this -> db -> escape($data['cmnd']) . "', 
			country_id = '" . $this -> db -> escape($data['country_id']) . "',
			address_id = '". $this -> db -> escape($data['zone_id']) ."',
			transaction_password = '" . $this -> db -> escape(sha1($salt . sha1($salt . sha1($data['transaction_password'])))) . "',
			date_added = NOW()
		");

		$customer_id = $this -> db -> getLastId();

		$totalChild = $this -> getTotalChild($p_node);
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_ml SET customer_id = '" . (int)$customer_id . "',p_binary = '" . $p_node . "', level = '1', p_node = '" . $p_node . "', date_added = NOW()");
		if ($totalChild == 0) {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `left` = '" . (int)$customer_id . "' WHERE customer_id = '" . $dp_node . "'");
		} else {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `right` = '" . (int)$customer_id . "' WHERE customer_id = '" . $p_node . "'");
		}
	}

	public function getTotalChild($customer_id) {
		$query = $this -> db -> query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ml WHERE p_binary = " . (int)$customer_id);
		return intval($query -> row['total']);
	}

	public function getTableCustomerTmp() {
		$query = $this -> db -> query("
			SELECT * FROM " . DB_PREFIX . "customer_tmp 
			");

		return $query -> rows;
	}
	public function addCustomerInventory($data) {

		$this -> db -> query("
				INSERT INTO " . DB_PREFIX . "customer SET
				p_node = '" . $this -> db -> escape($data['p_node']) . "', 
				email = '" . $this -> db -> escape($data['email']) . "', 
				username = '" . $this -> db -> escape($data['username']) . "', 
				telephone = '" . $this -> db -> escape($data['telephone']) . "', 
				salt =  '" . $this -> db -> escape($data['salt']) . "', 
				password = '" . $this -> db -> escape($data['password']) . "', 
				status = '10', 
				cmnd = '" . $this -> db -> escape($data['cmnd']) . "', 
				country_id = '" . $this -> db -> escape($data['country_id']) . "',
				transaction_password = '" . $this -> db -> escape($data['transaction_password']) . "',

			account_number = '" . $this -> db -> escape($data['account_number']) . "', 
			account_holder = '" . $this -> db -> escape($data['account_holder']) . "',
			bank_name = '" . $this -> db -> escape($data['bank_name']) . "',
			branch_bank = '" . $this -> db -> escape($data['branch_bank']) . "', 
				date_added = NOW()
			");

		$customer_id = $this -> db -> getLastId();

		$totalChild = $this -> getTotalChild($data['p_node']);
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_ml SET customer_id = '" . (int)$customer_id . "',p_binary = '" . $data['p_node'] . "', level = '1', p_node = '" . $data['p_node'] . "', date_added = NOW(), status = 9");
		if ($totalChild == 0) {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `left` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_node'] . "'");
		} else {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `right` = '" . (int)$customer_id . "' WHERE customer_id = '" . $data['p_node'] . "'");
		}
		return $customer_id;
	}
	public function addCustomerCustom($data, $p_node) {

		$this -> db -> query("
			INSERT INTO " . DB_PREFIX . "customer SET
			p_node = '" . $this -> db -> escape($p_node) . "', 
			email = '" . $this -> db -> escape($data['email']) . "', 
			username = '" . $this -> db -> escape($data['username']) . "', 
			telephone = '" . $this -> db -> escape($data['telephone']) . "', 
			salt = '27ce70995', 
			password = 'aee59be4256b84bdbef72905a5b4c46c0ffa5d15', 
			status = '9', 
			cmnd = '" . $this -> db -> escape($data['cmnd']) . "', 
			account_number = '" . $this -> db -> escape($data['account_number']) . "', 
			account_holder = '" . $this -> db -> escape($data['account_holder']) . "',
			bank_name = '" . $this -> db -> escape($data['bank_name']) . "',
			branch_bank = '" . $this -> db -> escape($data['branch_bank']) . "', 
			country_id = '" . $this -> db -> escape($data['country_id']) . "',
			transaction_password = 'cbbf11c085ccd5191b1d9946fc7fa5800a446649',
			date_added = NOW()
		");

		$customer_id = $this -> db -> getLastId();

		$totalChild = $this -> getTotalChild($p_node);
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_ml SET customer_id = '" . (int)$customer_id . "',p_binary = '" . $p_node . "', level = '1', p_node = '" . $p_node . "', date_added = NOW()");
		if ($totalChild == 0) {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `left` = '" . (int)$customer_id . "' WHERE customer_id = '" . $dp_node . "'");
		} else {
			$this -> db -> query("UPDATE " . DB_PREFIX . "customer_ml SET `right` = '" . (int)$customer_id . "' WHERE customer_id = '" . $p_node . "'");
		}
	}
}
