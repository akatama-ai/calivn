<?php
class ControllerPdPh extends Controller {
	public function index() {
				ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
		$this->document->setTitle('Pin');
		$this->load->model('sale/customer');
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 30;
		$start = ($page - 1) * 30;

		$ts_history = $this -> model_sale_customer -> get_count_ph();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/ph', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		$data['load_pin_date'] = $this -> url -> link('pd/ph/load_pin_date&token='.$this->session->data['token']);
		$data['pin'] =  $this-> model_sale_customer->get_all_pd($limit, $start);
		$data['pagination'] = $pagination -> render();
		
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/list_ph.tpl', $data));
	}

	public function load_pin_date()
	{
		$date = date('Y-m-d',strtotime($this -> request ->post['date']));
		
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_ph_date($date);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
				<td><?php echo $value['username'] ?></td>
				<td><?php echo $value['account_holder'] ?></td>
		        <td><?php echo number_format($value['filled']) ?> VNĐ</td>
		        <td><?php 
		         if ($value['status'] == 0) {
                        echo "<span class='label label-default'>Đang chờ</span>";
                    }
                    if ($value['status'] == 1) {
                        echo "<span class='label label-info'>Khớp lệnh</span>";
                    }
                    if ($value['status'] == 2) {
                        echo "<span class='label label-success'>Hoàn thành</span>";
                    }
                    if ($value['status'] == 3) {
                        echo "<span class='label label-danger'>Báo cáo</span>";
                    }
		         ?></td>
		       
				<td><?php echo date('d/m/Y H:i',strtotime($value['date_added'])) ?></td>
				<td><span style="color:red; font-size:15px;" class="text-danger countdown" data-countdown="<?php echo $value['date_finish']; ?>">
                     </span> </td>
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
}