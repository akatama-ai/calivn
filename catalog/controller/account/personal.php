<?php
class ControllerAccountPersonal extends Controller {
	private $error = array();

	public function index() {
		if (!$this -> customer -> isLogged()) {
			$this -> session -> data['redirect'] = $this -> url -> link('account/personal', '', 'SSL');

			$this -> response -> redirect($this -> url -> link('account/login', '', 'SSL'));
		}
		$this->document->addScript('catalog/view/javascript/personal/personal.js');
		$this->document->addScript('catalog/view/javascript/personal/tree.min.js');
		if ($this -> request -> server['HTTPS']) {
			$server = $this -> config -> get('config_ssl');
		} else {
			$server = $this -> config -> get('config_url');
		}

		$data['base'] = $server;

		$this -> load -> language('account/personal');
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/personal');
		$data['lang'] = $language -> data;

		$this -> document -> setTitle($data['lang']['heading_title']);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array('text' => $this -> language -> get('text_home'), 'href' => $this -> url -> link('common/home'));

		$data['breadcrumbs'][] = array('text' => $this -> language -> get('heading_title'), 'href' => $this -> url -> link('account/personal', '', 'SSL'));

		$data['heading_title'] = $this -> language -> get('heading_title');

		$data['column_left'] = $this -> load -> controller('common/column_left');
		$data['column_right'] = $this -> load -> controller('common/column_right');
		$data['content_top'] = $this -> load -> controller('common/content_top');
		$data['content_bottom'] = $this -> load -> controller('common/content_bottom');
		$data['footer'] = $this -> load -> controller('common/footer');
		$data['header'] = $this -> load -> controller('common/header');
		$data['idCustomer'] = $this -> customer -> getId();
		$this -> load -> model('account/customer');
		$id_user = $data['idCustomer'];
		$user = $this -> model_account_customer -> getCustomer($id_user);
		$data['self'] = $this;

		$data['trees'] =  HTTPS_SERVER . 'index.php?route=account/personal/getJsonBinaryTree';
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/account/personal.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/account/personal.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/account/personal.tpl', $data));
		}
	}

	public function getInfoCustomer() {
		$id_user = $this -> request -> get['id_user'];

		$this -> load -> model('account/customer');

		$user = $this -> model_account_customer -> getCustomer($id_user);
		$json = array();
		$json['nameCustomer'] = $user['firstname'];
		$json['telephone'] = $user['telephone'];
		$json['total_left'] = $this -> model_account_customer -> getSumLeft($id_user);
		$json['total_right'] = $this -> model_account_customer -> getSumRight($id_user);
		$json['floor'] = $this -> model_account_customer -> getSumFloor($id_user);
		$json['total'] = $json['total_left'] + $json['total_right'];
		$this -> response -> addHeader('Content-Type: application/json');
		$this -> response -> setOutput(json_encode($json));

	}

	public function getJsonBinaryTree() {

		//$id_user = $this -> request -> get['id_user'];
		$id_user = $this->session->data['customer_id'];
		$this -> load -> model('account/customer');

		$user = $this -> model_account_customer -> getCustomerCustom($id_user);

		$node = new stdClass();

		$node -> id = $user['customer_id'];

		$node -> text = $user['username'] ;
		$node -> iconCls = "level1";
		
		switch (intval($user['level'])) {
			case '1':
				$type = 'black';
				break;
			
			case '2':
				$type = 'blue';
				break;
			case '3':
				$type = 'red';
				break;
			case '4':
				$type = 'darkturquoise';
				break;
			case '5':
				$type = 'chartreuse';
				break;
			case '6':
				$type = 'yellow';
				break;
			case '6':
				$type = 'cyan';
				break;
		}
$node -> type = $type;
		$this -> getBinaryChild($node);

		$node = array($node);

		//	ob_clean();

		echo json_encode($node[0]);

		exit();

	}

	public function getBinaryChild(&$node) {

		$this -> load -> model('account/customer');

		$listChild = $this -> model_account_customer -> getListChild($node -> id);

		$node -> children = array();

		foreach ($listChild as $child) {
			$childNode = new stdClass();

			$childNode -> id = $child['customer_id'];

			$childNode -> text = $child['username'];
					switch (intval($child['level'])) {
			case '1':
				$type = 'black';
				break;
			
			case '2':
				$type = 'blue';
				break;
			case '3':
				$type = 'red';
				break;
			case '4':
				$type = 'darkturquoise';
				break;
			case '5':
				$type = 'chartreuse';
				break;
			case '6':
				$type = 'yellow';
				break;
			case '6':
				$type = 'cyan';
				break;
		}
$childNode -> type = $type;
			

			$childNode -> iconCls = "level1";
			array_push($node -> children, $childNode);

			$this -> getBinaryChild($childNode);

		}
		if (count($node -> children) === 0)
			$node -> children = 0;
		return;

	}
	public function sumFloor(){
		$this->load->model('account/customer');
		$floor = $this -> model_account_customer -> getSumFloor($this -> session -> data['customer_id']);
		$floor = intval($floor);

		return $floor;
	}
	// public function countFloor($limit, $offset){

	// 	$this -> load -> model('account/customer');
	// 	$floor1 = $this->model_account_customer->getCountFloor($this -> session -> data['customer_id']);
	// 			//Level 2
	// 	if(!empty($floor1)){
	// 		$data['floor1'] = count($floor1);
	// 		$arrId='';
	// 		foreach ($floor1 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor1'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId1'] = $arrId;
	// 		$floor2 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor2'] = count($floor2);
	// 	}		//Level 3
	// 	if(!empty($floor2)){
	// 		$arrId='';
	// 		foreach ($floor2 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor2'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId2'] = $arrId;
	// 		$floor3 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor3'] = count($floor3);
	// 	}
	// 	//Level 4
	// 	if(!empty($floor3)){
	// 		$arrId='';
	// 		foreach ($floor3 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor3'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId3'] = $arrId;
	// 		$floor4 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor4'] = count($floor4);
	// 	}
	// 	//Level 5
	// 	if(!empty($floor4)){
	// 		$arrId='';
	// 		foreach ($floor4 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor4'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId4'] = $arrId;
	// 		$floor5 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor5'] = count($floor5);
	// 	}
	// 	//Level 6
	// 	if(!empty($floor5)){
	// 		$arrId='';
	// 		foreach ($floor5 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor5'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId5'] = $arrId;
	// 		$floor6 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor6'] = count($floor6);
	// 	}
	// 	//Level 7
	// 	if(!empty($floor6)){
	// 		$arrId='';
	// 		foreach ($floor6 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor6'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId6'] = $arrId;
	// 		$floor7 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor7'] = count($floor7);
	// 	}
	// 	//Level 8
	// 	if(!empty($floor7)){
	// 		$arrId='';
	// 		foreach ($floor7 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor7'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId7'] = $arrId;
	// 		$floor8 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor8'] = count($floor8);
	// 	}
	// 	//Level 9
	// 	if(!empty($floor8)){
	// 		$arrId='';
	// 		foreach ($floor8 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor8'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId8'] = $arrId;
	// 		$floor9 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor9'] = count($floor9);
	// 	}
	// 	//Level 10
	// 	if(!empty($floor9)){
	// 		$arrId='';
	// 		foreach ($floor9 as $value) {
	// 			$arrId .= ','.$value['customer_id'];
	// 		}
	// 		$arrId = substr($arrId, 1);
	// 		$json['customerFloor9'] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
	// 		$json['arrId9'] = $arrId;
			
	// 		$floor10 = $this->model_account_customer->getCountFloor($arrId);
	// 		$data['floor10'] = count($floor10);
	// 	}
	// 	return $json;
	// }
	public function countFloor($limit, $offset){

		$this -> load -> model('account/customer');
		$floor = $this->model_account_customer->getCountFloor($this -> session -> data['customer_id']);

		$totalFloor = intval($this -> sumFloor());
		
		for ($i=1; $i <= $totalFloor; $i++) { 

			if(!empty($floor)){
				$data['floor'.$i] = count($floor);
				$arrId='';
				foreach ($floor as $value) {
					$arrId .= ','.$value['customer_id'];
				}
				$arrId = substr($arrId, 1);
				$json['customerFloor'.$i] = $this -> model_account_customer -> getCustomerFloor($arrId, $limit, $offset);
				$json['arrId'.$i] = $arrId;
				$floor = $this->model_account_customer->getCountFloor($arrId);
				$data['floor'.$i] = count($floor);
			}
		}

		return $json;
	}
	public function getParrent($customer_id){
		$this -> load -> model('account/customer');
		$parrent = $this -> model_account_customer ->getParrent($customer_id);
		return $parrent;
	}
	public function get_datepd($customer_id){
		$this -> load -> model('account/customer');
		$parrent = $this -> model_account_customer ->get_date_deposite($customer_id);
		return $parrent;
	}
	public function checkPD($customer_id){
		$this->load->model('account/customer');
		$rows = $this -> model_account_customer -> checkPD($customer_id);
		$count = count($rows) > 0 ? 1 : 2;
		return $count;
	}
	public function getPD($customer_id){
		$this->load->model('account/customer');
		$rows = $this -> model_account_customer -> getPDLimit1($customer_id);
		if (!empty($rows)) {
			$PD = $rows['filled'];
		}else{
			$PD = 0;
		}
		
		return $PD;
	}
	public function customerFloor(){
		$this -> load -> language('account/personal');
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/personal');
		$lang = $language -> data;

		$limits = 10;
		
		if (isset($this -> request -> get['prev'])) {
			$limits = intval($this -> request -> get['prev'])-10;
		}
		if (isset($this -> request -> get['next'])) {
			$limits = intval($this -> request -> get['next'])+10;
		}
		if ($limits == 0) {
			$limits = 10;
		}

		$page = intval($limits)/10;
		
		$limit = 10;
		
		$start = ($page - 1) * 10;
		
		$customerFloor = $this -> countFloor($limit,$start);
	
		$floor = $this -> request -> get['floor'];
		$totalFloor = intval($this -> sumFloor());
		for ($i=1; $i <= $totalFloor; $i++) { 
			if ($floor == 'floor'.$i) {
				$arrId = $customerFloor['arrId'.$i];
			}
		}

		// if ($floor == 'floor1') {
		// 	$arrId = $customerFloor['arrId1'];
		// }
		// if ($floor == 'floor2') {
		// 	$arrId = $customerFloor['arrId2'];
		// }
		// if ($floor == 'floor3') {
		// 	$arrId = $customerFloor['arrId3'];
		// }
		// if ($floor == 'floor4') {
		// 	$arrId = $customerFloor['arrId4'];
		// }
		// if ($floor == 'floor5') {
		// 	$arrId = $customerFloor['arrId5'];
		// }
		// if ($floor == 'floor6') {
		// 	$arrId = $customerFloor['arrId6'];
		// }
		// if ($floor == 'floor7') {
		// 	$arrId = $customerFloor['arrId7'];
		// }
		// if ($floor == 'floor8') {
		// 	$arrId = $customerFloor['arrId8'];
		// }
		// if ($floor == 'floor9') {
		// 	$arrId = $customerFloor['arrId9'];
		// }
		
		$ts_floor = $this -> model_account_customer -> getTotalCustomerFloor($arrId);
		$ts_floor = $ts_floor['number'];
		
		//Floor 1
		for ($i=1; $i <= $totalFloor; $i++) { 
			if ($floor == 'floor'.$i) {
				if (!empty($customerFloor['customerFloor'.$i])) {
					
					$fl = 0;
					
					$customerFloor = $customerFloor['customerFloor'.$i];

					//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
					$fl = $fl.$i;
					$fl = '';
					$fl .=' <h3 class="panel-title">Floor '.$i.' ('.$ts_floor.')</h3>';
					$fl .= '<table id="datatable" class="table table-striped table-bordered">';
			        $fl .= '   <thead>';
			        $fl .= '      <tr>';
					
					$fl .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl .= '           <th>'.$lang["Name"].'</th>';  
				// $fl .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl .= '           <th>'.$lang["Parrent"].'</th>';
				$fl .= '           <th>DEPOSITE</th>';
				$fl .= '           <th>STATUS</th>';
				$fl .= '           <th>DATE DEPOSITE</th>';
			        $fl .= '      </tr>';
			        $fl .= '       </thead>';
					$fl .= '<tbody>';
					$count = 1;
					foreach ($customerFloor as $key => $value) {
						$fl .= '<tr>';
						$fl .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';
					
					// $fl .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					
					$fl .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">Active</span>' : '<span class="text-warning">Waiting</span>').'</td>';
					$fl .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">'.date("d/m/Y", strtotime($this->get_datepd($value['customer_id']))).'</span>' : '<span class="text-warning">Waiting</span>').'</td>';

						$fl .= '</tr>';
						$count++;
					}
					$fl .= '</tbody>';
					$fl .= '</table>';
					$fl .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
					$fl .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
						<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
					
					$json['fl'.$i] = $fl;
					$fl = null;
				}
			}
		}
	

		$this -> response -> setOutput(json_encode($json));
	}


	/*
	public function customerFloor(){
		$this -> load -> language('account/personal');
		$this -> load -> model('account/customer');
		$getLanguage = $this -> model_account_customer -> getLanguage($this -> session -> data['customer_id']);
		$language = new Language($getLanguage);
		$language -> load('account/personal');
		$lang = $language -> data;

		$limits = 10;
		
		if (isset($this -> request -> get['prev'])) {
			$limits = intval($this -> request -> get['prev'])-10;
		}
		if (isset($this -> request -> get['next'])) {
			$limits = intval($this -> request -> get['next'])+10;
		}
		if ($limits == 0) {
			$limits = 10;
		}

		$page = intval($limits)/10;
		
		$limit = 10;
		
		$start = ($page - 1) * 10;
		
		$customerFloor = $this -> countFloor($limit,$start);
	
		$floor = $this -> request -> get['floor'];
		if ($floor == 'floor1') {
			$arrId = $customerFloor['arrId1'];
		}
		if ($floor == 'floor2') {
			$arrId = $customerFloor['arrId2'];
		}
		if ($floor == 'floor3') {
			$arrId = $customerFloor['arrId3'];
		}
		if ($floor == 'floor4') {
			$arrId = $customerFloor['arrId4'];
		}
		if ($floor == 'floor5') {
			$arrId = $customerFloor['arrId5'];
		}
		if ($floor == 'floor6') {
			$arrId = $customerFloor['arrId6'];
		}
		if ($floor == 'floor7') {
			$arrId = $customerFloor['arrId7'];
		}
		if ($floor == 'floor8') {
			$arrId = $customerFloor['arrId8'];
		}
		if ($floor == 'floor9') {
			$arrId = $customerFloor['arrId9'];
		}
		
		$ts_floor = $this -> model_account_customer -> getTotalCustomerFloor($arrId);
		$ts_floor = $ts_floor['number'];
		
		//Floor 1
		if ($floor == 'floor1') {
			if (!empty($customerFloor['customerFloor1'])) {
				$customerFloor1 = $customerFloor['customerFloor1'];
				//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
				$fl1 = '';
				$fl1 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 1 ('.$ts_floor.')</h3>';
				$fl1 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl1 .= '   <thead>';
		        $fl1 .= '      <tr>';
				$fl1 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl1 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl1 .= '           <th>'.$lang["Name"].'</th>';  
				$fl1 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl1 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl1 .= '           <th>PD</th>';
				$fl1 .= '           <th>STATUS</th>';
		        $fl1 .= '      </tr>';
		        $fl1 .= '       </thead>';
				$fl1 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor1 as $key => $value) {
					$fl1 .= '<tr>';
					$fl1 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl1 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl1 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';
					
					$fl1 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl1 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl1 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					
					$fl1 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';

				
					$fl1 .= '</tr>';
					$count++;
				}
				$fl1 .= '</tbody>';
				$fl1 .= '</table>';
				$fl1 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl1 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl1'] = $fl1;
				$fl1 = null;
			}
		}

		if ($floor == 'floor2') {
		//Floor 2
			if (!empty($customerFloor['customerFloor2'])) {
				$customerFloor2 = $customerFloor['customerFloor2'];
				//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
				$fl2 = '';
				$fl2 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 2('.$ts_floor.')</h3>';
				$fl2 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl2 .= '   <thead>';
		        $fl2 .= '      <tr>';
				$fl2 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl2 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl2 .= '           <th>'.$lang["Name"].'</th>';  
				$fl2 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl2 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl2 .= '           <th>PD</th>';
					$fl2 .= '           <th>STATUS</th>';
		        $fl2 .= '      </tr>';
		        $fl2 .= '       </thead>';
				$fl2 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor2 as $key => $value) {
					$fl2 .= '<tr>';
					$fl2 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl2 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl2 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';
					
					$fl2 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl2 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl2 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					$fl2 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
					
					$fl2 .= '</tr>';
					$count++;
				}
				$fl2 .= '</tbody>';
				$fl2 .= '</table>';
				$fl2 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl2 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl2'] = $fl2;
				$fl2 = null;
			}
		}
		//Floor 3
		if ($floor == 'floor3') {
			
		
			if (!empty($customerFloor['customerFloor3'])) {
				$customerFloor3 = $customerFloor['customerFloor3'];
				//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
				$fl3 = '';
				$fl3 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 3('.$ts_floor.')</h3>';
				$fl3 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl3 .= '   <thead>';
		        $fl3 .= '      <tr>';
				$fl3 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl3 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl3 .= '           <th>'.$lang["Name"].'</th>';  
				$fl3 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl3 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl3 .= '           <th>PD</th>';
					$fl3 .= '           <th>STATUS</th>';
		        $fl3 .= '      </tr>';
		        $fl3 .= '       </thead>';
				$fl3 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor3 as $key => $value) {
					$fl3 .= '<tr>';
					$fl3 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl3 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl3 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';
				
					$fl3 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl3 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl3 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					$fl3 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
					
					$fl3 .= '</tr>';
					$count++;
				}
				$fl3 .= '</tbody>';
				$fl3 .= '</table>';
				$fl3 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl3 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl3'] = $fl3;
				$fl3 = null;
			}
		}
		//Floor 4
		if ($floor == 'floor4') {
			
		
			if (!empty($customerFloor['customerFloor4'])) {
			$customerFloor4 = $customerFloor['customerFloor4'];
			//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
			$fl4 = '';
			$fl4 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 4('.$ts_floor.')</h3>';
			$fl4 .= '<table id="datatable" class="table table-striped table-bordered">';
	        $fl4 .= '   <thead>';
	        $fl4 .= '      <tr>';
			$fl4 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
			$fl4 .= '           <th>'.$lang["USERNAME"].'</th>';
			$fl4 .= '           <th>'.$lang["Name"].'</th>';  
			$fl4 .= '           <th>'.$lang["LEVEL"].'</th>';
			$fl4 .= '           <th>'.$lang["Parrent"].'</th>';
			$fl4 .= '           <th>PD</th>';
				$fl4 .= '           <th>STATUS</th>';
	        $fl4 .= '      </tr>';
	        $fl4 .= '       </thead>';
			$fl4 .= '<tbody>';
			$count = 1;
			foreach ($customerFloor4 as $key => $value) {
				$fl4 .= '<tr>';
				$fl4 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
				$fl4 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
				$fl4 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';
			
				$fl4 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
				$fl4 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
				$fl4 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
				$fl4 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
				
				$fl4 .= '</tr>';
				$count++;
			}

				$fl4 .= '</tbody>';
				$fl4 .= '</table>';
				$fl4 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl4 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl4'] = $fl4;
				$fl4 = null;
			}
		}
		//Floor 5
		if ($floor == 'floor5') {

				if (!empty($customerFloor['customerFloor5'])) {
				$customerFloor5 = $customerFloor['customerFloor5'];
				//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
				$fl5 = '';
				$fl5 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 5('.$ts_floor.')</h3>';
				$fl5 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl5 .= '   <thead>';
		        $fl5 .= '      <tr>';
				$fl5 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl5 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl5 .= '           <th>'.$lang["Name"].'</th>';  
				$fl5 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl5 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl5 .= '           <th>PD</th>';
					$fl5 .= '           <th>STATUS</th>';
		        $fl5 .= '      </tr>';
		        $fl5 .= '       </thead>';
				$fl5 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor5 as $key => $value) {
					$fl5 .= '<tr>';
					$fl5 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl5 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl5 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';

					$fl5 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl5 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl5 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					$fl5 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
					
					$fl5 .= '</tr>';
					$count++;
				}
				$fl5 .= '</tbody>';
				$fl5 .= '</table>';
				
				$fl5 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl5 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl5'] = $fl5;
				$fl5 = null;
			}
		}
		//Floor 6
		if ($floor == 'floor6') {
			
	
				if (!empty($customerFloor['customerFloor6'])) {
				$customerFloor6 = $customerFloor['customerFloor6'];
				//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
				$fl6 = '';
				$fl6 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 6('.$ts_floor.')</h3>';
				$fl6 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl6 .= '   <thead>';
		        $fl6 .= '      <tr>';
				$fl6 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl6 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl6 .= '           <th>'.$lang["Name"].'</th>';  
				$fl6 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl6 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl6 .= '           <th>PD</th>';
					$fl6 .= '           <th>STATUS</th>';
		        $fl6 .= '      </tr>';
		        $fl6 .= '       </thead>';
				$fl6 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor6 as $key => $value) {
					$fl6 .= '<tr>';
					$fl6 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl6 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl6 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';

					$fl6 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl6 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl6 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					$fl6 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
					
					$fl6 .= '</tr>';
					$count++;
				}
				$fl6 .= '</tbody>';
				$fl6 .= '</table>';
				$fl6 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl6 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl6'] = $fl6;
				$fl6 = null;
			}
		}
		//Floor 7
		if ($floor == 'floor7') {
			
				
				if (!empty($customerFloor['customerFloor7'])) {
				$customerFloor7 = $customerFloor['customerFloor7'];
				//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
				$fl7 = '';
				$fl7 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 7('.$ts_floor.')</h3>';
				$fl7 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl7 .= '   <thead>';
		        $fl7 .= '      <tr>';
				$fl7 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl7 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl7 .= '           <th>'.$lang["Name"].'</th>';  
				$fl7 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl7 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl7 .= '           <th>PD</th>';
				$fl7 .= '           <th>STATUS</th>';
		        $fl7 .= '      </tr>';
		        $fl7 .= '       </thead>';
				$fl7 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor7 as $key => $value) {
					$fl7 .= '<tr>';
					$fl7 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl7 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl7 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';
			
					$fl7 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl7 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl7 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					$fl7 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
					
					$fl7 .= '</tr>';
					$count++;
				}
				$fl7 .= '</tbody>';
				$fl7 .= '</table>';
				
				$fl7 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl7 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl7'] = $fl7;
				$fl7 = null;
			}
		}
		//Floor 8
		if ($floor == 'floor8') {
		
			if (!empty($customerFloor['customerFloor8'])) {
				$customerFloor8 = $customerFloor['customerFloor8'];
				$fl8 = '';
				$fl8 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 8('.$ts_floor.')</h3>';
				$fl8 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl8 .= '   <thead>';
		        $fl8 .= '      <tr>';
				$fl8 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl8 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl8 .= '           <th>'.$lang["Name"].'</th>';  
				$fl8 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl8 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl8 .= '           <th>PD</th>';
				$fl8 .= '           <th>STATUS</th>';
		        $fl8 .= '      </tr>';
		        $fl8 .= '       </thead>';
				$fl8 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor8 as $key => $value) {
					$fl8 .= '<tr>';
					$fl8 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl8 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl8 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';
		
					$fl8 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl8 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl8 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					$fl8 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
					
					$fl8 .= '</tr>';
					$count++;
				}
				$fl8 .= '</tbody>';
				$fl8 .= '</table>';
				$fl8 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl8 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl8'] = $fl8;
				$fl8 = null;
			}
		}
		//Floor 8
		if ($floor == 'floor9') {
			
		
				if (!empty($customerFloor['customerFloor9'])) {
				$customerFloor9 = $customerFloor['customerFloor9'];
				//echo "<pre>"; print_r($customerFloor1); echo "</pre>"; die();
				$fl9 = '';
				$fl9 .=' <h3 class="panel-title">'.$lang["FLOOR"].' 9('.$ts_floor.')</h3>';
				$fl9 .= '<table id="datatable" class="table table-striped table-bordered">';
		        $fl9 .= '   <thead>';
		        $fl9 .= '      <tr>';
				$fl9 .= '       	<th class="text-center">'.$lang["NO"].'</th>';
				$fl9 .= '           <th>'.$lang["USERNAME"].'</th>';
				$fl9 .= '           <th>'.$lang["Name"].'</th>';  
				$fl9 .= '           <th>'.$lang["LEVEL"].'</th>';
				$fl9 .= '           <th>'.$lang["Parrent"].'</th>';
				$fl9 .= '           <th>PD</th>';
				$fl9 .= '           <th>STATUS</th>';
		        $fl9 .= '      </tr>';
		        $fl9 .= '       </thead>';
				$fl9 .= '<tbody>';
				$count = 1;
				foreach ($customerFloor9 as $key => $value) {
					$fl9 .= '<tr>';
					$fl9 .= '<td data-title="'.$lang["NO"].'" align="center">'.$count.'</td>';
					$fl9 .= '<td data-title="'.$lang["USERNAME"].'">'.$value['name'].'</td>';
					$fl9 .= '<td data-title="'.$lang["Name"].'">'.$value['fullname'].'</td>';

					$fl9 .= '<td data-title="'.$lang["LEVEL"].'">Vip '.($value['level']-1).'</td>';
					$fl9 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getParrent($value['p_node']).'</td>';
					$fl9 .= '<td data-title="'.$lang["Parrent"].'">'.$this -> getPD($value['customer_id']).'</td>';
					$fl9 .= '<td data-title="PD">'.(intval($this -> checkPD($value['customer_id'])) === 1 ? '<span class="text-success">true</span>' : '<span class="text-danger">false</span>').'</td>';
					
					$fl9 .= '</tr>';
					$count++;
				}
				$fl9 .= '</tbody>';
				$fl9 .= '</table>';
				$fl9 .= '<button id="Prev" type="button" class="btn btn-primary">Preview</button>'; 
				$fl9 .= '<input id="next_page" type="hidden" name="next" value="'.$limits.'">
					<button id="Next" type="button" class="btn btn-primary">Next</button>'; 
				
				$json['fl9'] = $fl9;
				$fl9 = null;
			}
		}

		$this -> response -> setOutput(json_encode($json));
	}
 */
}
