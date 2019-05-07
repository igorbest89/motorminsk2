<?php
class OCSMSCGateway {
	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');
	}

	public function get_order_info($textarea, $order_id, $order_status_id = 0) {
		$order = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = $order_id LIMIT 1");

		$currency = $this->db->query("SELECT symbol_left,symbol_right FROM `" . DB_PREFIX . "currency` WHERE currency_id = " . $order->row['currency_id'] . " LIMIT 1");

		$total = $currency->row['symbol_left'].number_format(round($order->row['total'] * $order->row['currency_value'], 2), 2).$currency->row['symbol_right'];

		$g_list = $status->row['name'] = $st_com = '';

		if (stripos($textarea, '{GOODS_LIST}') !== false) {
			$goods_list = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE order_id = $order_id");

			$goods_list_options = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_option` WHERE order_id = $order_id");

			foreach ($goods_list->rows as $k => $v) {
				$p_glo = '';
				foreach ($goods_list_options->rows as $kp => $vp)
					if ($vp['order_product_id'] == $v['order_product_id'])
						$p_glo .= ($p_glo ? '; ' : '').$vp['name'].': '.$vp['value'];

				$g_list .= ($k ? "\n" : '').$v['name'].($p_glo ? ' ('.$p_glo.')' : '').':'.$v['model'].':'.$v['quantity'].':'.$currency->row['symbol_left'].
						number_format(round($v['total'] * $order->row['currency_value'], 2), 2).$currency->row['symbol_right'];
			}
		}

		if (stripos($textarea, '{ORDER_STATUS}') !== false)
			$status = $this->db->query("SELECT name FROM `" . DB_PREFIX . "order_status` WHERE order_status_id = " . ($order_status_id ? $order_status_id : $order->row['order_status_id']) . " AND language_id = ".$order->row['language_id'] . " LIMIT 1");

		if (stripos($textarea, '{STATUS_COMMENT}') !== false)
			$st_com = isset($_POST['comment']) ? $_POST['comment'] : '';

		$macros = array('~\{ORDER_NUM\}~i', '~\{ORDER_SUM\}~i', '~\{ORDER_STATUS\}~i', '~\{FIRST_NAME\}~i', '~\{LAST_NAME\}~i', '~\{CR_PHONE\}~i', '~\{CR_CITY\}~i', '~\{CR_ADDR\}~i', '~\{GOODS_LIST\}~i', '~\{COMMENT\}~i', '~\{STATUS_COMMENT\}~i');

		$value = str_replace('$', '\$', array($order_id, $total, $status->row['name'],
												$order->row['shipping_firstname'] ? $order->row['shipping_firstname'] : $order->row['firstname'],
												$order->row['shipping_lastname'] ? $order->row['shipping_lastname'] : $order->row['lastname'], $order->row['telephone'],
												$order->row['shipping_city'] ? $order->row['shipping_city'] : $order->row['payment_city'],
												$order->row['shipping_address_1'] || $order->row['shipping_address_2'] ? $order->row['shipping_address_1'].' '.$order->row['shipping_address_2'] :
												$order->row['payment_address_1'].' '.$order->row['payment_address_2'], $g_list, $order->row['comment'], $st_com));

		return array('phone' => $order->row['telephone'], 'message' => preg_replace($macros, $value, $textarea), 'order_status_id' => $order->row['order_status_id']);
	}

	public function send($login, $password, $number, $message, $sender, $query = '')
	{
	    if ($this->config->get('oc_smsc_status')) {
			$res = $this->_read_url('https://smsc.ru/sys/send.php?login='.urlencode($login).'&psw='.urlencode(html_entity_decode($password)).
								'&phones='.urlencode($number).'&mes='.urlencode(html_entity_decode(str_replace('\n', "\n", $message), ENT_QUOTES, 'UTF-8')).
								($sender ? '&sender='.urlencode($sender) : '').'&maxsms='.$this->config->get('oc_smsc_maxsms').
								'&cost=3&fmt=3&all=0&charset=utf-8&userip='.$_SERVER['REMOTE_ADDR'].($query ? '&'.$query : ''));

			if ($this->config->get('oc_smsc_debug')) {
				$log = fopen(DIR_LOGS . 'smsc.log', 'w');
				fwrite($log, ($res ? $res : 0)."\nlogin=$login\npassword=$password\nphone=$number\nsender=$sender\nmessage=$message");
				fclose($log);
			}

			return $res;
		}
	}

	// Функция чтения URL. Для работы должно быть доступно:
	// curl или fsockopen (только http) или включена опция allow_url_fopen для file_get_contents

	private function _read_url($url)
	{
		$ret = "";

		if (function_exists("curl_init"))
		{
			static $c = 0; // keepalive

			if (!$c) {
				$c = curl_init();
				curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($c, CURLOPT_TIMEOUT, 10);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
			}

			curl_setopt($c, CURLOPT_URL, $url);

			$ret = curl_exec($c);
		}
		elseif (function_exists("fsockopen") && strncmp($url, 'http:', 5) == 0) // not https
		{
			$m = parse_url($url);

			$fp = fsockopen($m["host"], 80, $errno, $errstr, 10);

			if ($fp) {
				fwrite($fp, "GET $m[path]?$m[query] HTTP/1.1\r\nHost: smsc.ru\r\nUser-Agent: PHP\r\nConnection: Close\r\n\r\n");

				while (!feof($fp))
					$ret = fgets($fp, 1024);

				fclose($fp);
			}
		}
		else
			$ret = file_get_contents($url);

		return $ret;
	}
}
