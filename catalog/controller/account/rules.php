<?php
class ControllerAccountRules extends Controller {

    public function index() {  

    	function myCheckLoign($self) {
            return $self->customer->isLogged() ? true : false;
        };

        function myConfig($self){
            
        };
        $data['self'] = $this;
        //method to call function
        !call_user_func_array("myCheckLoign", array($this)) && $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        call_user_func_array("myConfig", array($this));  

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/rules.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/rules.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/rules.tpl', $data));
        }

    }
}