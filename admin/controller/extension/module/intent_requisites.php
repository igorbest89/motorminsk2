<?php
class ControllerExtensionModuleIntentRequisites extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/intent_requisites');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

//            print_r($this->request->post);
//            die();

            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('intent_requisites', $this->request->post);
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
        $data['entry_requisite_name'] = $this->language->get('entry_requisite_name');
        $data['entry_bic'] = $this->language->get('entry_bic');
        $data['entry_rs'] = $this->language->get('entry_rs');
        $data['entry_image'] = $this->language->get('entry_image');
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
        if (isset($this->error['requisite_name'])) {
            $data['error_requisite_name'] = $this->error['requisite_name'];
        } else {
            $data['error_requisite_name'] = '';
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
                'href' => $this->url->link('extension/module/intent_brands', 'token=' . $this->session->data['token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/intent_brands', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/intent_requisites', 'token=' . $this->session->data['token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/intent_requisites', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);


        $this->load->model('tool/image');


            $module_name = array_shift($module_info);
            foreach($module_info as $key => $item){
                foreach($item as $numb => $location_attribute){
                    $module_info[$numb][$key] = $location_attribute;
                }
                unset($module_info[$key]);
            }
        }

        $data['token'] = $this->session->data['token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_name;
        } else {
            $data['name'] = '';
        }


        if (isset($this->request->get['module_id'])) {
            foreach ($module_info as $key => $requisite){
                if (isset($requisite['image']) && is_file(DIR_IMAGE . $requisite['image'])) {
                    $module_info[$key]['thumb'] = $this->model_tool_image->resize($requisite['image'], 100, 100);
                } else {
                    $module_info[$key]['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
                }
            }

        }
        $data['module_info'] = $module_info;



//        if (isset($this->request->post['requisite_name'])) {
//            $data['requisite_name'] = $this->request->post['requisite_name'];
//        } elseif (!empty($module_info)) {
//            $data['requisite_name'] = $module_info['requisite_name'];
//        } else {
//            $data['requisite_name'] = '';
//        }
//
//        if (isset($this->request->post['bic'])) {
//            $data['bic'] = $this->request->post['bic'];
//        } elseif (!empty($module_info)) {
//            $data['bic'] = $module_info['bic'];
//        } else {
//            $data['bic'] = '';
//        }
//
//        if (isset($this->request->post['rs'])) {
//            $data['rs'] = $this->request->post['rs'];
//        } elseif (!empty($module_info)) {
//            $data['rs'] = $module_info['rs'];
//        } else {
//            $data['rs'] = '';
//        }
//        if (isset($this->request->post['image'])) {
//            $data['image'] = $this->request->post['image'];
//        } elseif (!empty($module_info)) {
//            $data['image'] = $module_info['image'];
//        } else {
//            $data['image'] = '';
//        }
//
//        $this->load->model('tool/image');
//
//        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
//            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
//        } elseif (!empty($module_info) && is_file(DIR_IMAGE . $module_info['image'])) {
//            $data['thumb'] = $this->model_tool_image->resize($module_info['image'], 100, 100);
//        } else {
//            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
//        }
//
//
//
//        if (isset($this->request->post['status'])) {
//            $data['status'] = $this->request->post['status'];
//        } elseif (!empty($module_info)) {
//            $data['status'] = $module_info['status'];
//        } else {
//            $data['status'] = '';
//        }



        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['i'] = 1;
        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);


        $this->response->setOutput($this->load->view('extension/module/intent_requisites', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/intent_requisites')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }


        return !$this->error;
    }
    public function ajaxHtmlHelper(){

        $this->load->language('extension/module/intent_requisites');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_product'] = $this->language->get('entry_product');
        $data['entry_requisite_name'] = $this->language->get('entry_requisite_name');
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
        if (isset($this->error['requisite_name'])) {
            $data['error_requisite_name'] = $this->error['requisite_name'];
        } else {
            $data['error_requisite_name'] = '';
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
        $this->load->model('tool/image');
        $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        $data['status'] = '';
        $data['i'] = $this->request->get['row_id'] + 1;

        $this->response->setOutput($this->load->view('extension/module/intent_ajax_requisite', $data));
    }
}