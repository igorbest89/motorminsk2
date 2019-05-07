<?php
class ControllerModuleSociallogin extends Controller {
	private $error = array();

	public function vk() {
		$this->language->load('module/sociallogin');
		// Check if module is on
		if(!$this->config->get('sociallogin_vkontakte_status') ){
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$IS_DEBUG = 1;

        $REDIRECT_URI = 'http://bereg.intent-solutions.com/vk-login';

		if(!isset($this->request->get['code']) || empty($this->request->get['code'])){

			// If this is first request
			if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
				setcookie("soclogin_ref", $_SERVER['HTTP_REFERER']);
			}else{
				setcookie("soclogin_ref", $this->url->link('common/home', '', 'SSL'));
			}

			$APP_ID = $this->config->get('sociallogin_vkontakte_appid');

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

			$CLIENT_ID = $this->config->get('sociallogin_vkontakte_appid');
			$CLIENT_SECRET = $this->config->get('sociallogin_vkontakte_appsecret');

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
				"&fields=uid,first_name,last_name&v=1.0&access_token=".$data['access_token'];

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
                    $this->response->redirect($this->url->link('common/home', '', true));
				}else{
					// generate array to create new customer
					$userdata['newsletter'] = 1;
					$userdata['telephone'] = $userdata['fax'] = $userdata['company_id'] = $userdata['address_1'] = $userdata['city'] = $userdata['country_id'] = '';
					$userdata['company'] = $userdata['tax_id'] = $userdata['address_2'] = $userdata['postcode'] = $userdata['zone_id'] = $userdata['country_id'] = '';
					$userdata['password'] = $this->generatePassword();
					$this->model_account_customer->addCustomer($userdata);
					$this->customer->login($userdata['email'], $userdata['password']);
					$this->mailPassword($userdata['email'], $userdata['password']);
                    $this->response->redirect($this->url->link('common/home', '', true));
				}
			}
		}

	}

    public function tw() {
        $this->language->load('module/sociallogin');
        // Check if module is on
        if(!$this->config->get('sociallogin_twitter_status') ){
            $this->redirect($this->url->link('account/login', '', 'SSL'));
        }

        $IS_DEBUG = 1;

// определяем изначальные конфигурационные данные

        $CONSUMER_KEY = $this->config->get('sociallogin_twitter_appid');
        $CONSUMER_SECRET = $this->config->get('sociallogin_twitter_appsecret');

        define('CONSUMER_KEY', $CONSUMER_KEY);
        define('CONSUMER_SECRET', $CONSUMER_SECRET);

        define('REQUEST_TOKEN_URL', 'https://api.twitter.com/oauth/request_token');
        define('AUTHORIZE_URL', 'https://api.twitter.com/oauth/authorize');
        define('ACCESS_TOKEN_URL', 'https://api.twitter.com/oauth/access_token');
        define('ACCOUNT_DATA_URL', 'https://api.twitter.com/1.1/users/show.json');

        define('CALLBACK_URL', 'http://bereg.intent-solutions.com/tw-login');


// формируем подпись для получения токена доступа
        define('URL_SEPARATOR', '&');

        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();

        $params = array(
            'oauth_callback=' . urlencode(CALLBACK_URL) . URL_SEPARATOR,
            'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
            'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
            'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
            'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
            'oauth_version=1.0'
        );

        $oauth_base_text = implode('', array_map('urlencode', $params));
        $key = CONSUMER_SECRET . URL_SEPARATOR;
        $oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(REQUEST_TOKEN_URL) . URL_SEPARATOR . $oauth_base_text;
        $oauth_signature = base64_encode(hash_hmac('sha1', $oauth_base_text, $key, true));


// получаем токен запроса
        $params = array(
            URL_SEPARATOR . 'oauth_consumer_key=' . CONSUMER_KEY,
            'oauth_nonce=' . $oauth_nonce,
            'oauth_signature=' . urlencode($oauth_signature),
            'oauth_signature_method=HMAC-SHA1',
            'oauth_timestamp=' . $oauth_timestamp,
            'oauth_version=1.0'
        );
        $url = REQUEST_TOKEN_URL . '?oauth_callback=' . urlencode(CALLBACK_URL) . implode('&', $params);

        $response = file_get_contents($url);

      //  echo "<pre>";
     //   print_r($response);
      //  echo "</pre>";
      //  die;
        parse_str($response, $response);

        $oauth_token = $response['oauth_token'];
        $oauth_token_secret = $response['oauth_token_secret'];


// генерируем ссылку аутентификации

        $link = AUTHORIZE_URL . '?oauth_token=' . $oauth_token;


        if(!isset($_REQUEST['oauth_token'])){
            echo '<a href="' . $link . '">Аутентификация через Twitter</a>';
        }


        if (!empty($_GET['oauth_token']) && !empty($_GET['oauth_verifier'])) {
            // готовим подпись для получения токена доступа

            $oauth_nonce = md5(uniqid(rand(), true));
            $oauth_timestamp = time();
            $oauth_token = $_GET['oauth_token'];
            $oauth_verifier = $_GET['oauth_verifier'];


            $oauth_base_text = "GET&";
            $oauth_base_text .= urlencode(ACCESS_TOKEN_URL)."&";

            $params = array(
                'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
                'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
                'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
                'oauth_token=' . $oauth_token . URL_SEPARATOR,
                'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
                'oauth_verifier=' . $oauth_verifier . URL_SEPARATOR,
                'oauth_version=1.0'
            );

            $key = CONSUMER_SECRET . URL_SEPARATOR . $oauth_token_secret;
            $oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(ACCESS_TOKEN_URL) . URL_SEPARATOR . implode('', array_map('urlencode', $params));
            $oauth_signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

            // получаем токен доступа
            $params = array(
                'oauth_nonce=' . $oauth_nonce,
                'oauth_signature_method=HMAC-SHA1',
                'oauth_timestamp=' . $oauth_timestamp,
                'oauth_consumer_key=' . CONSUMER_KEY,
                'oauth_token=' . urlencode($oauth_token),
                'oauth_verifier=' . urlencode($oauth_verifier),
                'oauth_signature=' . urlencode($oauth_signature),
                'oauth_version=1.0'
            );
            $url = ACCESS_TOKEN_URL . '?' . implode('&', $params);

            $response = file_get_contents($url);
            parse_str($response, $response);


            // формируем подпись для следующего запроса
            $oauth_nonce = md5(uniqid(rand(), true));
            $oauth_timestamp = time();

            $oauth_token = $response['oauth_token'];
            $oauth_token_secret = $response['oauth_token_secret'];
            $screen_name = $response['screen_name'];

            $params = array(
                'oauth_consumer_key=' . CONSUMER_KEY . URL_SEPARATOR,
                'oauth_nonce=' . $oauth_nonce . URL_SEPARATOR,
                'oauth_signature_method=HMAC-SHA1' . URL_SEPARATOR,
                'oauth_timestamp=' . $oauth_timestamp . URL_SEPARATOR,
                'oauth_token=' . $oauth_token . URL_SEPARATOR,
                'oauth_version=1.0' . URL_SEPARATOR,
                'screen_name=' . $screen_name
            );
            $oauth_base_text = 'GET' . URL_SEPARATOR . urlencode(ACCOUNT_DATA_URL) . URL_SEPARATOR . implode('', array_map('urlencode', $params));

            $key = CONSUMER_SECRET . '&' . $oauth_token_secret;
            $signature = base64_encode(hash_hmac("sha1", $oauth_base_text, $key, true));

            // получаем данные о пользователе
            $params = array(
                'oauth_consumer_key=' . CONSUMER_KEY,
                'oauth_nonce=' . $oauth_nonce,
                'oauth_signature=' . urlencode($signature),
                'oauth_signature_method=HMAC-SHA1',
                'oauth_timestamp=' . $oauth_timestamp,
                'oauth_token=' . urlencode($oauth_token),
                'oauth_version=1.0',
                'screen_name=' . $screen_name
            );

            $url = ACCOUNT_DATA_URL . '?' . implode(URL_SEPARATOR, $params);

            $response = file_get_contents($url);
            $user_data = json_decode($response, true);


            $userdata = array();
            foreach($user_data as $key => $usrdata){
                switch($key){
                    case "name":
                        $userdata["firstname"] = $usrdata;
                    case "screen_name":
                        $userdata['lastname'] = $usrdata;
                }
            }

            $userdata['email'] = 'twitter.' . $user_data['id'] . '@fake.com';
            $this->load->model('account/customer');
            if($this->model_account_customer->getTotalCustomersByEmail($userdata['email'])){
                // login without password
                $this->customer->login($userdata['email'], "", true);
                $this->response->redirect($this->url->link('common/home', '', true));
            }else {
                $userdata['newsletter'] = 1;
                $userdata['telephone'] = $userdata['fax'] = $userdata['company_id'] = $userdata['address_1'] = $userdata['city'] = $userdata['country_id'] = '';
                $userdata['company'] = $userdata['tax_id'] = $userdata['address_2'] = $userdata['postcode'] = $userdata['zone_id'] = $userdata['country_id'] = '';
                $userdata['password'] = $this->generatePassword();
                $this->model_account_customer->addCustomer($userdata);
                $this->customer->login($userdata['email'], $userdata['password']);
                $this->mailPassword($userdata);
                $this->response->redirect($this->url->link('common/home', '', true));
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
		$REDIRECT_URI = $this->url->link('module/sociallogin/fb');

		if(empty($_GET['code']) ){
			if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
				setcookie("soclogin_ref", $_SERVER['HTTP_REFERER']);
			}else{
				setcookie("soclogin_ref", $this->url->link('common/home', '', 'SSL'));
			}

			$CLIENT_ID = $this->config->get('sociallogin_facebook_appid');

			$url = 'https://www.facebook.com/dialog/oauth?'.
				'client_id='.$CLIENT_ID.
				'&redirect_uri='.$REDIRECT_URI.
				'&scope=email';
			header("Location: ".$url);

		}else{
			$CODE = $this->request->get['code'];
			$CURRENT_URI = $_COOKIE['soclogin_ref'];

			$CLIENT_ID = $this->config->get('sociallogin_facebook_appid');
			$CLIENT_SECRET = $this->config->get('sociallogin_facebook_appsecret');

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
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($userdata['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}

}
?>