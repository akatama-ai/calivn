<?php
class ModelAccountAutopdgd extends Model {

	public function createGD($amount, $customer_id,$date_added){
		echo "INSERT INTO ". DB_PREFIX . "customer_get_donation SET 
			customer_id = '".$customer_id."',
			date_added = '".$date_added."',
			amount = '".$amount."',
			status = 0";
			echo '<br>';
		$this -> db -> query("
			INSERT INTO ". DB_PREFIX . "customer_get_donation SET 
			customer_id = '".$customer_id."',
			date_added = '".$date_added."',
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
				date_added = '".$date_added."'
				WHERE customer_id = '".$customer_id."'
			");
		}
		$data['query'] = $query ? true : false;
		$data['gd_number'] = $gd_number;
		$data['gd_id'] = $gd_id;
		return $data;
	}
	// public function get_r_wallet($arrId){
	// 	$query = $this->db->query("
	// 	SELECT c.username,r.* FROM `sm_customer_r_wallet` r JOIN sm_customer c on r.customer_id = c.customer_id WHERE amount > 0 and r.customer_id IN (".$arrId.")");
	// 	return $query->rows;

	// }
	public function get_r_wallet(){
		$query = $this->db->query("	SELECT c.username,r.* 
			FROM `sm_customer_r_wallet` r JOIN sm_customer c 
			ON r.customer_id = c.customer_id WHERE  amount > 0");
		return $query->rows;
	}
	// public function get_c_wallet($arrId){
	// 	$query = $this->db->query("
	// 	SELECT c.username,r.* FROM `sm_customer_c_wallet` r 
	// 	JOIN sm_customer c on r.customer_id = c.customer_id 
	// 	WHERE amount >= 2000000 and r.customer_id IN (".$arrId.")");
	// 	return $query->rows;

	// }
	public function get_c_wallet(){
		$query = $this->db->query("SELECT c.username,r.* FROM `sm_customer_c_wallet` r JOIN sm_customer c 
			ON r.customer_id = c.customer_id  WHERE 
r.customer_id IN (SELECT customer_id FROM `sm_customer` WHERE `username` IN (
'hungphuong',
'hungphuong1',
'tuyetvan1',
'tuyetvan2',
'Mailuu',
'Mailuu02',
'Mailuu03',
'Mailuu04',
'Mailuu05',
'ngohuong',
'dangthai1',
'dangthai2',
'dangthai3',
'dangthai4',
'non',
'non1',
'non2',
'non3',
'non4',
'Kimquanh',
'Bichtuyen',
'Bichtuyen1',
'Bichtuyen2',
'Haidang01',
'Haidang02',
'Haidang03',
'Haidang04',
'Haidang05'
)) and amount >= 2000000");
		return $query->rows;

	}
	public function get_all_c_wallet(){
		$query = $this->db->query("
		SELECT * FROM `sm_customer_c_wallet` 
		WHERE amount >= 2000000 and customer_id NOT IN (2,2161,2162,2163,2164,2165,2166,2167,2168,2169)");
		return $query->rows;
	}
	public function update_r_wallet($customer_id){
		$query = $this -> db -> query("
			UPDATE sm_customer_r_wallet SET 
				amount = 0
				WHERE customer_id = '".$customer_id."'
			");
		return $query = true ? true : false;
	}
	public function update_c_wallet($customer_id){
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
	public function get_id_gd($date){
		$query=$this->db->query("SELECT * 
FROM  `sm_customer_get_donation` 
WHERE DATE( date_added ) =  '".$date."'");
		return $query -> rows;
	}
}