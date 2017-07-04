<?php
class ModelPdPd extends Model {
	public function getTotalCustomers() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getTotalProvide($status) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_provide_donation WHERE status = '" . (int)$status . "'");
		return $query->row['total'];
	}
	public function getTotalStatusProvide($status, $customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_provide_donation WHERE status = '" . (int)$status . "' AND customer_id = '".(int)$customer_id."'");
		return $query->row['total'];
	}
	
	public function getAllProfitByType($type) {
		$query = $this->db->query("SELECT SUM(receive) AS total FROM " . DB_PREFIX . "profit WHERE  type_profit in (".$type.")");
		return $query->row['total'];
	}
	public function get_total_gd_current_date($status){
		$query = $this->db->query("SELECT COUNT(*) as total
			FROM ".DB_PREFIX."customer_get_donation WHERE status = ".$status."");
		return $query->row['total'];
	}
	public function get_count_gh(){

		$query = $this -> db -> query("
			SELECT count(*) as number
			FROM  ".DB_PREFIX."customer_get_donation  WHERE customer_id NOT IN (SELECT customer_id FROM sm_customer WHERE status = 9 OR status = 10)
		");
		return $query -> row;
	}
	public function get_all_gd_current_date($status,$limit, $offset){
		$date_added= date('Y-m-d H:i:s') ;
		$date_finish = strtotime ( '-1 day' , strtotime ( $date_added ) ) ;
			$date_finish= date('Y-m-d H:i:s',$date_finish) ;
		if ($status) {
			switch ($status) {
				case 1:
					$status = 0;
					break;
				case 2:
					$status = 1;
					break;
				default:
					$status = 2;
					break;
			}
			$query = $this->db->query("SELECT c.username, c.account_holder, gd.*
			FROM ".DB_PREFIX."customer_get_donation gd JOIN ".DB_PREFIX."customer  c
			ON gd.customer_id = c.customer_id WHERE gd.status = ".$status." WHERE gd.customer_id NOT IN (SELECT customer_id FROM sm_customer WHERE status = 9 OR status = 10) LIMIT ".$limit."
			OFFSET ".$offset."");
		return $query->rows;
		}else{
			$query = $this->db->query("SELECT c.username, c.account_holder, gd.*
			FROM ".DB_PREFIX."customer_get_donation gd JOIN ".DB_PREFIX."customer  c
			ON gd.customer_id = c.customer_id WHERE gd.customer_id NOT IN (SELECT customer_id FROM sm_customer WHERE status = 9 OR status = 10) LIMIT ".$limit."
			OFFSET ".$offset."");
		return $query->rows;
		}
		
	}

	public function get_total_pd_current_date($status){
		$query = $this->db->query("SELECT COUNT(*) as total
			FROM ".DB_PREFIX."customer_provide_donation WHERE date(date_added)=CURRENT_DATE AND status = ".$status."");
		return $query->row['total'];
	}
	
	public function total_btc(){
		$query = $this->db->query("SELECT SUM(filled) as total FROM `sm_customer_get_donation`
		 WHERE status = 2 and customer_id IN (SELECT customer_id FROM sm_customer WHERE status = 9)");
		return $query -> row['total'];
	}

	public function getTotalCustomersNewLast() {
		$date = strtotime(date('Y-m-d'));
		$year = date('Y',$date);
		$month = date('m',$date);
		if($month == 1){
			$year = $year - 1;
			$month = 12;
		}else{
			$month = $month - 1;
		}
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE YEAR(`date_added`) = '".$year."' AND MONTH(`date_added`) = '".$month."'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getTotalCustomersNew() {
		$date = strtotime(date('Y-m-d'));
		$year = date('Y',$date);
		$month = date('m',$date);

		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE YEAR(`date_added`) = '".$year."' AND MONTH(`date_added`) = '".$month."'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getTotalCustomersOff() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE status = 8";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function onlineToday(){
		$date = date('Y-m-d');
		$total = 0;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity` WHERE `key` = 'login' and `date_added` >= '".$date." 00:00:00' and `date_added` <='".$date." 23:59:59' GROUP BY customer_id");
		if (isset($query->rows)) {
			$total = count($query->rows);
		}
		return $total;
	}
	public function onlineYesterday(){
		$date = date('Y-m-d',strtotime( '-1 days' ));
		$total = 0;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity` WHERE `key` = 'login' and `date_added` >= '".$date." 00:00:00' and `date_added` <='".$date." 23:59:59' GROUP BY customer_id");
		if (isset($query->rows)) {
			$total = count($query->rows);
		}
		return $total;
	}
	
	public function onlineAll(){
		$total = 0;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity` WHERE `key` = 'login' GROUP BY customer_id");
		if (isset($query->rows)) {
			$total = count($query->rows);
		}
		return $total;
	}
	public function getDayFnPD($date = false){
		if ($date) {
			$query = $this -> db -> query("
			SELECT A.*, c.username, c.account_holder, c.bank_name, c.account_number, c.branch_bank FROM  ".DB_PREFIX."customer_provide_donation A LEFT JOIN  ".DB_PREFIX."customer c ON A.customer_id= c.customer_id WHERE A.status = 1  AND A.customer_id NOT IN ( SELECT customer_id FROM sm_customer WHERE status =9 OR status = 10 ) AND date(A.date_finish) = '".$date."'
			");
		}else{
			$query = $this -> db -> query("
			SELECT A.*, c.username,c.account_holder, c.bank_name, c.account_number, c.branch_bank FROM  ".DB_PREFIX."customer_provide_donation A LEFT JOIN  ".DB_PREFIX."customer c ON A.customer_id= c.customer_id WHERE A.status = 1  AND A.customer_id NOT IN ( SELECT customer_id FROM sm_customer WHERE status =9 OR status = 10 ) AND date(A.date_finish) = CURRENT_DATE
		");
		}
		
		
		return $query -> rows;
	}
}