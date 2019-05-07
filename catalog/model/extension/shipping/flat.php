<?php
class ModelExtensionShippingFlat extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/flat');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			if(isset($this->session->data['flat_choose']) && $this->session->data['flat_choose'] == 'belarus'){
			    $cost = $this->config->get('flat_cost2');
            }else{
                $cost = $this->config->get('flat_cost');
            }
//print_r($cost);
//			die();
			$quote_data['flat'] = array(
				'code'         => 'flat.flat',
				'title'        => $this->language->get('text_description'),
				'cost'         => $cost,
				'cost2'         => $this->config->get('flat_cost2'),
                'cost_minsk'         => $this->config->get('flat_cost'),
				'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => "",
                'text2'         => $this->currency->format($this->tax->calculate($this->config->get('flat_cost2'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'flat',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('flat_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}