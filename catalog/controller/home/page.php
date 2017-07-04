<?php
class ControllerHomePage extends Controller {
	public function index() {

		$data['base'] = HTTPS_SERVER;
		$data['self'] = $this;
		if (file_exists(DIR_TEMPLATE . $this -> config -> get('config_template') . '/template/home/page.tpl')) {
			$this -> response -> setOutput($this -> load -> view($this -> config -> get('config_template') . '/template/home/page.tpl', $data));
		} else {
			$this -> response -> setOutput($this -> load -> view('default/template/home/page.tpl', $data));
		}
	}
}