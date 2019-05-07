<?php
class ControllerExtensionModuleJobs extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/jobs');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

//            print_r($this->request->post);
//            die();

            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('jobs', $this->request->post);
            } else {
                $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['module_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_job_name'] = $this->language->get('entry_job_name');
        $data['entry_job_description'] = $this->language->get('entry_job_description');
        $data['entry_module_description'] = $this->language->get('entry_module_description');
        $data['entry_bic'] = $this->language->get('entry_bic');
        $data['entry_rs'] = $this->language->get('entry_rs');
        $data['entry_status'] = $this->language->get('entry_status');



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
        if (isset($this->error['job_name'])) {
            $data['error_job_name'] = $this->error['lob_name'];
        } else {
            $data['error_job_name'] = '';
        }

        if (isset($this->error['bic'])) {
            $data['error_bic'] = $this->error['bic'];
        } else {
            $data['error_bic'] = '';
        }

        if (isset($this->error['rs'])) {
            $data['error_rs'] = $this->error['rs'];
        } else {
            $data['error_rs'] = '';
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
                'href' => $this->url->link('extension/module/jobs', 'token=' . $this->session->data['token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/jobs', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/jobs', 'token=' . $this->session->data['token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/jobs', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
//            print_r($module_info);
//            die();


            $module_name = array_shift($module_info);
            $module_description = array_shift($module_info);
            foreach($module_info as $key => $item){
                foreach($item as $numb => $location_attribute){
                    $module_info[$numb][$key] = $location_attribute;
                }
                unset($module_info[$key]);
            }
        }else{
            $module_info = [];
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_name;
        } else {
            $data['name'] = '';
        }
        if (isset($this->request->post['module_description'])) {
            $data['module_description'] = $this->request->post['module_description'];
        } elseif (!empty($module_info)) {
            $data['module_description'] = $module_description;
        } else {
            $data['module_description'] = '';
        }

        $data['module_info'] = $module_info;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        if(!isset($this->request->get['module_id'])){
            $data['key'] = 1;
        }else{
            $last_key = key(array_slice($module_info,-1,1,TRUE));
            $data['key'] = $last_key + 1;
        }

        $this->response->setOutput($this->load->view('extension/module/jobs', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/jobs')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }


        return !$this->error;
    }

    public function ajaxHtmlHelper(){

        $this->load->language('extension/module/jobs');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_job_name'] = $this->language->get('entry_job_name');
        $data['entry_job_description'] = $this->language->get('entry_job_description');
        $data['entry_bic'] = $this->language->get('entry_bic');
        $data['entry_rs'] = $this->language->get('entry_rs');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['i'] = $this->request->get['row_id'];

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
        if (isset($this->error['job_name'])) {
            $data['error_job_name'] = $this->error['job_name'];
        } else {
            $data['error_job_name'] = '';
        }

        if (isset($this->error['bic'])) {
            $data['error_bic'] = $this->error['bic'];
        } else {
            $data['error_bic'] = '';
        }

        if (isset($this->error['rs'])) {
            $data['error_rs'] = $this->error['rs'];
        } else {
            $data['error_rs'] = '';
        }


        $this->response->setOutput($this->load->view('extension/module/intent_ajax_jobs', $data));
    }
}