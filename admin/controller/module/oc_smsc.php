<?php

class ControllerModuleOCSMSC extends Controller
{
	public $order_statuses, $status_id_message;
	private $data, $path_ext;

	public function index()
	{
		$this->path_ext = 'extension/' . (VERSION >= '2.3' ? 'extension' : 'module');

		$this->_init();

		// If form is posted & receiving data is valid
		if (count($this->request->post) && isset($this->request->post['form-oc-smsc'])) {
			// Settings update
			isset($this->request->post['form-oc-smsc']);

			// Remove form id from DB config
			unset($this->request->post['form-oc-smsc']);

			// Save changes to DB
			$this->model_setting_setting->editSetting('oc_smsc', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			// Redirect into the main page
			$this->response->redirect($this->url->link($this->path_ext, 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->_view();
	}

	private function _breadcrumbs()
	{
		$breadcrumbs[] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token='.$this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$breadcrumbs[] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link($this->path_ext, 'token='.$this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
		$breadcrumbs[] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/oc_smsc', 'token='.$this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		return $breadcrumbs;
	}

	private function _init()
	{
		// Load gateway library
		require_once(DIR_SYSTEM.'library/oc_smsc/gateway.php');

		// Load settings
		$this->load->model('setting/setting');

		// Load multilanguage language tools
		$this->load->model('localisation/language');

		// Load language
		foreach ($this->load->language((VERSION >= '2.3' ? '../../language/russian/' : '').'module/oc_smsc') as $key => $value)
			$data[$key] = $value;

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		// Get saved values
		$setting = $this->model_setting_setting->getSetting('oc_smsc');

		// Set by default form_values
		foreach ($setting as $key => $value)
			$data['value_'.$key] = $value;
	}

	private function _view()
	{
		// Set title
		$this->document->setTitle($this->language->get('heading_title'));

		// Set view variables
		$data['breadcrumbs'] = $this->_breadcrumbs();

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['action'] = $this->url->link('module/oc_smsc', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link($this->path_ext, 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['oc_smsc_status']))
			$data['oc_smsc_status'] = $this->request->post['oc_smsc_status'];
		else
			$data['oc_smsc_status'] = $this->config->get('oc_smsc_status');

		// If we have a new form values from request
		foreach ($this->request->post as $key => $value)
			$data['value_'.$key] = $value;

		// Get all statuses from database oc_smsc
		$this->order_statuses = $this->db->query("SELECT order_status_id,name FROM `" . DB_PREFIX . "order_status` WHERE language_id = ".$this->config->get('config_language_id'));

		// Get messages for all statuses oc_smsc
		$this->status_id_message = $this->db->query("SELECT `key`,`value` FROM `" . DB_PREFIX . "setting` WHERE `key` LIKE 'oc_smsc_status_id_%'");

		$data['order_statuses'] = $this->order_statuses;
		$data['status_id_message'] = $this->status_id_message;
		$data['oc_smsc_textarea_customer_new_status'] = $this->config->get('oc_smsc_textarea_customer_new_status');

		// Template rendering
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/oc_smsc.tpl', $data));
	}
}
