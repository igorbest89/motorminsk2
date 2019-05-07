<?php

class ControllerInformationContact extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('information/contact');

        $this->document->setTitle($this->language->get('heading_title'));


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {


            if(isset($this->request->post['copy'])){
                $this->sendEmail($this->config->get('config_email'), $this->request->post['email'], $this->request->post['name'], $this->request->post['enquiry']);
                $this->sendEmail($this->request->post['email'], $this->request->post['email'], $this->request->post['name'], $this->request->post['enquiry']);
            }
            else{
                $this->sendEmail($this->config->get('config_email'), $this->request->post['email'], $this->request->post['name'], $this->request->post['enquiry']);
            }
            $this->response->redirect($this->url->link('information/contact/success'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => 'Главная',
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/contact')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_location'] = $this->language->get('text_location');
        $data['text_store'] = $this->language->get('text_store');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_address'] = $this->language->get('text_address');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_fax'] = $this->language->get('text_fax');
        $data['text_open'] = $this->language->get('text_open');
        $data['text_comment'] = $this->language->get('text_comment');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_enquiry'] = $this->language->get('entry_enquiry');

        $data['button_map'] = $this->language->get('button_map');

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['enquiry'])) {
            $data['error_enquiry'] = $this->error['enquiry'];
        } else {
            $data['error_enquiry'] = '';
        }

        $data['button_submit'] = $this->language->get('button_submit');

        $data['action'] = $this->url->link('information/contact', '', true);

        $this->load->model('tool/image');

        if ($this->config->get('config_image')) {
            $data['image'] = $this->model_tool_image->resize($this->config->get('config_image'), $this->config->get($this->config->get('config_theme') . '_image_location_width'), $this->config->get($this->config->get('config_theme') . '_image_location_height'));
        } else {
            $data['image'] = false;
        }

        $data['store'] = $this->config->get('config_name');
        $data['address'] = nl2br($this->config->get('config_address'));
        $data['geocode'] = $this->config->get('config_geocode');
        $data['geocode_hl'] = $this->config->get('config_language');
        $data['telephone'] = $this->config->get('config_telephone');
        $data['fax'] = $this->config->get('config_fax');
        $data['open'] = nl2br($this->config->get('config_open'));
        $data['comment'] = $this->config->get('config_comment');

        $data['locations'] = array();

        $this->load->model('localisation/location');

        $store_groups = $this->model_localisation_location->getStoreGroups();
        $data['locations_groups'] = [];
        foreach ($store_groups as $store_group) {

            $locations_group_info = $this->model_localisation_location->getLocationsByStoreGroupId($store_group['store_group_id']);
//            echo '<pre>';
//            print_r($locations_group_info[0]);
//            echo '</pre>';
//            die;
            $locations = [];
            if ($locations_group_info) {


                foreach ($locations_group_info as $location_info) {
                    if ($location_info['image']) {
                        $image = $this->model_tool_image->resize($location_info['image'], $this->config->get($this->config->get('config_theme') . '_image_location_width'), $this->config->get($this->config->get('config_theme') . '_image_location_height'));
                    } else {
                        $image = false;
                    }
                    $geodata = explode(',', $location_info['geocode']);

                    $settings = json_decode($location_info['settings'], true);
                    if ($settings != '') {
                        foreach ($settings as $key => $item) {
                            foreach ($item as $numb => $location_attribute) {
                                $settings[$numb][$key] = $location_attribute;
                            }
                            unset($settings[$key]);
                        }
                    }


                    $locations[] = array(
                        'location_id' => $location_info['location_id'],
                        'name' => $location_info['name'],
                        'address' => nl2br($location_info['address']),
                        'geocode' => $location_info['geocode'],
                        'geocode_w' => $geodata[0],
                        'geocode_h' => $geodata[1],
                        'telephone' => explode(',', $location_info['telephone']),
                        'fax' => $location_info['fax'],
                        'email' => $location_info['email'],
                        'image' => $image,
                        'open' => nl2br($location_info['open']),
                        'comment' => $location_info['comment'],
                        'settings' => $settings,
                    );
                }

                $data['locations_groups'][] = [
                    'name' => $store_group['name'],
                    'locations' => $locations
                ];
            }
        }

        $data['regions'] = array_pop($data['locations_groups']);
        $data['regions'] = $data['regions']['locations'][0];


//        echo '<pre>';
//        print_r($data['regions']);
//        echo '</pre>';
//        die;


        foreach ((array)$this->config->get('config_location') as $location_id) {
            $location_info = $this->model_localisation_location->getLocation($location_id);

            if ($location_info) {
                if ($location_info['image']) {
                    $image = $this->model_tool_image->resize($location_info['image'], $this->config->get($this->config->get('config_theme') . '_image_location_width'), $this->config->get($this->config->get('config_theme') . '_image_location_height'));
                } else {
                    $image = false;
                }

                $data['locations'][] = array(
                    'location_id' => $location_info['location_id'],
                    'name' => $location_info['name'],
                    'address' => nl2br($location_info['address']),
                    'geocode' => $location_info['geocode'],
                    'telephone' => $location_info['telephone'],
                    'fax' => $location_info['fax'],
                    'image' => $image,
                    'open' => nl2br($location_info['open']),
                    'comment' => $location_info['comment']
                );
            }
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } else {
            $data['name'] = $this->customer->getFirstName();
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } else {
            $data['email'] = $this->customer->getEmail();
        }

        if (isset($this->request->post['enquiry'])) {
            $data['enquiry'] = $this->request->post['enquiry'];
        } else {
            $data['enquiry'] = '';
        }

        // Captcha
        if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
            $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'), $this->error);
        } else {
            $data['captcha'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['search'] = $this->load->controller('common/search');
        $data['back'] = $data['breadcrumbs'][count($data['breadcrumbs']) - 2]['href'];
        $data['main_email'] = $this->config->get('config_email');


        $this->response->setOutput($this->load->view('information/contact', $data));
    }

    public function sendEmail($set_to, $set_from, $set_sender_name, $set_text){

        $this->load->language('information/contact');

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($set_to);
        $mail->setFrom($set_from);
        $mail->setSender(html_entity_decode($set_sender_name, ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $set_sender_name), ENT_QUOTES, 'UTF-8'));
        $mail->setText($set_text);
        $mail->send();

    }

    protected function validate()
    {

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['enquiry']) < 5) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
            $this->error['enquiry'] = $this->language->get('error_enquiry');
        }

        // Captcha
        if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('contact', (array)$this->config->get('config_captcha_page'))) {
            $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

            if ($captcha) {
                $this->error['captcha'] = $captcha;
            }
        }

        return !$this->error;
    }

    public function success()
    {
        $this->load->language('information/contact');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('information/contact')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_message'] = $this->language->get('text_success');

        $data['button_continue'] = $this->language->get('button_continue');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('common/success', $data));
    }



    public function ajaxPost()
    {


        $this->load->language('information/contact');
        $this->load->model('localisation/location');
        $this->load->model('extension/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            if ($this->request->post['action'] == 'send_email') {

                $send_to_email = html_entity_decode($this->request->post['email'], ENT_QUOTES, 'UTF-8');
                if (!isset($this->request->post['name'])) {
                    $this->request->post['name'] = $this->config->get('config_name');
                }

                if ($this->request->post['type'] == 'req') {
                    $email_send_text = $this->concateEmailForRequisites($this->request->post['location_id']);

                } elseif($this->request->post['type'] == 'os'){
                    $email_send_text = $this->concateEmailForCorrespondense();

                }elseif($this->request->post['type'] == 'loc') {
                    $email_send_text = $this->concateEmailForLocation($this->request->post['location_id']);
                }

                $this->sendEmail($send_to_email, $this->config->get('config_email'), $this->request->post['name'], $email_send_text);

                $json = $this->url->link('information/contact/success');
                $this->response->setOutput(json_encode($json));

            }
            elseif ($this->request->post['action'] == 'send_sms') {
                if ($this->request->post['type'] == 'req') {
                    $sms = $this->concateEmailForRequisites($this->request->post['location_id']);
                } elseif($this->request->post['type'] == 'os'){
                    $sms = $this->concateEmailForCorrespondense();
                }elseif($this->request->post['type'] == 'loc') {
                    $sms = $this->concateEmailForLocation($this->request->post['location_id']);
                }
                if (true === $this->oc_smsc_init() && !empty($this->request->post['phone'])) {
                    $answer = $this->oc_smsc_gateway->send($this->config->get('oc_smsc_login'), $this->config->get('oc_smsc_password'), $this->request->post['phone'], $sms, $this->config->get('oc_smsc_signature'));

                    $answer = json_decode($answer, true);

                    if (isset($answer['error'])){
                        $json['error'] = 1;
                    }else{
                        $json['error'] = 0;
                    }

                    $this->response->setOutput(json_encode($json));
                }
            }

        }
    }

    public function concateEmailForLocation($location_id)
    {

        $this->load->language('information/contact');
        $this->load->model('localisation/location');

        $locations_info = $this->model_localisation_location->getLocation($location_id);

        $email = $locations_info['name'] . "\n";
        if ($locations_info['address']) {
            $email .= "\n Адресс: " . $locations_info['address'];
        }
        if ($locations_info['telephone']) {
            $email .= "\n Телефоны: " . $locations_info['telephone'];
        }
        if ($locations_info['fax']) {
            $email .= "\n Факс: " . $locations_info['fax'];
        }
        if ($locations_info['email']) {
            $email .= "\n Email: " . $locations_info['email'];
        }
        if ($locations_info['open']) {
            $email .= "\n Время работы: " . $locations_info['open'];
        }
        if ($locations_info['geocode']) {
            $email .= "\n Координаты геолокации: " . $locations_info['geocode'];
        }
        if ($locations_info['comment']) {
            $email .= "\n Комментарий: " . $locations_info['comment'];
        }
        if ($locations_info['settings'] != '') {

            $locations_info['settings'] = json_decode($locations_info['settings'], true);
            foreach ($locations_info['settings'] as $key => $item) {
                foreach ($item as $numb => $location_attribute) {
                    $locations_info['settings'][$numb][$key] = $location_attribute;
                }
                unset($locations_info['settings'][$key]);
            }
            foreach ($locations_info['settings'] as $key => $location) {
                $email .= "\n \n$key отделение \n";

                if ($locations_info['address']) {
                    $email .= "\n Адресс: " . $location['address_append'];
                }
                if ($locations_info['telephone']) {
                    $email .= "\n Телефоны: " . $location['telephone_append'];
                }
                if ($locations_info['fax']) {
                    $email .= "\n Факс: " . $location['fax_append'];
                }
                if ($locations_info['email']) {
                    $email .= "\n Email: " . $location['email_append'];
                }
                if ($locations_info['open']) {
                    $email .= "\n Время работы: " . $location['open_append'];
                }
                if ($locations_info['geocode']) {
                    $email .= "\n Координаты геолокации: " . $location['geocode_append'];
                }
                if ($locations_info['comment']) {
                    $email .= "\n Комментарий: " . $locations_info['comment_append'];
                }
            }
        }

        return $email;
    }

    public function concateEmailForRequisites($location_id)
    {
        $this->load->model('extension/module');
        $module_info = $this->model_extension_module->getModule($location_id);
        $module_name = array_shift($module_info);
        $module_id = array_pop($module_info);
        foreach ($module_info as $key => $item) {
            foreach ($item as $numb => $location_attribute) {
                $module_info[$numb][$key] = $location_attribute;
            }
            unset($module_info[$key]);
        }
        $email = $module_name . "\n\n";
        $email .= $this->config->get('config_name') . "\n";
        $email .= $this->config->get('config_address') . "\n\n";
        $email .= "УНП " . $this->config->get('config_unp') . "\n";
        $email .= "ОКПО " . $this->config->get('config_okpo') . "\n";

        foreach ($module_info as $requisite) {
            $email .= htmlspecialchars_decode($requisite['requisite_name']) . "\n";
            $email .= "BIC " . $requisite['bic'] . "\n";
            $email .= "Р/С " . $requisite['rs'] . "\n";
        }

        return $email;
    }

    public function concateEmailForCorrespondense()
    {
        $email = $this->config->get('config_name'). "\n\n";
        $email .= "Наш email для общей корреспонденции: ". $this->config->get('config_email') . "\n";
        $email .= "Наш адресс: ".$this->config->get('config_address');

        return $email;
    }
}
