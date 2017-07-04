<?php
class ModelAccountPd extends Model {

	public function createGDInventory($amount, $customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET 
			customer_id = '".$customer_id."',
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
		if($query){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET 
				date_added = NOW()
				WHERE customer_id = '".$customer_id."'
			");
		}
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		$data['gd_id'] = $gd_id;
		return $data;
	}

	public function getInvoiceFormTranferID($transferid){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE transfer_id = ".$transferid."
		");
		return $query -> row;
	}

	public function createGD10($amount, $customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET 
			customer_id = '".$customer_id."',
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
		if($query){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET 
				date_added = NOW()
				WHERE customer_id = '".$customer_id."'
			");
		}
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		$data['gd_id'] = $gd_id;
		return $data;
	}

	public function createGDCustom($amount, $customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET 
			customer_id = '".$customer_id."',
			date_added = DATE_ADD(NOW(),INTERVAL -30 DAY),
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
		if($query){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET 
				date_added = NOW()
				WHERE customer_id = '".$customer_id."'
			");
		}
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		return $data;
	}

	public function createPDInventory($filled, $customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET 
			customer_id = '".$customer_id."',
			date_added = DATE_ADD(NOW(),INTERVAL -8 DAY),
			filled = '".$filled."',
			amount = 0,
			status = 0
		");

		$gd_id = $this->db->getLastId();
		
		$gd_number = hexdec(crc32($gd_id));

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET 
				pd_number = '".$gd_number."'
				WHERE id = '".$gd_id."'
			");
		if($query){
			$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET 
				date_added = NOW()
				WHERE customer_id = '".$customer_id."'
			");
		}
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		return $data;
	}

	

	public function getGDBefore(){
		$query = $this -> db -> query("
			SELECT id , customer_id, amount , filled
			FROM ". DB_PREFIX . "customer_get_donation 
			WHERE date_added <= DATE_ADD(NOW(), INTERVAL -1 DAY)
				  AND status = 0 OR status = 1 AND filled < amount
			ORDER BY date_added ASC
			LIMIT 1
		");
		return $query -> row;
	}
	public function getGD7BeforeCustom(){
		$query = $this -> db -> query("
			SELECT id , customer_id, amount , filled
			FROM ". DB_PREFIX . "customer_get_donation 
			WHERE date_added <= DATE_ADD(NOW(), INTERVAL +10 DAY)
				  AND status = 0 AND customer_id IN (SELECT customer_id FROM ".DB_PREFIX ."customer WHERE customer_id = 50)
			ORDER BY date_added DESC
			LIMIT 1
		");
		return $query -> row;
	}

	public function getCreatePD($iod_customer){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."'
		");
		return $query -> row;
	}

	public function getPDNow($id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = '".$this->db->escape($id)."'
		");
		return $query -> row;
	}

	public function getCustomerInventory(){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE status = 9 
			ORDER BY date_added ASC 
			LIMIT 1
		");
		return $query -> row;
	}
	public function getCustomerAdmin(){

		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE status = 2
			ORDER BY date_added ASC 
			LIMIT 1
		");
		return $query -> row;
	}

	public function getCustomerALLInventory(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE status = 9
		");
		return $query -> rows;
	}
	public function getUser(){
		$query = $this -> db -> query("
			SELECT * 
			FROM ". DB_PREFIX . "customer_tmp
		");
		return $query -> rows;
	}

	public function updateStatusPD($pd_id , $status){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_provide_donation SET 
			status = '".$status."' 
			WHERE id = '".$pd_id."'
		");
	}
	public function get_StatusPD($pd_id){
		$query = $this -> db -> query("SELECT * 
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE id = '".$pd_id."'
		");
		return $query -> row;
	}
	public function get_invoide($pd_id){
		$query = $this -> db -> query("
			SELECT confirmations,pd.filled AS pd_amount, inv.input_address, inv.amount AS amount_inv, inv.received
			FROM ". DB_PREFIX . "customer_provide_donation AS pd
			JOIN ". DB_PREFIX . "customer_invoice_pd inv
				ON pd.id = inv.transfer_id
			WHERE pd.id = ".$pd_id."
		");
		return $query -> row;
	}
	public function updateStatusPD_fn($customer_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_provide_donation SET 
			status = 2
			WHERE customer_id = '".$customer_id."'
		");
	}
	

	public function updateAmountPD($pd_id , $amount){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET 
			amount20percent = amount20percent + ".$amount." 
			WHERE id = '".$pd_id."'
		");
	}
	public function updateTotalAmountPD($pd_id , $amount){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET 
			amount = amount + ".$amount." 
			WHERE id = '".$pd_id."'
		");
	}


	public function updateFilledGD($gd_id , $filled){
		$this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET 
			filled = filled + '".$filled."' 
			WHERE id = '".$gd_id."'
		");
	}

	public function updateStatusGD($gd_id , $status){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_get_donation SET 
			status = '".$status."'
			WHERE id = '".$gd_id."'
		");
	}

	public function createTransferList10Percent($data, $transferAdmin){
		$this -> db -> query("
				INSERT INTO ". DB_PREFIX . "customer_transfer_list SET 
				pd_id = '".$data["pd_id"]."',
				gd_id = '".$data["gd_id"]."',
				pd_id_customer = '".$data["pd_id_customer"]."',
				gd_id_customer = '".$data["gd_id_customer"]."',
				
				date_added = NOW(),
				date_finish = DATE_ADD(NOW() , INTERVAL +1 DAY),
				amount = '".$data["amount"]."',
				pd_satatus = 0,
				gd_status = 0
				
			");
		return $this->db->getLastId();
	}
	public function updateTransferList($transfer_id){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer_transfer_list SET 
			transfer_code = '".hexdec( crc32($transfer_id) )."'
			WHERE id = ".$transfer_id."
		");

		return $query;
	}
	public function createTransferList20Percent($data, $transferAdmin){
		$this -> db -> query("
				INSERT INTO ". DB_PREFIX . "customer_transfer_list SET 
				pd_id = '".$data["pd_id"]."',
				gd_id = '".$data["gd_id"]."',
				pd_id_customer = '".$data["pd_id_customer"]."',
				gd_id_customer = '".$data["gd_id_customer"]."',
				
				date_added = NOW(),
				date_finish = DATE_ADD(NOW() , INTERVAL +1 DAY),
				amount = '".$data["amount"]."',
				pd_satatus = 0,
				gd_status = 0,
				transferAdmin = '".$transferAdmin."'
			");
		return $this->db->getLastId();
	}
	public function getAllPD(){
		$query = $this -> db -> query("
			SELECT ctl.* , c.p_node, pd.check_R_Wallet as checkRWallet, pd.max_profit as max, pd.status as pdstatus, pd.pd_number as pd_number
			FROM ". DB_PREFIX . "customer_provide_donation AS pd
			JOIN ". DB_PREFIX ."customer_transfer_list AS ctl
				ON ctl.pd_id = pd.id
			JOIN ". DB_PREFIX ."customer AS c
				ON c.customer_id = pd.customer_id
			WHERE ctl.date_finish <= NOW()
			
		");
		return $query -> rows;
	}
	public function getCusIdByPdID($pd_id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_transfer_list
			WHERE pd_id = '".$this -> db -> escape($pd_id)."'
		");
		return $query -> row;
	}
	
	public function updatePDcheck_R_Wallet($pd_id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_provide_donation SET
			check_R_Wallet = 0
			WHERE id = '".$pd_id."'
		");
		return $query === true ? true : false;
	}
	public function update_R_Wallet($amount , $customer_id){
		
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_r_wallet SET
			amount = amount + ".intval($amount)."
			WHERE customer_id = '".$customer_id."'
		");

		return $query === true ? true : false;
	}
	public function update_C_Wallet($amount , $customer_id){
		$query = $this -> db -> query("
		UPDATE " . DB_PREFIX . "customer_c_wallet SET
			amount = amount + ".intval($amount)."
			WHERE customer_id = '".$customer_id."'
		");
	}

	public function updateStatusCustomer($customer_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			status = 8 WHERE customer_id = '".$customer_id."'
		");
	}
	public function updateStatusPDTransferList($transferID){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = 2,
				gd_status = 2				
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}
	
	public function getFromTransferList(){
		$query = $this -> db -> query("
			SELECT ctl.* , c.username
			FROM ". DB_PREFIX . "customer_transfer_list AS ctl
			JOIN ". DB_PREFIX ."customer AS c
				ON ctl.pd_id_customer = c.customer_id
			WHERE ctl.date_finish <= NOW() and ctl.transferAdmin IN(1,2) and pd_satatus = 0 and gd_status = 0
		");
		return $query -> rows;
	}
	public function DeletePD($id) {
		$this -> db -> query("DELETE FROM `" . DB_PREFIX . "customer_provide_donation` WHERE id = '" . $this -> db -> escape($id) . "'");
	}
	public function DeleteGD($id) {
		$this -> db -> query("DELETE FROM `" . DB_PREFIX . "customer_get_donation` WHERE id = '" . $this -> db -> escape($id) . "'");
	}



	//================================================================
	public function getAllInvoiceByCustomer($customer_id, $limit, $offset){
		$query = $this -> db -> query("
			SELECT amount, received, confirmations, date_created, transfer_id, input_address
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $customer_id ."'
			ORDER BY date_created DESC
			LIMIT ".$limit."
			OFFSET ".$offset."
		");
		return $query -> rows;
	}
 
	public function getAllInvoiceByCustomer_notCreateOrder($customer_id){
		$query = $this -> db -> query("
			SELECT amount, received, confirmations, date_created, transfer_id, input_address
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $customer_id ."' AND confirmations = 0
			ORDER BY date_created DESC
		");
		return $query -> rows;
	}

	public function getAllInvoiceByCustomerTotal($customer_id){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $customer_id ."'
		");
		return $query -> row;
	}

	public function countPD($id_customer){
		$query = $this -> db -> query("
			SELECT COUNT(*) AS number
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $id_customer ."' AND confirmations = 0
		");
		return $query -> row;
	}

	public function updateInaddressAndFree($invoice_id, $invoice_id_hash , $input_addr, $fee_percent, $my_addr,$call_back){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
			input_address = '".$input_addr."',
			fee_percent = ".$fee_percent.",
			my_address = '".$my_addr."',
			invoice_id_hash = '".$invoice_id_hash."',
			callback='".$call_back."'
			WHERE invoice_id = ".$invoice_id."");
		return $query;
	}

	public function updateConfirm($invoice_id_hash, $confirmations){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
			confirmations = ".$confirmations."
			WHERE invoice_id_hash = ". $invoice_id_hash."");
		return $query;
	}

	public function updateReceived($received, $invoice_id_hash){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
			received = received + '" . $received . "'
			WHERE invoice_id_hash = '" . $invoice_id_hash . "'");
		return $query;
	}
	public function updateTransactionHash($transaction_hash, $input_transaction_hash, $invoice_id_hash){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_invoice_pd SET
			transaction_hash = '" . $transaction_hash . "',
			input_transaction_hash = '" . $input_transaction_hash . "'
			WHERE invoice_id_hash = '" . $invoice_id_hash . "'");
		return $query;
	}
	public function updatePin($id_customer, $pin){

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer SET
			ping = ping + '" . $this -> db -> escape((int)$pin) . "'
			WHERE customer_id = '" . (int)$id_customer . "'");
		return $query;
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

	public function saveInvoice($customer_id, $secret, $amount,$transferid){
		
		$query = $this -> db -> query("
			INSERT INTO ".DB_PREFIX."customer_invoice_pd SET
			customer_id = '".$customer_id."',
			secret = '".$secret."',
			amount = ".$amount.",
			transfer_id = '".$transferid."',
			received = 0,
			date_created = NOW()
		");

		return $query === True ? $this->db->getLastId() : -1;
	}

	public function getInvoiceByIdAndSecret($invoice_id_hash, $secret){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE invoice_id_hash = '". $invoice_id_hash ."' AND 
				  secret = '".$secret."'
		");
		return $query -> row;
	}

	public function updateStatusPDTransfer10Percent($transferID, $link){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_transfer_list SET
				pd_satatus = 1,
				gd_status = 1,
				link = '".$link."'
				WHERE id = '".$this->db->escape($transferID)."'
		");
		return $query;
	}
	public function getInvoceFormHash($invoice_id_hash, $customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX ."customer_invoice_pd
			WHERE customer_id = '". $customer_id ."' AND invoice_id_hash = ".$invoice_id_hash."
		");
		return $query -> row;
	}
	public function check_packet_pd($customer_id, $amount){
		
		$query = $this -> db -> query("
			SELECT id as pd_number, status
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = ".$customer_id." and filled = ".$amount." LIMIT 1
		");
		return $query -> row;
	}
	
}