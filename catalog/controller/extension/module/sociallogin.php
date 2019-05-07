<?php
class ControllerModuleSociallogin extends Controller {
	private $error = array();

	public function vk() {
		$this->language->load('extension/module/sociallogin');
		// Check if module is on
		if(!$this->config->get('sociallogin_vkontakte_status') ){
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$IS_DEBUG = 0;
		$REDIRECT_URI = $this->url->link('extension/module/sociallogin/vk');


		if(!isset($this->request->get['code']) || empty($this->request->get['code'])){
			// If this is first request
			if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
				setcookie("soclogin_ref", $_SERVER['HTTP_REFERER']);
			}else{
				setcookie("soclogin_ref", $this->url->link('account/account', '', 'SSL'));
			}

			$APP_ID = $this->config->get('socnetauth_vkontakte_appid');

			$url = 'https://oauth.vk.com/authorize?client_id='.$APP_ID.
				'&scope=SETTINGS,email'.
				'&redirect_uri='.$REDIRECT_URI.
				'&display=page'.
				'&response_type=code';
			header("Location: ".$url);

		}else{
			// if it is request from vk server already

			$CODE = $this->request->get['code'];

			$CURRENT_URI = $_COOKIE['soclogin_ref'];

			$CLIENT_ID = $this->config->get('socnetauth_vkontakte_appid');
			$CLIENT_SECRET = $this->config->get('socnetauth_vkontakte_appsecret');

			$url = "https://oauth.vk.com/access_token?client_id=".$CLIENT_ID.
				   "&client_secret=".$CLIENT_SECRET.
				   "&code=".$CODE.'&redirect_uri='.$REDIRECT_URI;

			if( $IS_DEBUG ) echo $url."<hr>";


			if( extension_loaded('curl') ){
				$c = curl_init($url);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($c);
				curl_close($c);
			}else{
				$response = file_get_contents($url);
			}


			if( $IS_DEBUG ) echo $response."<hr>";

			$data = json_decode($response, true);

			if( !empty($data['access_token']) ){
				$graph_url = "https://api.vk.com/method/users.get?uids=".$data['user_id'].
				"&fields=uid,first_name,last_name&access_token=".$data['access_token'];

				if( $IS_DEBUG ) echo $graph_url."<hr>";

				if( extension_loaded('curl') ){
					$c = curl_init($graph_url);
					curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
					$json = curl_exec($c);
					curl_close($c);
				}else{
					$json = file_get_contents($graph_url);
				}

				if( $IS_DEBUG ) echo $json;
				$json_data = json_decode($json, TRUE);
				$json_data['response'][0]['email'] = $data['email'];

				$userdata = array();
				foreach($json_data['response'][0] as $key => $usrdata){
					switch($key){
						case "first_name":
							$userdata["firstname"] = $usrdata;
						case "last_name":
							$userdata['lastname'] = $usrdata;
						default:
							$userdata[$key] = $usrdata;
					}
				}

				$this->load->model('account/customer');
				if($this->model_account_customer->getTotalCustomersByEmail($userdata['email'])){
					// login without password
					$this->customer->login($userdata['email'], "", true);
					$this->redirect($CURRENT_URI);
				}else{
					// generate array to create new customer
					$userdata['newsletter'] = 1;
					$userdata['telephone'] = $userdata['fax'] = $userdata['company_id'] = $userdata['address_1'] = $userdata['city'] = $userdata['country_id'] = '';
					$userdata['company'] = $userdata['tax_id'] = $userdata['address_2'] = $userdata['postcode'] = $userdata['zone_id'] = $userdata['country_id'] = '';
					$userdata['password'] = $this->generatePassword();
					$this->model_account_customer->addCustomer($userdata);
					$this->customer->login($userdata['email'], $userdata['password']);
					$this->mailPassword($userdata['email'], $userdata['password']);
					$this->redirect($CURRENT_URI);
				}
			}
		}

	}


	public function fb() {
		$this->language->load('module/sociallogin');
		// Check if module is on
		if(!$this->config->get('sociallogin_facebook_status') ){
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$IS_DEBUG = 1;
		$REDIRECT_URI = $this->url->link('extension/module/sociallogin/fb');

		if(empty($_GET['code']) ){
			if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
				setcookie("soclogin_ref", $_SERVER['HTTP_REFERER']);
			}else{
				setcookie("soclogin_ref", $this->url->link('account/account', '', 'SSL'));
			}

			$CLIENT_ID = $this->config->get('socnetauth_facebook_appid');

			$url = 'https://www.facebook.com/dialog/oauth?'.
				'client_id='.$CLIENT_ID.
				'&redirect_uri='.$REDIRECT_URI.
				'&scope=email';
			header("Location: ".$url);

		}else{
			$CODE = $this->request->get['code'];
			$CURRENT_URI = $_COOKIE['soclogin_ref'];

			$CLIENT_ID = $this->config->get('socnetauth_facebook_appid');
			$CLIENT_SECRET = $this->config->get('socnetauth_facebook_appsecret');

			$url = "https://graph.facebook.com/oauth/access_token?".
						   "client_id=".$CLIENT_ID.
						   "&redirect_uri=".$REDIRECT_URI.
						   "&client_secret=".$CLIENT_SECRET.
						   "&code=".$CODE;

		   if( $IS_DEBUG ) echo $url."<hr>";


			if( extension_loaded('curl') ){
				$c = curl_init($url);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($c);
				curl_close($c);
			}else{
				$response = file_get_contents($url);
			}


			if( $IS_DEBUG ) echo $response."<hr>";
			$data = null;
			parse_str($response, $data);

			if( !empty($data['access_token']) ){
				$graph_url = "https://graph.facebook.com/me?access_token=".$data['access_token'];
				if( $IS_DEBUG ) echo $graph_url."<hr>";

				if( extension_loaded('curl') ){
					$c = curl_init($graph_url);
					curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
					$json = curl_exec($c);
					curl_close($c);
				}else{
					$json = file_get_contents($graph_url);
				}

				if( $IS_DEBUG ) echo $json;
				$json_data = json_decode($json, TRUE);

				$userdata = array();
				foreach($json_data as $key => $usrdata){
					switch($key){
						case "first_name":
							$userdata["firstname"] = $usrdata;
						case "last_name":
							$userdata['lastname'] = $usrdata;
						default:
							$userdata[$key] = $usrdata;
					}
				}

				$this->load->model('account/customer');
				if($this->model_account_customer->getTotalCustomersByEmail($userdata['email'])){
					// login without password
					$this->customer->login($userdata['email'], "", true);
					$this->redirect($CURRENT_URI);
				}else{
					// generate array to create new customer
					$userdata['newsletter'] = 1;
					$userdata['telephone'] = $userdata['fax'] = $userdata['company_id'] = $userdata['address_1'] = $userdata['city'] = $userdata['country_id'] = '';
					$userdata['company'] = $userdata['tax_id'] = $userdata['address_2'] = $userdata['postcode'] = $userdata['zone_id'] = $userdata['country_id'] = '';
					$userdata['password'] = $this->generatePassword();
					$this->model_account_customer->addCustomer($userdata);
					$this->customer->login($userdata['email'], $userdata['password']);
					$this->mailPassword($userdata);
					$this->redirect($CURRENT_URI);
				}
			}
		}

	}

	private function generatePassword($length = 8) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);
			for ($i = 0, $result = ''; $i < $length; $i++) {
				$index = rand(0, $count - 1);
				$result .= mb_substr($chars, $index, 1);
			}
		return $result;
	}

	private function mailPassword($userdata){
		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
		$message = sprintf($this->language->get('text_help'), $userdata['lastname'], $userdata['firstname'], $this->config->get('config_name'), $this->config->get('config_url'), $this->config->get('config_name'), $userdata['password']);

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($userdata['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();


//        $mail = new Mail();
//        $mail->protocol = $this->config->get('config_mail_protocol');
//        $mail->parameter = $this->config->get('config_mail_parameter');
//        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
//        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
//        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
//        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
//        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
//
//        $mail->setTo($set_to);
//        $mail->setFrom($set_from);
//        $mail->setSender(html_entity_decode($set_sender_name, ENT_QUOTES, 'UTF-8'));
//        $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $set_sender_name), ENT_QUOTES, 'UTF-8'));
//        $mail->setText($set_text);
//        $mail->send();

	}

}
?>