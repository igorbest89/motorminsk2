<?php
/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
*/

include_once(DIR_SYSTEM . 'library/simple/simple_controller.php');

class ControllerCheckoutSimpleCheckoutSummary extends SimpleController {
    private $_templateData = array();

    public function index() {

        $this->loadLibrary('simple/simplecheckout');

        $this->simplecheckout = SimpleCheckout::getInstance($this->registry);

        $version = $this->simplecheckout->getOpencartVersion();

        $this->language->load('checkout/cart');
        $this->language->load('checkout/simplecheckout');

        $get_route = isset($_GET['route']) ? $_GET['route'] : (isset($_GET['_route_']) ? $_GET['_route_'] : '');

        if ($get_route == 'checkout/simplecheckout_summary') {
            $this->simplecheckout->init('customer');
            $this->simplecheckout->init('payment_address');
            $this->simplecheckout->init('payment');
            $this->simplecheckout->init('shipping_address');
            $this->simplecheckout->init('shipping');
        }

        $this->load->model('tool/image');

        if ($version >= 200) {
            $this->load->model('tool/upload');
        }

        $this->loadLibrary('encryption');

        $this->_templateData['column_image']                  = $this->language->get('column_image');
        $this->_templateData['column_name']                   = $this->language->get('column_name');
        $this->_templateData['column_model']                  = $this->language->get('column_model');
        $this->_templateData['column_quantity']               = $this->language->get('column_quantity');
        $this->_templateData['column_price']                  = $this->language->get('column_price');
        $this->_templateData['column_total']                  = $this->language->get('column_total');
        $this->_templateData['text_summary']                  = $this->language->get('text_summary');
        $this->_templateData['text_summary_comment']          = $this->language->get('text_summary_comment');
        $this->_templateData['text_summary_shipping_address'] = $this->language->get('text_summary_shipping_address');
        $this->_templateData['text_summary_payment_address']  = $this->language->get('text_summary_payment_address');
        $this->_templateData['text_until_cancelled']          = $this->language->get('text_until_cancelled');
        $this->_templateData['text_freq_day']                 = $this->language->get('text_freq_day');
        $this->_templateData['text_freq_week']                = $this->language->get('text_freq_week');
        $this->_templateData['text_freq_month']               = $this->language->get('text_freq_month');
        $this->_templateData['text_freq_bi_month']            = $this->language->get('text_freq_bi_month');
        $this->_templateData['text_freq_year']                = $this->language->get('text_freq_year');
        $this->_templateData['text_trial']                    = $this->language->get('text_trial');
        $this->_templateData['text_recurring']                = $this->language->get('text_recurring');
        $this->_templateData['text_length']                   = $this->language->get('text_length');
        $this->_templateData['text_recurring_item']           = $this->language->get('text_recurring_item');
        $this->_templateData['text_payment_profile']          = $this->language->get('text_payment_profile');

        $this->_templateData['button_update'] = $this->language->get('button_update');

        $this->_templateData['products'] = array();

        $products = $this->cart->getProducts();

        $points_total = 0;

        foreach ($products as $product) {

            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            $option_data = array();

            foreach ($product['option'] as $option) {
                if ($version >= 200) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }
                } else {
                    if ($option['type'] != 'file') {
                        $value = $option['option_value'];
                    } else {
                        $encryption = new Encryption($this->config->get('config_encryption'));
                        $option_value = $encryption->decrypt($option['option_value']);
                        $filename = substr($option_value, 0, strrpos($option_value, '.'));
                        $value = $filename;
                    }
                }

                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }

            if ($product['image']) {
                $image_cart_width = $this->config->get('config_image_cart_width');
                $image_cart_width = $image_cart_width ? $image_cart_width : 40;
                $image_cart_height = $this->config->get('config_image_cart_height');
                $image_cart_height = $image_cart_height ? $image_cart_height : 40;
                $image = $this->model_tool_image->resize($product['image'], $image_cart_width, $image_cart_height);
            } else {
                $image = '';
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $price = $this->simplecheckout->formatCurrency($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
            } else {
                $price = false;
            }

            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                $total = $this->simplecheckout->formatCurrency($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
            } else {
                $total = false;
            }

            if ($version >= 200) {
                $recurring = '';

                if ($product['recurring']) {
                    $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                    );

                    if ($product['recurring']['trial']) {
                        $recurring = sprintf($this->language->get('text_trial_description'), $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
                    }

                    if ($product['recurring']['duration']) {
                        $recurring .= sprintf($this->language->get('text_payment_description'), $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    } else {
                        $recurring .= sprintf($this->language->get('text_payment_until_canceled_description'), $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
                    }
                }

                $attribute_groups = $this->model_catalog_product->getProductAttributes($product['product_id']);
                foreach ($attribute_groups as $attribute_group){
                    if($attribute_group['name'] == 'Технические'){
                        $data['technical_attributes'] = $attribute_group;
                    }elseif ($attribute_group['name']  == 'Описательные'){
                        $data['common_attributes'] = $attribute_group;
                    }
                }

                if(isset($data['common_attributes'])){
                    foreach ($data['common_attributes']['attribute'] as $attribute){
                        if($attribute['name'] == 'Ед. изм.')
                            $data['unit'] = $attribute['text'];
                    }
                }

                $this->_templateData['products'][] = array(
                    'attr'      => $data['unit'],
                    'key'       => isset($product['key']) ? $product['key'] : '',
                    'cart_id'   => isset($product['cart_id']) ? $product['cart_id'] : '',
                    'thumb'     => $image,
                    'name'      => $product['name'],
                    'model'     => $product['model'],
                    'option'    => $option_data,
                    'recurring' => $recurring,
                    'quantity'  => $product['quantity'],
                    'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
                    'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
                    'price'     => $price,
                    'total'     => $total,
                    'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            } elseif ($version >= 156) {
                $profile_description = '';

                if ($product['recurring']) {
                    $frequencies = array(
                        'day'        => $this->language->get('text_day'),
                        'week'       => $this->language->get('text_week'),
                        'semi_month' => $this->language->get('text_semi_month'),
                        'month'      => $this->language->get('text_month'),
                        'year'       => $this->language->get('text_year'),
                    );

                    if ($product['recurring_trial']) {
                        $recurring_price = $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring_trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));
                        $profile_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring_trial_cycle'], $frequencies[$product['recurring_trial_frequency']], $product['recurring_trial_duration']) . ' ';
                    }

                    $recurring_price = $this->simplecheckout->formatCurrency($this->tax->calculate($product['recurring_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')));

                    if ($product['recurring_duration']) {
                        $profile_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    } else {
                        $profile_description .= sprintf($this->language->get('text_payment_until_canceled_description'), $recurring_price, $product['recurring_cycle'], $frequencies[$product['recurring_frequency']], $product['recurring_duration']);
                    }
                }

                $this->_templateData['products'][] = array(
                    'key'                 => $product['key'],
                    'thumb'               => $image,
                    'name'                => $product['name'],
                    'model'               => $product['model'],
                    'option'              => $option_data,
                    'quantity'            => $product['quantity'],
                    'stock'               => $product['stock'],
                    'reward'              => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'               => $price,
                    'total'               => $total,
                    'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                    'recurring'           => $product['recurring'],
                    'profile_name'        => $product['profile_name'],
                    'profile_description' => $profile_description,
                );
            } else {
                $this->_templateData['products'][] = array(
                    'key'      => $product['key'],
                    'thumb'    => $image,
                    'name'     => $product['name'],
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'stock'    => $product['stock'],
                    'reward'   => ($product['reward'] ? sprintf($this->language->get('text_reward'), $product['reward']) : ''),
                    'price'    => $price,
                    'total'    => $total,
                    'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }

            if ($product['points']) {
                $points_total += $product['points'];
            }
        }

        // Gift Voucher
        $this->_templateData['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $key => $voucher) {
                $this->_templateData['vouchers'][] = array(
                    'key'         => $key,
                    'description' => $voucher['description'],
                    'amount'      => $this->simplecheckout->formatCurrency($voucher['amount'])
                );
            }
        }

        $totals = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->_templateData['modules'] = array();

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            if ($version < 200 || $version >= 300) {
                $this->load->model('setting/extension');

                $results = $this->model_setting_extension->getExtensions('total');
            } else {
                $this->load->model('extension/extension');

                $results = $this->model_extension_extension->getExtensions('total');
            }

            foreach ($results as $key => $result) {
                if ($version < 300) {
                    $sort_order[$key] = $this->config->get($result['code'] . '_sort_order');
                } else {
                    $sort_order[$key] = $this->config->get('total_' . $result['code'] . '_sort_order');
                }                
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($version < 300) {
                    $status = $this->config->get($result['code'] . '_status');
                } else {
                    $status = $this->config->get('total_' . $result['code'] . '_status');
                }

                if ($status) {
                    $this->simplecheckout->loadModel('total/' . $result['code']);

                    if ($version < 220) {
                        $this->{'model_total_' . $result['code']}->getTotal($totals, $total, $taxes);
                    } else {
                        $this->{'model_total_' . $result['code']}->getTotal($total_data);
                    }

                    $this->_templateData['modules'][$result['code']] = true;
                }
            }

            $sort_order = array();

            foreach ($totals as $key => $value) {
                $sort_order[$key] = $value['sort_order'];

                if (!isset($value['text'])) {
                    $totals[$key]['text'] = $this->simplecheckout->formatCurrency($value['value']);
                }
            }

            array_multisort($sort_order, SORT_ASC, $totals);
        }









        $origin_total = 0;
        foreach ($products as $product){
            $origin_price = $this->cart->getProductOriginalPrice($product['product_id']);
            $origin_total  += $origin_price * $product['quantity'];

        }
        $subtotal = 0;
        foreach ($totals as $total){
            if($total['code'] == 'sub_total'){
                $subtotal = $total['value'];
            }
        }
        $different = $origin_total - $subtotal;
        if(is_float($different)){
            $different = round($different,2);
        }
        if($origin_total != 0){
            $client_special = 100 - ( ($subtotal * 100 ) / $origin_total);
        }




        $totals[] = [
            'code' => 'custom_total',
            'title' => 'Клиентская скидка',
            'value' => $origin_total,
            'sort_order' => '2',
            'text' => '-'. $different,

        ];

        $this->_templateData['totals'] = $totals;

        $points = $this->customer->getRewardPoints();
        $points_to_use = $points > $points_total ? $points_total : $points;
        $this->_templateData['points'] = $points_to_use;

        $this->_templateData['reward']  = isset($this->session->data['reward']) ? $this->session->data['reward'] : '';
        $this->_templateData['voucher'] = isset($this->session->data['voucher']) ? $this->session->data['voucher'] : '';
        $this->_templateData['coupon']  = isset($this->session->data['coupon']) ? $this->session->data['coupon'] : '';

        $customer = $this->session->data['simple']['customer'];

        $payment_address = $this->session->data['simple']['payment_address'];

        $payment_address_format = $this->simplecheckout->getAddressFormat(array(
            'payment_code' => !empty($this->session->data['payment_method']) && !empty($this->session->data['payment_method']['code']) ? $this->session->data['payment_method']['code'] : '',
            'payment_address_format' => $payment_address['address_format']
        ), 'payment');

        if (!empty($payment_address_format)) {
            $format = $payment_address_format;
        } else {
            $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
        }

        $find = array(
            '{firstname}',
            '{lastname}',
            '{company}',
            '{address_1}',
            '{address_2}',
            '{city}',
            '{postcode}',
            '{zone}',
            '{zone_code}',
            '{country}',
            '{company_id}',
            '{tax_id}'
        );

        $replace = array(
            'firstname'  => $payment_address['firstname'],
            'lastname'   => $payment_address['lastname'],
            'company'    => $payment_address['company'],
            'address_1'  => $payment_address['address_1'],
            'address_2'  => $payment_address['address_2'],
            'city'       => $payment_address['city'],
            'postcode'   => $payment_address['postcode'],
            'zone'       => $payment_address['zone'],
            'zone_code'  => $payment_address['zone_code'],
            'country'    => $payment_address['country'],
            'company_id' => isset($payment_address['company_id']) ? $payment_address['company_id'] : '',
            'tax_id'     => isset($payment_address['tax_id']) ? $payment_address['tax_id'] : ''
        );

        foreach($payment_address as $id => $value) {
            if (isset($replace[$id]) || $id == 'address_format' || $id == 'custom_field') {
                continue;
            }

            if ($this->simplecheckout->isListField($id)) {
                $value = $this->simplecheckout->convertOptionsValueToText($id, $value);
            }

            if (strpos($id, 'payment_') === 0) {
                $id = str_replace('payment_', '', $id);
                $find[] = '{'.$id.'}';
                $replace[$id] = $value;
            } elseif (strpos($id, 'shipping_') === false) {
                $find[] = '{'.$id.'}';
                $replace[$id] = $value;
            }
        }

        foreach($customer as $id => $value) {
            if (isset($replace[$id]) || $id == 'address_format' || $id == 'custom_field') {
                continue;
            }

            if ($this->simplecheckout->isListField($id)) {
                $value = $this->simplecheckout->convertOptionsValueToText($id, $value);
            }
           
            $find[] = '{'.$id.'}';
            $replace[$id] = $value;
        }

        foreach($replace as $id => $value) {
            if ($this->simplecheckout->isListField($id)) {
                $replace[$id] = $this->simplecheckout->convertOptionsValueToText($id, $value);
            }
        }

        $this->_templateData['summary_payment_address'] = trim(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))));

        $this->_templateData['summary_shipping_address'] = '';

        if ($this->cart->hasShipping()) {



            $session = $this->session->data;

            $ship_method = (isset($session['shipping_method']['title'])) ? $session['shipping_method']['title'] : '';
            $payment_method = (isset($session['payment_method']['title'])) ? $session['payment_method']['title'] : '';

            $ship_cost = (isset($session['shipping_method']['cost'])) ? $session['shipping_method']['cost'] : '';
            $ship_city = (isset($session['simple']['shipping']['field20'])) ? $session['simple']['shipping']['field20'] : '';
            $ship_postcode = (isset($session['simple']['shipping']['field21'])) ? $session['simple']['shipping']['field21'] : '';
            $ship_delivery_address = (isset($session['simple']['shipping']['field22'])) ? $session['simple']['shipping']['field22'] : '';
            $ship_stock_address = (isset($session['simple']['shipping']['field23'])) ? $session['simple']['shipping']['field23'] : '';
            $ship_stock_date = (isset($session['simple']['shipping']['field24'])) ? $session['simple']['shipping']['field24'] : '';
            $ship_stock_time = (isset($session['simple']['shipping']['field25'])) ? $session['simple']['shipping']['field25'] : '';

            $client_firstname = (isset($session['shipping_address']['firstname'])) ? $session['shipping_address']['firstname'] : '';
            $client_lastname = (isset($session['shipping_address']['lastname'])) ? $session['shipping_address']['lastname'] : '';
            $client_email = (isset($session['shipping_address']['field28'])) ? $session['shipping_address']['field28'] : '';
            $client_phone = (isset($session['shipping_address']['field29'])) ? $session['shipping_address']['field29'] : '';

            $this->_templateData['ship_method'] = $ship_method;
            $this->_templateData['payment_method'] = $payment_method;

            $this->_templateData['firstname_custom'] = $client_firstname;
            $this->_templateData['lastname_custom'] = $client_lastname;
            $this->_templateData['email_custom'] = $client_email;
            $this->_templateData['phone_custom'] = $client_phone;

            $this->_templateData['ship_cost'] = $ship_cost;
            $this->_templateData['ship_city'] = $ship_city;
            $this->_templateData['ship_postcode'] = $ship_postcode;
            $this->_templateData['ship_delivery_address'] = $ship_delivery_address;

            $this->_templateData['ship_stock_address'] = $ship_stock_address;
            $this->_templateData['ship_stock_date'] = $ship_stock_date;
            $this->_templateData['ship_stock_time'] = $ship_stock_time;



            $shipping_address = $this->session->data['simple']['shipping_address'];

            $shipping_address_format = $this->simplecheckout->getAddressFormat(array(
                'shipping_code' => !empty($this->session->data['shipping_method']) && !empty($this->session->data['shipping_method']['code']) ? $this->session->data['shipping_method']['code'] : '',
                'shipping_address_format' => $shipping_address['address_format']
            ), 'shipping');

            if (!empty($shipping_address_format)) {
                $format = $shipping_address_format;
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}',
                '{company_id}',
                '{tax_id}'
            );

            $replace = array(
                'firstname'  => $shipping_address['firstname'],
                'lastname'   => $shipping_address['lastname'],
                'company'    => $shipping_address['company'],
                'address_1'  => $shipping_address['address_1'],
                'address_2'  => $shipping_address['address_2'],
                'city'       => $shipping_address['city'],
                'postcode'   => $shipping_address['postcode'],
                'zone'       => $shipping_address['zone'],
                'zone_code'  => $shipping_address['zone_code'],
                'country'    => $shipping_address['country'],
                'company_id' => isset($shipping_address['company_id']) ? $shipping_address['company_id'] : '',
                'tax_id'     => isset($shipping_address['tax_id']) ? $shipping_address['tax_id'] : ''
            );

            foreach($shipping_address as $id => $value) {
                if (isset($replace[$id]) || $id == 'address_format' || $id == 'custom_field') {
                    continue;
                }

                if (strpos($id, 'shipping_') === 0) {
                    $id = str_replace('shipping_', '', $id);
                    $find[] = '{'.$id.'}';
                    $replace[$id] = $value;
                } elseif (strpos($id, 'payment_') === false) {
                    $find[] = '{'.$id.'}';
                    $replace[$id] = $value;
                }
            }

            foreach($customer as $id => $value) {
                if (isset($replace[$id]) || $id == 'address_format' || $id == 'custom_field') {
                    continue;
                }

                $find[] = '{'.$id.'}';
                $replace[$id] = $value;
            }

            foreach($replace as $id => $value) {
                if ($this->simplecheckout->isListField($id)) {
                    $replace[$id] = $this->simplecheckout->convertOptionsValueToText($id, $value);
                }
            }

            $this->_templateData['summary_shipping_address'] = trim(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))));
        }


        if(isset($client_special)){
            $this->_templateData['client_special'] = round($client_special);
        }
        $this->_templateData['origin_total'] = $origin_total;
        $this->_templateData['different'] = $different;


        $this->_templateData['display_header'] = $this->simplecheckout->getSettingValue('displayHeader', 'summary');
        $this->_templateData['display_products'] = $this->simplecheckout->getSettingValue('displayProducts', 'summary');
        $this->_templateData['display_totals_block'] = $this->simplecheckout->getSettingValue('displayTotalsBlock', 'summary');
        $this->_templateData['display_totals'] = $this->simplecheckout->getSettingValue('displayTotals', 'summary');
        $this->_templateData['display_address'] = $this->simplecheckout->getSettingValue('displayAddress', 'summary');
        $this->_templateData['display_comment'] = $this->simplecheckout->getSettingValue('displayComment', 'summary');

        if (!is_array($this->_templateData['display_totals'])) {
            $this->_templateData['display_totals'] = array();
        }

        if (empty($this->_templateData['display_totals'])) {
            $this->_templateData['display_totals_block'] = false;
        }

        $this->_templateData['summary_comment'] = trim($this->simplecheckout->getComment());

        if (empty($this->_templateData['summary_comment'])) {
            $this->_templateData['display_comment'] = false;
        }

        $this->_templateData['hide'] = $this->simplecheckout->isBlockHidden('summary');
        
        if ($this->simplecheckout->isBlockHidden('payment_address')) {
            $this->_templateData['summary_payment_address'] = '';
        }

        if ($this->simplecheckout->isBlockHidden('shipping_address')) {
            $this->_templateData['summary_shipping_address'] = '';
        }

        if (empty($this->_templateData['summary_payment_address']) && empty($this->_templateData['summary_shipping_address'])) {
            $this->_templateData['display_address'] = false;
        }

        if (!$this->_templateData['display_products'] && !$this->_templateData['display_totals_block'] && !$this->_templateData['display_comment'] && !$this->_templateData['display_address']) {            
            $this->_templateData['hide'] = true;
        }

        $this->setOutputContent($this->renderPage('checkout/simplecheckout_summary', $this->_templateData));
    }
}
