<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['scripts'] = $this->document->getScripts('footer');

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

        $this->load->model('catalog/category');
        $categories = $this->model_catalog_category->getCategories(0);
        foreach ($categories as $category) {
            $children_data = array();
            $children = $this->model_catalog_category->getCategories($category['category_id']);
            foreach($children as $child) {
                $children_data[] = array(
                    'category_id' => $child['category_id'],
                    'name' => $child['name'],
                    'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                );
            }
            $data['categories'][] = array(
                'category_id' => $category['category_id'],
                'name'        => $category['name'],
                'children'    => $children_data,
                'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
            );
        }
        $data['catalog'] = $this->url->link('product/catalog', '', true);
        $data['footer_cat'] = $this->load->controller('extension/module/category');
        $data['back'] = $this->url->link('common/home');




        $data['search'] = $this->load->controller('common/search');

        if($this->config->get('config_secure') == 1){
            $server = HTTPS_SERVER;
        }else{
            $server = HTTP_SERVER;
        }

        $data['vk'] = $server . 'vk-login';
        $data['fb'] = $server . 'fb-login';
        $data['tw'] = $server . 'tw-login';
        $data['action'] = $this->url->link('account/register', '', true);




		$this->load->model('catalog/information');

        $data['blog'] = array(
            'name' => $this->config->get('easy_blog_home_page_name'),
            'href'  => $this->url->link('blog/blog')
        );


		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}



		$data['about_us'] = $this->url->link('information/information', 'information_id=4', true);
		$data['delivery'] = $this->url->link('information/information', 'information_id=6', true);
        $data['home'] = $this->url->link('common/home','', true );
        $data['manufacturer'] = $this->url->link('product/manufacturer', '',true);
        $data['catalog'] = $this->url->link('product/catalog', '', true);

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/account', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['news'] = $this->url->link('news/news', '', true);
		$data['blog'] = $this->url->link('blog/blog', '', true);
		$data['jobs'] = $this->url->link('jobs/jobs', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		return $this->load->view('common/footer', $data);
	}
    public function recursive($cats, $parent_id, $url, $path = '', $path_id){

        /* if($path == ''){
              if($parent_id == 0){
                  $path = $this->url->link('product/category', 'path=' . $url);
              }else{
                  $path = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url);
              }
          }*/
        if(is_array($cats) and isset($cats[$parent_id])){
            $tree = [];
            foreach($cats[$parent_id] as $key => $cat){

                //  echo "<pre>";
                //   print_r($path);
                //   echo "</pre>";
                $tree[$key]['category_id'] = $cat['category_id'];
                $tree[$key]['name'] = $cat['name'];

                if($parent_id == 0){
                    //  $tree[$key]['href'] = $path . $cat['category_id'];
                    $tree[$key]['href'] =  $this->url->link('product/category', 'path=' . $cat['category_id'] . $url);
                    $tree[$key]['path_id'] = $cat['category_id'];
                }else{
                    // $tree[$key]['href'] = $path . '_' . $cat['category_id'];
                    $tree[$key]['href'] = $this->url->link('product/category', 'path=' . $path_id . '_' . $cat['category_id'] . $url);
                    $tree[$key]['path_id'] = $path_id . '_'. $cat['category_id'];

                }
                if($this->recursive($cats, $cat['category_id'], $url, $tree[$key]['href'], $tree[$key]['path_id'] )){
                    $tree[$key]['sub_categories'] = $this->recursive($cats, $cat['category_id'], $url, $tree[$key]['href'], $tree[$key]['path_id']);
                }
            }
        }
        else return null;
        return $tree;
    }
}
