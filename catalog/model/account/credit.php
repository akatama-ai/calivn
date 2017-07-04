<?php
class ModelAccountCredit extends Model {
	
	public function Save_history_transfer_credit($customer_id_send, $customer_id_received, $amount, $wallet, $type){
		$this -> db -> query("INSERT INTO " . DB_PREFIX . "customer_history_transfer_credit SET
			customer_id_send = '" . $this -> db -> escape($customer_id_send) . "',
			customer_id_received = '" . $this -> db -> escape( $customer_id_received ) . "',
			amount = '" . $this -> db -> escape( $amount ) . "',
			wallet = '" . $this -> db -> escape( $wallet ) . "',
			type = '" . $this -> db -> escape( $type ) . "',
			date = NOW()
		");
		return $this -> db -> getLastId();
	}


	public function checkUsername($username){
		$query = $this -> db -> query("
			SELECT customer_id
			FROM ". DB_PREFIX ."customer
			WHERE username = '".$this->db->escape($username)."'
		");
		return $query -> row;
	}
	public function getTotalTransferCredit($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_history_transfer_credit
			WHERE customer_id_send = ".$this -> db -> escape($id_customer)."
		");

		return $query -> row;
	}
	public function getTransferHistoryById($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_history_transfer_credit
			WHERE customer_id_send = ".$this -> db -> escape($id_customer)."
			ORDER BY date DESC
			LIMIT ".$limit."
			OFFSET ".$offset."

		");

		return $query -> rows;
	}
	public function getTotalReceivedCredit($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT( * ) AS number
			FROM  ".DB_PREFIX."customer_history_transfer_credit
			WHERE customer_id_received = ".$this -> db -> escape($id_customer)."
		");

		return $query -> row;
	}
	public function getReceivedHistoryById($id_customer, $limit, $offset){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_history_transfer_credit
			WHERE customer_id_received = ".$this -> db -> escape($id_customer)."
			ORDER BY date DESC
			LIMIT ".$limit."
			OFFSET ".$offset."

		");

		return $query -> rows;
	}
	public function get_username($customer_id){
		$query = $this -> db -> query("
			SELECT username
			FROM ". DB_PREFIX ."customer
			WHERE customer_id = '".$this->db->escape($customer_id)."'
		");
		return $query -> row['username'];
	}
}