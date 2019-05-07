<?php

class ControllerExtensionModuleIntentCategory extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/intent_category');


		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
//		    echo"<pre>";
//            print_r($this->request->post);
//            echo"</pre>";
//            die();
			if (!isset($this->request->get['module_id'])) {

				$this->model_extension_module->addModule('intent_category', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/intent_category', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/intent_category', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/intent_category', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/intent_category', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('catalog/category');

		$data['categories'] = array();

        if (!empty($this->request->post['category'])) {
            $categories = $this->request->post['category'];
        } elseif (!empty($module_info['category'])) {
            $categories = $module_info['category'];
        } else {
            $categories = array();
        }

        $this->load->model('tool/image');
        if (isset($this->request->get['module_id'])) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
            if(isset($module_info['category'])){
                foreach($module_info['category'] as $cat => $item){

                    if(!empty($item['image'])){
                        $thumb = $this->model_tool_image->resize($item['image'], 100, 100);
                    }else{
                        $thumb = $this->model_tool_image->resize('no_image.png', 100, 100);
                    }

                    $data['categories'][$cat] = array(
                        'name'           => $item['name'],
                        'href'           => $item['href'],
                        'thumb'          => $thumb,
                        'image'          => $item['image'],
                        'sub_categories' => $item['sub_categories'],
                    );

                }
            }
        }else{
            $data['categories'] = array();
        }
        $data['i'] = count($data['categories']);


		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/intent_category', $data));
	}

	protected function validate() {

//        echo "<pre>";
//        print_r($this->request->post);
//        echo "</pre>";
//        die();

		if (!$this->user->hasPermission('modify', 'extension/module/intent_category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

    public function ajaxHtmlHelper()
    {
        $this->load->model('tool/image');
        $data['categories'] = array();
        $data['limit'] = 5;
        $data['status'] = '';
        $data['text_enabled'] = 'Enabled';
        $data['text_disabled'] = 'Disabled';
        $data['i'] = $this->request->get['row_id'];
        $data['thumb'] = $this->model_tool_image->resize('no_image.png', 50, 50);
        $this->response->setOutput($this->load->view('extension/module/intent_ajax_category', $data));

    }

}
