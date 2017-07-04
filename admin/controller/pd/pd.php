<?php
class ControllerPdPd extends Controller {
	public function index() {
		$this->document->setTitle('Provide Donation');
		$this->load->model('pd/pd');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		

		if (isset($this->request->get['filter_status'])) {
				$status = $this->request->get['filter_status'];
				$data['filter_status'] = $this->request->get['filter_status'];
			
		} else{
			$status = null;
			$data['filter_status'] = null;
		}
		// echo "<pre>"; print_r($status); echo "</pre>"; die();
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 30;
		$start = ($page - 1) * 30;
		$ts_history = $this -> model_pd_pd -> get_count_gh();

		$ts_history = intval($ts_history['number']);
		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/pd', 'page={page}&token='.$this->session->data['token'].'', 'SSL');

		$data['allGd'] = $this -> model_pd_pd -> get_all_gd_current_date($status, $limit, $start);
		$data['pagination'] = $pagination -> render();
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/pd.tpl', $data));
	}
	public function finish($value='')
	{
		$this->document->setTitle('PD Finish');
		$this->load->model('pd/pd');
		$data['allPD'] = $this -> model_pd_pd -> getDayFnPD();
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			$url = '';

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			$this->response->redirect($this->url->link('pd/pd', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$data['load_pd_finish'] = $this -> url -> link('pd/pd/load_pd_finish&token='.$this->session->data['token']);

		// echo "<pre>"; print_r($status); echo "</pre>"; die();
	

		
		
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/pd-finish.tpl', $data));
	}

	public function load_pd_finish($value='')
	{
		$date = date('Y-m-d',strtotime($this -> request ->post['date']));
		
		$this->load->model('pd/pd');
		$load_pin_date = $this -> model_pd_pd -> getDayFnPD($date);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
                    <td><?php echo $stt; ?></td>
                    <td><?php echo $value['customer_id'] ?></td>
                    <td><?php echo $value['username'] ?></td>
                    <td><?php echo $value['account_holder'] ?></td>
                    <td><?php echo $value['bank_name'] ?></td>
                    <td><?php echo $value['account_number'] ?></td>
                    <td><?php echo $value['branch_bank'] ?></td>
                    <td><?php echo number_format($value['filled']) ?> USD</td>
                    <td><?php echo number_format($value['max_profit']) ?> USD</td>
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
                    <td><?php echo date('d/m/Y H:i',strtotime($value['date_finish'])) ?></td>
                </tr>  
	               
		<?php 
			}
		}
	
		else
		{
		?>
		<tr><td colspan="6" class="text-center">Không có dữ liệu</td> </tr>
		<?php
		}
	}
	
	public function export_finish($value='')
	{
		

		$link = HTTPS_SERVER.'index.php?route=pd/pd/exportpd&date='.$_POST['date'].'&token='.$this->session->data['token'];
		echo $link;
	}	

	public function exportpd() {
	
		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		/** Include PHPExcel */
		require_once dirname(__FILE__) . '/PHPExcel.php';

				$this->load->language('sale/customer');
				$this->load->model('sale/customer');	
				$this->load->model('pd/pd');
				$date = false;
				if (($this->request->server['REQUEST_METHOD'] == 'GET')) {
					$date = date('Y-m-d',strtotime($this -> request ->get['date']));
				}
				$results = $this -> model_pd_pd -> getDayFnPD($date);
				
					//echo "<pre>"; print_r($results); echo "</pre>"; die();
					//echo "<pre>"; print_r($results); echo "</pre>"; die();
				!count($results) > 0 && die('no data!');
				foreach ($results as $key => $result) {
			
					$customers[] = array(
						'customer_id'    => $result['customer_id'],
						'username'  => $result['username'],
						'account_holder'  => $result['account_holder'],
						'bank_name'  => $result['bank_name'],
						'account_number'  => $result['account_number'],
						'branch_bank'    => $result['branch_bank'],
						'filled'    => $result['filled'],
						'max_profit'    => $result['max_profit'],
						'date_added'    => date('d/m/Y',strtotime($result['date_added'])),
						'date_finish'    => date('d/m/Y',strtotime($result['date_finish']))
					);
				}



// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();


// Set document properties
			$objPHPExcel->getProperties()->setCreator("Hoivien")
							 ->setLastModifiedBy("Hoivien")
							 ->setTitle("Office 2007 XLSX".$this->language->get('heading_title'))
							 ->setSubject("Office 2007 XLSX".$this->language->get('heading_title'))
							 ->setDescription($this->language->get('heading_title'))
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'STT')
            ->setCellValue('B1', 'UserID')
            ->setCellValue('C1', 'Username')
            ->setCellValue('D1', 'Ho Ten')
            ->setCellValue('E1', 'Ngan Hang')
            ->setCellValue('F1', 'So Tai Khoan')
            ->setCellValue('G1', 'Chi Nhanh')
            ->setCellValue('H1', 'Goi Dau Tu')
            ->setCellValue('I1', 'Loi Nhuan')
            ->setCellValue('J1', 'Ngay Tao')
            ->setCellValue('K1', 'Ngay Ket Thuc');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
			$h=0;
			$n = 2;
			foreach ($customers as $customer) {
				$h++;		
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$n,$h);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$n,$customer['customer_id']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$n,$customer['username']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$n,$customer['account_holder']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$n,$customer['bank_name']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$n,$customer['account_number']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$n,$customer['branch_bank']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$n,$customer['filled']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$n,$customer['max_profit']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$n,$customer['date_added']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$n,$customer['date_finish']);
				$n++;	
			}
			$objPHPExcel->getActiveSheet()->getStyle('N'.$n.':'.'P'.$n)
			->applyFromArray(
				array('font'  => array(
					'bold'  => true,
					'size'  => 12,
					'name'  => 'Arial'
				))
			);

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle($this->language->get('heading_title'));


			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);


			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="LISTPD.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
			$this->response->redirect($this->url->link('pd/pd/finish', 'token=' . $this->session->data['token'] . $url, 'SSL'));

	}

}