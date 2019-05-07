<?php
class ControllerExtensionModuleSociallogin extends Controller {
	private $error = array();

	public function install(){
		$this->load->model('module/sociallogin');
		$this->model_module_sociallogin->install();
	}

	public function uninstall(){
		$this->load->model('module/sociallogin');
		$this->model_module_sociallogin->uninstall();
	}

	public function index() {
		$this->load->language('extension/module/sociallogin');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sociallogin', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		if(isset($this->session->data['success'])){
			$data['success'] =  $this->session->data['success'];
			unset($this->session->data['success']);
		}
        $data['text_edit'] = 'Изменить';
		$data['heading_title'] = $this->language->get('heading_title');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_facebook'] = $this->language->get('tab_facebook');
		$data['tab_vkontakte'] = $this->language->get('tab_vkontakte');
		$data['tab_twitter'] = $this->language->get('tab_twitter');

		$data['text_description'] = $this->language->get('text_description');
		$data['text_help'] = $this->language->get('text_help');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_vkontakte_appid'] = $this->language->get('entry_vkontakte_appid');
		$data['entry_vkontakte_appsecret'] = $this->language->get('entry_vkontakte_appsecret');

		$data['entry_facebook_appid'] = $this->language->get('entry_facebook_appid');
		$data['entry_facebook_appsecret'] = $this->language->get('entry_facebook_appsecret');

        $data['entry_twitter_appid'] = $this->language->get('entry_twitter_appid');
        $data['entry_twitter_appsecret'] = $this->language->get('entry_twitter_appsecret');

		$data['button_save'] = $this->language->get('button_save_go');
		$data['button_save_stay'] = $this->language->get('button_save_stay');
		$data['button_cancel'] = $this->language->get('button_cancel');

		//============================================

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}



  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/sociallogin', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('extension/module/sociallogin', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension/module', 'token=' . $this->session->data['token'], 'SSL');

		// VKONTAKTE SETTINGS

		if (isset($this->request->post['sociallogin_vkontakte_status'])) {
			$data['sociallogin_vkontakte_status'] = $this->request->post['sociallogin_vkontakte_status'];
		} elseif ($this->config->get('sociallogin_vkontakte_status')) {
			$data['sociallogin_vkontakte_status'] = $this->config->get('sociallogin_vkontakte_status');
		} else {
			$data['sociallogin_vkontakte_status'] = 0;
		}

		if (isset($this->request->post['sociallogin_vkontakte_appid'])) {
			$data['sociallogin_vkontakte_appid'] = $this->request->post['sociallogin_vkontakte_appid'];
		} elseif ($this->config->get('sociallogin_vkontakte_appid')) {
			$data['sociallogin_vkontakte_appid'] = $this->config->get('sociallogin_vkontakte_appid');
		} else {
			$data['sociallogin_vkontakte_appid'] = '';
		}

		if (isset($this->request->post['sociallogin_vkontakte_appsecret'])) {
			$data['sociallogin_vkontakte_appsecret'] = $this->request->post['sociallogin_vkontakte_appsecret'];
		} elseif ($this->config->get('sociallogin_vkontakte_appsecret')) {
			$data['sociallogin_vkontakte_appsecret'] = $this->config->get('sociallogin_vkontakte_appsecret');
		} else {
			$data['sociallogin_vkontakte_appsecret'] = '';
		}

		// FACEBOOK SETTINGS

		if (isset($this->request->post['sociallogin_facebook_status'])) {
			$data['sociallogin_facebook_status'] = $this->request->post['sociallogin_facebook_status'];
		} elseif ($this->config->get('sociallogin_facebook_status')) {
			$data['sociallogin_facebook_status'] = $this->config->get('sociallogin_facebook_status');
		} else {
			$data['sociallogin_facebook_status'] = 0;
		}


		if (isset($this->request->post['sociallogin_facebook_appid'])) {
			$data['sociallogin_facebook_appid'] = $this->request->post['sociallogin_facebook_appid'];
		} elseif ($this->config->get('sociallogin_facebook_appid')) {
			$data['sociallogin_facebook_appid'] = $this->config->get('sociallogin_facebook_appid');
		} else {
			$data['sociallogin_facebook_appid'] = '';
		}

		if (isset($this->request->post['sociallogin_facebook_appsecret'])) {
			$data['sociallogin_facebook_appsecret'] = $this->request->post['sociallogin_facebook_appsecret'];
		} elseif ($this->config->get('sociallogin_facebook_appsecret')) {
			$data['sociallogin_facebook_appsecret'] = $this->config->get('sociallogin_facebook_appsecret');
		} else {
			$data['sociallogin_facebook_appsecret'] = '';
		}

        // TWITTER SETTINGS

        if (isset($this->request->post['sociallogin_twitter_status'])) {
            $data['sociallogin_twitter_status'] = $this->request->post['sociallogin_twitter_status'];
        } elseif ($this->config->get('sociallogin_twitter_status')) {
            $data['sociallogin_twitter_status'] = $this->config->get('sociallogin_twitter_status');
        } else {
            $data['sociallogin_twitter_status'] = 0;
        }


        if (isset($this->request->post['sociallogin_twitter_appid'])) {
            $data['sociallogin_twitter_appid'] = $this->request->post['sociallogin_twitter_appid'];
        } elseif ($this->config->get('sociallogin_twitter_appid')) {
            $data['sociallogin_twitter_appid'] = $this->config->get('sociallogin_twitter_appid');
        } else {
            $data['sociallogin_twitter_appid'] = '';
        }

        if (isset($this->request->post['sociallogin_twitter_appsecret'])) {
            $data['sociallogin_twitter_appsecret'] = $this->request->post['sociallogin_twitter_appsecret'];
        } elseif ($this->config->get('sociallogin_twitter_appsecret')) {
            $data['sociallogin_twitter_appsecret'] = $this->config->get('sociallogin_twitter_appsecret');
        } else {
            $data['sociallogin_twitter_appsecret'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/sociallogin', $data));






		//========================================

//		$this->template = 'module/sociallogin.tpl';
//		$this->children = array(
//			'common/header',
//			'common/footer'
//		);

//		$this->response->setOutput($this->render());

	}


	private function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/sociallogin')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }



		if( !empty( $this->request->post['sociallogin_vkontakte_appid'] ) )	{
			$this->request->post['sociallogin_vkontakte_appid'] = trim($this->request->post['sociallogin_vkontakte_appid']);
		}

		if( !empty( $this->request->post['sociallogin_vkontakte_appsecret'] ) ){
			$this->request->post['sociallogin_vkontakte_appsecret'] = trim($this->request->post['sociallogin_vkontakte_appsecret']);
		}

		if( !empty( $this->request->post['sociallogin_facebook_appid'] ) ){
			$this->request->post['sociallogin_facebook_appid'] = trim($this->request->post['sociallogin_facebook_appid']);
		}

		if( !empty( $this->request->post['sociallogin_facebook_appsecret'] ) ){
			$this->request->post['sociallogin_facebook_appsecret'] = trim($this->request->post['sociallogin_facebook_appsecret']);
		}

		if( !empty( $this->request->post['sociallogin_module'] ) ){
			$this->request->post['sociallogin_module'] = $this->request->post['sociallogin_module'];
		}else{
			$this->request->post['sociallogin_module'] = '';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>