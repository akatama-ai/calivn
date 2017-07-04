<?php
class ModelAccountAuto extends Model {

	public function createGDInventory($amount, $customer_id){
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
		$data['gd_id'] = $gd_id;
		return $data;
	}
	public function createGD($amount,$customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET
			customer_id = '".$customer_id."',
			date_added = NOW(),
			date_finish = DATE_ADD(NOW(),INTERVAL +1 DAY) ,
			amount = '".$amount."',
			status = 1
		");
// date_finish = DATE_ADD(NOW(),INTERVAL +1 DAY),
		$gd_id = $this->db->getLastId();

		$gd_number = hexdec(crc32($gd_id));

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_get_donation SET
				gd_number = '".$gd_number."'
				WHERE id = '".$gd_id."'
			");
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		$data['gd_id'] = $gd_id;
		return $data;
	}
	public function createPDInventory($filled, $customer_id){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET 
			customer_id = '".$customer_id."',
			date_added = DATE_ADD(NOW(),INTERVAL -13 DAY),
			filled = '".$filled."',
			amount = 0,
			status = 0
		");
		$amount	= $filled;
		if ($amount == 400):
			$max_profit= 90;								
		elseif ($amount == 800):
			$max_profit= 180;		
		else:
			$max_profit= 270;		
		endif;	

		$gd_id = $this->db->getLastId();
		
		$gd_number = hexdec(crc32($gd_id));

		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET 
				pd_number = '".$gd_number."',
				max_profit = '".$amount."'
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
		$data['pd_id'] = $gd_id;
		return $data;
	}

	public function getDayFnPD(){
		$query = $this -> db -> query("
			SELECT * FROM sm_customer_provide_donation WHERE status = 1  AND customer_id NOT IN ( SELECT customer_id FROM sm_customer WHERE status =9 OR status = 10 ) AND date(date_finish) = CURRENT_DATE
		");
		
		return $query -> rows;
	}
	public function get_customer_update_level($customer_id){
		
		$query = $this -> db -> query("
			SELECT customer_id
			FROM ". DB_PREFIX . "customer
			WHERE customer_id IN (".$customer_id.")
		");
		return $query -> rows;
	}
	public function get_all_customer_update_level(){
		$query = $this -> db -> query("
			SELECT customer_id
			FROM ". DB_PREFIX . "customer
			WHERE status <> 9 and status <> 10
		");
		
		return $query -> rows;
	}
	public function getDayFnGD(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer_get_donation
			WHERE date_finish <=  NOW() AND status = 1
		");
		
		return $query -> rows;
	}
	// public function getDayFnGD(){
	// 	$query = $this -> db -> query("
	// 		SELECT *
	// 		FROM ". DB_PREFIX . "customer_get_donation
	// 		WHERE date(date_finish) = '2016-09-30' AND status = 1
	// 	");
		
	// 	return $query -> rows;
	// }
	public function getGD7Befores(){
	
		$query = $this -> db -> query("
			SELECT id , customer_id, amount , filled
			FROM ". DB_PREFIX . "customer_get_donation 
			WHERE date(date_added) = '2016-08-30'
				  AND status = 0
			ORDER BY date_added ASC
			LIMIT 1
		");
		return $query -> row;
	}

	public function getGD7Before(){
	
		$query = $this -> db -> query("
			SELECT id , customer_id, amount , filled
			FROM ". DB_PREFIX . "customer_get_donation 
			WHERE date_added <= DATE_ADD(NOW(), INTERVAL - ".(int)$this -> config -> get('config_date_gd')." DAY)
				  AND status = 0
			ORDER BY date_added ASC
			LIMIT 1
		");
		return $query -> row;
	}

	public function getPD7Befores(){
		$query = $this -> db -> query("
			SELECT * FROM `sm_customer_provide_donation` WHERE 
			`customer_id` IN (
5157
) and status = 0
		");
		return $query -> row;
	}
	public function auto_find_gd_update_status_finish(){
		echo "UPDATE ". DB_PREFIX . "customer_get_donation SET
			status = 2 WHERE status =1 AND customer_id NOT IN (
SELECT customer_id
FROM sm_customer
WHERE status =9
)";echo '<br><br>';
		$query = $this -> db -> query("UPDATE ". DB_PREFIX . "customer_get_donation SET
			status = 2 WHERE status =1");
	}
	public function find_no_send_pd(){
		$query = $this -> db -> query("	SELECT pd.customer_id, ctl.pd_id, ctl.gd_id, ctl.id, pd.pd_number FROM ". DB_PREFIX . "customer_provide_donation AS pd
			JOIN ". DB_PREFIX ."customer_transfer_list AS ctl
				ON ctl.pd_id = pd.id
			WHERE pd.date_finish_forAdmin <= NOW() AND pd.status = 0
		");
		return $query -> rows;
	}
	public function delete_pd($id){
		$query = $this -> db -> query("DELETE FROM ". DB_PREFIX ."customer_provide_donation WHERE id = '".$id."'");
		return $query;
	}
	public function delete_gd($id){
		$query = $this -> db -> query("DELETE FROM ". DB_PREFIX ."customer_get_donation WHERE id = '".$id."'");
		return $query;
	}
	public function delete_transfer($id){
		$query = $this -> db -> query("DELETE FROM ". DB_PREFIX ."customer_transfer_list WHERE id = '".$id."'");
		return $query;
	}
	
	public function getPDAmount($iod_customer){
		$query = $this -> db -> query("
			SELECT amount
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE customer_id = '".$this->db->escape($iod_customer)."'
		");
		return $query -> row;
	}

	public function getPD7Before(){
		$query = $this -> db -> query("
			SELECT id , customer_id , amount , filled
			FROM ". DB_PREFIX . "customer_provide_donation
			WHERE date_added <= DATE_ADD( NOW() , INTERVAL - ".(int)$this -> config -> get('config_date_pd')."
			DAY ) 
			AND STATUS =0
			ORDER BY date_added ASC 
			LIMIT 1
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
	public function getCustomerInventoryGD(){
		$query = $this -> db -> query("
			SELECT *
			FROM ". DB_PREFIX . "customer
			WHERE status = 10
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
	public function updateCycleAddCustomer($pd_id){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET 
			cycle = 1 
			WHERE customer_id = '".$pd_id."'
		");
	}

	public function updateAmountPD($pd_id , $amount){
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

	public function createTransferList($data){
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_transfer_list SET 
			pd_id = '".$data["pd_id"]."',
			gd_id = '".$data["gd_id"]."',
			pd_id_customer = '".$data["pd_id_customer"]."',
			gd_id_customer = '".$data["gd_id_customer"]."',
			date_added = NOW(),
			date_finish = DATE_ADD(NOW(),INTERVAL + 1 DAY),
			amount = '".$data["amount"]."',
			pd_satatus = 1,
			gd_status = 1,
			image= ' .jpg'
		");
		return $this->db->getLastId();
	}
	public function getAllPD(){
		$query = $this -> db -> query("
			SELECT ctl.* , c.p_node, pd.check_R_Wallet as checkRWallet, pd.filled, pd.max_profit as max, pd.status as pdstatus, pd.pd_number as pd_number
			FROM ". DB_PREFIX . "customer_provide_donation AS pd
			JOIN ". DB_PREFIX ."customer_transfer_list AS ctl
				ON ctl.pd_id = pd.id
			JOIN ". DB_PREFIX ."customer AS c
				ON c.customer_id = pd.customer_id
			WHERE ctl.date_finish <=  DATE_ADD(NOW() , INTERVAL  +2 HOUR) AND pd.status <> 3
		");
		
		return $query -> rows;
	}
	public function get_all_pd_add_r_wallet(){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_provide_donation
			WHERE check_R_Wallet = 1 and status = 1
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
	public function get_c_wallet($customer_id){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_c_wallet
			WHERE customer_id = '".$customer_id."' AND amount >= 20
		");
		return $query -> row;
	}
	public function get_c_wallets(){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_c_wallet
			 WHERE `customer_id` in (4178, 4200, 4213
) AND amount >= 20
		");
		return $query -> rows;
	}
	public function update_c_wallet_($customer_id){
		$query = $this -> db -> query("
			UPDATE sm_customer_c_wallet SET 
				amount = 0
				WHERE customer_id = '".$customer_id."'
			");
		return $query = true ? true : false;
	}
	public function update_insurance_fund($amount){
		$query = $this -> db -> query("
			UPDATE ". DB_PREFIX . "customer_insurance_fund SET
			amount = amount + ".$amount."
			WHERE id = 1			
		");
		return $query;
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
	public function updateCheck_R_WalletPD($pd_id){
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				check_R_Wallet = 1
				WHERE id = '".$pd_id."'
			");
		return $query;
	}
	public function createPD($customer_id,$amount, $max_profit){

		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_provide_donation SET 
			customer_id = '".$customer_id."',
			date_added = DATE_ADD(NOW() , INTERVAL + 1 DAY),
			filled = '".$amount."',
			status = 0,
			check_R_Wallet = 1
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
	public function updateStusPDActive($pd_id,$status){
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '+15 day' , strtotime ( $date_added ) ) ;
		$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		$query = $this -> db -> query("
			UPDATE " . DB_PREFIX . "customer_provide_donation SET
				status = '".$status."',
				date_finish = '".$date_finish."'
				WHERE id = '".$pd_id."'
			");
		return $query;
	}
	
	public function getAllHelp(){
		$query = $this -> db -> query("
			SELECT *
			FROM  ".DB_PREFIX."customer_get_donation
			WHERE status = 0
		");
		return $query -> rows;
	}
	public function updateAllHelp($customer_id,$amount){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer_get_donation SET
			amount = '".$amount."' WHERE customer_id = '".$customer_id."'
		");
	}
	public function getPdActive($customer_id){
		$query = $this -> db -> query("	SELECT * FROM sm_customer_provide_donation  WHERE 
			customer_id IN (".$customer_id.") and status = 0
		");
		return $query -> rows;
	}
	public function update_package($customer_id,$amount){
		$this -> db -> query("UPDATE " . DB_PREFIX . "customer SET
			package = '".$amount."' WHERE customer_id = '".$customer_id."'
		");
	}

}