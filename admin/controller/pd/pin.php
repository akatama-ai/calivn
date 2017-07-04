<?php
class ControllerPdPin extends Controller {
	public function index() {

		$this->document->setTitle('Pin');
		$this->load->model('sale/customer');
		$page = isset($this -> request -> get['page']) ? $this -> request -> get['page'] : 1;

		$limit = 10;
		$start = ($page - 1) * 10;

		$ts_history = $this -> model_sale_customer -> get_count_code();

		$ts_history = $ts_history['number'];

		$pagination = new Pagination();
		$pagination -> total = $ts_history;
		$pagination -> page = $page;
		$pagination -> limit = $limit;
		$pagination -> num_links = 5;
		$pagination -> text = 'text'; 
		$pagination -> url = $this -> url -> link('pd/pin', 'page={page}&token='.$this->session->data['token'].'', 'SSL');
		$data['load_pin_date'] = $this -> url -> link('pd/pin/load_pin_date&token='.$this->session->data['token']);
		$data['pin'] =  $this-> model_sale_customer->get_all_code($limit, $start);
		$data['pagination'] = $pagination -> render();
		
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('pd/pin.tpl', $data));
	}

	public function load_pin_date()
	{
		$date = date('Y-m-d',strtotime($this -> request ->post['date']));
		
		$this->load->model('sale/customer');
		$load_pin_date = $this -> model_sale_customer -> load_pin_date($date);
		$stt = 0;
		if (count($load_pin_date) > 0)
		{


			foreach ($load_pin_date as $value) { $stt++;?>
		?>
			<tr>
		        <td><?php echo $stt; ?></td>
				<td><?php echo $value['username'] ?></td>
				<td><?php echo $value['input_address'] ?></td>
		        <td><?php echo $value['pin'] ?></td>
		        <td><?php echo ($value['confirmations'] == 0) ? "<span class='label label-warning'>Đang chờ</span>" : "<span class='label label-success'>Đã Chuyển</span>" ?></td>
		       
				<td><?php echo date('d/m/Y H:i',strtotime($value['date_created'])) ?></td>
				
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