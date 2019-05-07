<?php

class ControllerProductCategory extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {


        $this->load->language('product/category');
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.sort_order';
        }

        if (isset($this->request->get['order_years'])) {
            $order_years = $this->request->get['order_years'];
        } else {
            $order_years = '';
        }

        if (isset($this->request->get['years'])) {
            $years = $this->request->get['years'];
        } else {
            $years = '';
        }

        if (isset($this->request->get['special'])) {
            $special = $this->request->get['special'];
        } else {
            $special = '';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => 'Главная',
            'href' => $this->url->link('common/home')
        );


        if (isset($this->request->get['path'])) {

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $path = '';

            $parts = explode('_', (string)$this->request->get['path']);

//            $category_id = (int)array_pop($parts);
            $category_id = (int)end($parts);

            $breadcrumbElement = 'catalog';

            foreach ($parts as $path_id) {
                if (!$path) {
                    $path = (int)$path_id;
                } else {
                    $path .= '_' . (int)$path_id;
                }

                $category_info = $this->model_catalog_category->getCategory($path_id);
                $data['url_alias'] = '';
                foreach ($parts as $cat_id){
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$cat_id . "'");
                    if ($query->row['keyword']) {
                        $data['url_alias'] .= '/' . $query->row['keyword'];
                    }else{
                        $data['url_alias'] = '';
                    }
                }




                $category_full_info = $this->model_catalog_category->getCategoryBreadcrumb($path_id);
                $breadcrumbElement .= '/' . $category_full_info['keyword'];

//                $breadcrumbElement = 'catalog';
                if ($category_info) {
                    $data['breadcrumbs'][] = array(
                        'text' => $category_info['name'],
//						'href' => $this->url->link('product/category', 'path=' . $path . $url),
                        'href' => $this->config->get('site_base') . $breadcrumbElement
                    );
                }
            }
        } else {
            $category_id = 0;
        }

        $category_info = $this->model_catalog_category->getCategory($category_id);


        if ($category_info) {


            if ($this->request->get['path'] !== 0) {

//                $this->document->setTitle($category_info['name'] .  ' купить в Москве и Смоленске | Продажа ' . $category_info['name'] . $data['metaFor'] . ' б/у');
//                $this->document->setDescription($category_info['name'] .  ' б/у из Европы на легковых автомобилей и микроавтобусов ➤ Компания Ф-АВТО ☎ ' . $this->config->get('config_telephone') . ' Гарантия, доставка по всей России.');

                if (isset($this->request->get['filter']) && isset($_GET['filter'])) {
                    $requestGet = explode(',', $this->request->get['filter']);
                    $globalGet = explode(',', $_GET['filter']);

                    $deletingEllements = array_uintersect($globalGet, $requestGet, 'strcasecmp');

                    foreach ($deletingEllements as $key => $element) {
                        unset($requestGet[$key]);
                    }

                    $requestGet = $this->model_catalog_category->getMeta(implode(',', $requestGet));

                    $data['metaFor'] = ' на ' . implode(' ', array_column($requestGet, 'name'));
                    $data['meta'] = ' ' . implode(' ', array_column($requestGet, 'name'));

                    $this->document->setTitle($category_info['name'] . $data['metaFor'] . ' купить в Москве и Смоленске | Продажа ' . $category_info['name'] . $data['metaFor'] . ' б/у');
                    $this->document->setDescription($category_info['name'] . $data['metaFor'] . ' б/у из Европы на легковых автомобилей и микроавтобусов ➤ Компания Ф-АВТО ☎ ' . $this->config->get('config_telephone') . ' Гарантия, доставка по всей России.');
                    $this->document->setKeywords($category_info['meta_keyword']);
                } elseif (isset($this->request->get['filter']) && !isset($_GET['filter'])) {

                    $requestGet = $this->model_catalog_category->getMeta($this->request->get['filter']);

                    $data['metaFor'] = ' на ' . implode(' ', array_column($requestGet, 'name'));

                    $this->document->setTitle($category_info['name'] . $data['metaFor'] . ' купить в Москве и Смоленске | Продажа ' . $category_info['name'] . $data['metaFor'] . ' б/у');
                    $this->document->setDescription($category_info['name'] . $data['metaFor'] . ' б/у из Европы на легковых автомобилей и микроавтобусов ➤ Компания Ф-АВТО ☎ ' . $this->config->get('config_telephone') . ' Гарантия, доставка по всей России.');
                    $this->document->setKeywords($category_info['meta_keyword']);
                } elseif (!isset($this->request->get['filter']) && !isset($_GET['filter'])) {
                    $data['metaFor'] = '';
                }
            }

            $data['heading_title'] = $category_info['name'];

            $data['text_refine'] = $this->language->get('text_refine');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['text_quantity'] = $this->language->get('text_quantity');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_price'] = $this->language->get('text_price');
            $data['text_tax'] = $this->language->get('text_tax');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
            $data['text_sort'] = $this->language->get('text_sort');
            $data['text_limit'] = $this->language->get('text_limit');

            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['button_list'] = $this->language->get('button_list');
            $data['button_grid'] = $this->language->get('button_grid');
            $data['siteBase'] = HTTP_SERVER;


            $categorySiteBase = str_replace('_', ', ', $this->request->get['path']);
            $categorySiteBase = $this->model_catalog_category->getCategorySiteBase($categorySiteBase);

            $categoryBaseUrl = '';
            foreach ($categorySiteBase as $item) {
                $categoryBaseUrl .= '/' . $item['url_alias'];
            }
            $data['category_baseUrl'] = 'catalog' . $categoryBaseUrl;

//			// Set the last category breadcrumb
//			$data['breadcrumbs'][] = array(
//				'text' => $category_info['name'],
//				'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
//			);


            if (!isset($_GET['filter']) && isset($this->request->get['filter'])) {
                $breadcrumbs_filters = explode(',', $this->request->get['filter']);

                $filter_info_element = [];
                foreach ($breadcrumbs_filters as $breadcrumbs_filter) {
                    $filter_info = $this->model_catalog_category->getFiltersBreadcrumbs($breadcrumbs_filter);

                    if ($filter_info) {
                        $data['breadcrumbs'][] = array(
                            'text' => $filter_info['name'],
//                            'href' => $this->url->link('product/category',  'path='  . $this->request->get['path'] . implode("&", $filter_info_element) . '&' . $filter_info['keyword']),
                            'href' => $this->config->get('site_base') . $breadcrumbElement . '/' . $filter_info['keyword']
                        );
                    }
                    $filter_info_element[] = 'catalog' . '&' . $filter_info['keyword'];
                }
            }

            if ($category_info['image']) {
                $data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
            } else {
                $data['thumb'] = '';
            }

            $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
            $data['compare'] = $this->url->link('product/compare');

            $url = '';

            if (isset($this->request->get['filter'])) {
                //         $url .= '&filter=' . $this->request->get['filter'];
            }


            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }



            $results_cat = $this->model_catalog_category->getAllCategories();
            foreach ($results_cat as $cat){
                $cats[$cat['parent_id']][$cat['category_id']] =  $cat;
            }
            $data['categories'] = $this->recursive($cats, $category_id, '', '', $this->request->get['path']);
            $data['parent_categories'] = $this->recursive($cats, 0, '', '', '');

            $this->load->language('extension/module/filtersearch');
            $this->load->model('catalog/product');
            $this->load->model('extension/module/filtersearch');
            $data['marks'] = $this->model_extension_module_filtersearch->getAllMarks();
            $data['parentFilters'] = $this->model_extension_module_filtersearch->getAllGroupFilters();
            $data['childFilters'] = $this->model_extension_module_filtersearch->getAllChildFilters();
            $data['siteBase'] = HTTP_SERVER;
            $data['allCategories'] = [];
            $data['allCategories'] = $this->model_extension_module_filtersearch->getAllCategories();





            $data['categories'] = array();

            $results = $this->model_catalog_category->getCategories($category_id);

            foreach ($results as $result) {
                $filter_data = array(
                    'filter_category_id' => $result['category_id'],
                    'filter_sub_category' => true
                );

                $data['categories'][] = array(
                    'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
                );
            }


            $data['products'] = array();




            if (isset($_GET['filter']) && !empty($_GET['filter'])) {
                $newFilters = $_GET['filter'];
            } elseif (!isset($_GET['filter']) && empty($_GET['filter']) && isset($this->request->get['filter'])) {
                $array_of_id_filters = explode(',' , $this->request->get['filter']);
                $newFilters = end($array_of_id_filters);
            }else{
                $newFilters = '';
            }



            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_filter' => $newFilters,
                'sort' => $sort,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );

            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

            if (isset($this->request->post['selectedMinPrice']) && isset($this->request->post['selectedMaxPrice'])) {
                $min_price = $this->request->post['selectedMinPrice'];
                $max_price = $this->request->post['selectedMaxPrice'];
                $this->session->data['selectedMinPrice'] = $this->request->post['selectedMinPrice'];
                $this->session->data['selectedMaxPrice'] = $this->request->post['selectedMaxPrice'];
            } else {
                $price = $this->model_catalog_category->getRangeProductPrice();
                $min_price = $price['min_product_price'];
                $max_price = $price['max_product_price'];
            }

            $results = $this->model_catalog_product->getProducts($filter_data, $min_price, $max_price);

            $data['countProd'] = count($results);

            foreach ($results as $result) {

                $images = [];

                $attribut = $this->model_catalog_product->getProductAttributes($result['product_id']);
                $attrCatalog = [];
                $attrFull = [];

                foreach ($attribut[0]['attribute'] as $attr) {
                    if ($attr['name'] == "Марка" || $attr['name'] == "Модель" || $attr['name'] == "Модификация" || $attr['name'] == "Год") {
                        $attrCatalog[] = $attr;
                    }
                }
                foreach ($attribut[0]['attribute'] as $attr) {
                    if ($attr['name'] == "Тип топлива" || $attr['name'] == "Тип двигателя" || $attr['name'] == "КПП") {
                        $attrFull[] = $attr;
                    }
                }

                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                    $product_images = $this->model_catalog_product->getProductImages($result['product_id']);

                    if ($product_images) {
                        foreach ($product_images as $product_image) {
                            $images[] = $this->model_tool_image->resize($product_image['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                        }
                    }
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }
                $images = [];

                $results_images = $this->model_catalog_product->getProductImages($result['product_id']);
                $attributes = $this->model_catalog_product->getProductAttributes($result['product_id']);



                foreach ($results_images as $results_image) {
                    $images[] = [
                        'thumb' => $this->model_tool_image->resize($results_image['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
                    ];
                }

                if (strlen($result['name']) > 85) {
                    $name = substr($result['name'], 0, 77) . "...";
                } else {
                    $name = $result['name'];
                }


                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'thumbs' => $images,
                    'images' => $images,
                    'name' => $name,
                    'attributes' => (isset($attributes[0]['attribute'])) ? $attributes[0]['attribute'] : '',
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $result['rating'],
                    'href' => str_replace(HTTP_SERVER,'goods/', $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url)),
                    'newHref' => $this->config->get('config_url') . 'goods/' . $result['url_alias'],
                    'attrCatalog' => $attrCatalog,
                    'attrFull' => $attrFull
                );
            }

            $url = '';

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $data['sorts'][] = array(
                'text' => "Цене",
                'value' => 'p.price-ASC',
                'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
            );

            $extendedFilter = '';
            if (isset($_GET['filter']) && !empty($_GET['filter'])) {
                $extendedFilter = '&filter=' . $_GET['filter'];
            }
            $fullHref = $data['siteBase'] . $_GET['_route_'] . $extendedFilter;

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price'),
                'value' => 'p.price-Desc',
                'href' => $fullHref . '&sort=p.price&order=DESC' . $url
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $fullHref . '&sort=p.price&order=ASC' . $url
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $fullHref . '&sort=p.price&order=DESC' . $url
            );

            $data['years'][] = array(
                'text' => $this->language->get('text_sort_year'),
                'value' => '',
                'href' => $fullHref . $url
            );

            $data['years'][] = array(
                'text' => $this->language->get('text_sort_year_asc'),
                'value' => 'p.jan-ASC',
                'href' => $fullHref . '&years=p.jan&order=ASC' . $url
            );

            $data['years'][] = array(
                'text' => $this->language->get('text_sort_year_desc'),
                'value' => 'p.jan-DESC',
                'href' => $fullHref . '&years=p.jan&order=DESC' . $url
            );

            $data['specials'][] = array(
                'text' => $this->language->get('text_sort_special'),
                'value' => '',
                'href' => $fullHref . $url
            );

            $data['specials'][] = array(
                'text' => $this->language->get('text_sort_special_asc'),
                'value' => 'true',
                'href' => $fullHref . '&special=true' . $url
            );

            $data['specials'][] = array(
                'text' => $this->language->get('text_sort_special_desc'),
                'value' => 'false',
                'href' => $fullHref . '&special=false' . $url
            );

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = array(
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
                );
            }

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
//			$pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
            $pagination->url = $data['siteBase'] . $this->request->get['_route_'] . '&page={page}';

            $data['pagination'] = $pagination->render(1);

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
            if ($page == 1) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], true), 'canonical');
            } elseif ($page == 2) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'], true), 'prev');
            } else {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page - 1), true), 'prev');
            }

            if ($limit && ceil($product_total / $limit) > $page) {
                $this->document->addLink($this->url->link('product/category', 'path=' . $category_info['category_id'] . '&page=' . ($page + 1), true), 'next');
            }
            $data['total'] = $product_total;
            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;

            $data['continue'] = $this->url->link('common/home');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $data['search'] = $this->load->controller('common/search');
            if(isset($_SERVER["HTTP_REFERER"])){
                $data['back'] = $_SERVER["HTTP_REFERER"];
            }else{
                $data['back'] = $this->url->link('common/home');
            }

            $url = '';
            if(isset($this->request->get['path'])){
                $categories_custom = explode('_', $this->request->get['path']);
                foreach ($categories_custom as $category) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
                    if ($query->num_rows && $query->row['keyword']) {
                        if($url == ''){
                            $url .= $query->row['keyword'];
                        }else{
                            $url .= '/' . $query->row['keyword'];
                        }
                    } else {
                        $url = '';
                        break;
                    }
                }
            }
            $data['category_custom'] = $url;

            if(isset($this->request->get['filter'])){
                $temp_array = explode(',', $this->request->get['filter']);
                foreach ($temp_array as $key => $item){
                    $sql = "SELECT filter_group_id, filter_id FROM " . DB_PREFIX . "filter WHERE filter_id='" . $item . "'";
                    $temp = $this->db->query($sql)->row;


                    if(isset($temp['filter_group_id']) && $temp['filter_group_id'] == 3){

                        $filters['childFilters'] = $this->model_extension_module_filtersearch->getFilters($item);
                        $filters['countChildFilters'] = $this->model_extension_module_filtersearch->countFilters($filters['childFilters']);
                        $parentFilters = $this->model_extension_module_filtersearch->getAllGroupFilters();

                        $filters['parentFilters'] = [];
                        foreach ($parentFilters as $key => $sideFilter) {
                            if ($sideFilter['name'] == 'Тип двигателя') {
                                unset($parentFilters[$key]);
                                array_unshift($filters['parentFilters'], $sideFilter);
                            } else {
                                $filters['parentFilters'][] = $sideFilter;
                            }
                        }

                        $deleteRestail = array_search('Рестайлинг', array_column($filters['parentFilters'], 'name'));
                        unset($filters['parentFilters'][$deleteRestail]);

                        $temp = [];
                        foreach ($filters['parentFilters'] as $parent_filters) {
                            foreach ($filters['childFilters'] as $childFilter) {
                                if ($childFilter['filter_group_id'] == $parent_filters['filter_group_id']) {
                                    $temp[$parent_filters['name']][] = $childFilter;

                                }
                            }
                        }

                    }

                    $filters_custom[$item]['data'] = $this->model_extension_module_filtersearch->getFiltersData($item)[0];
                    if($filters_custom[$item]['data']['filter_group_id'] != 3){
                        $filters_custom[$item]['list'] = $this->model_extension_module_filtersearch->getFilters($item);
                    }
                }


                foreach ($filters_custom as &$filter){
                    foreach($temp as $key => $inner){
                        if($key == $filter['data']['group_name']){
                            $filter['list'] = $inner;
                        }
                    }
                }
                $filters_custom = array_values($filters_custom);

                foreach ($filters_custom as $key => $item){

                    if($item['data']['group_name'] == 'Марка'){
                        $data['filter_marks']['data']  = $item['data'];
                        $data['filter_marks']['list'] = $this->model_extension_module_filtersearch->getAllMarks();
                    }elseif ($item['data']['group_name'] == 'Модель'){
                        $data['filter_models']['data'] = $item['data'];
                        $data['filter_models']['list'] = $filters_custom[$key -1]['list'];
                    }elseif ($item['data']['group_name'] == 'Модификация'){
                        $data['filter_modification']['data'] = $item['data'];
                        $data['filter_modification']['list'] = $filters_custom[$key -1]['list'];
                    }elseif ($item['data']['group_name'] == 'Тип топлива'){
                        $data['filter_fuel']['data'] = $item['data'];
                        $data['filter_fuel']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'Тип двигателя'){
                        $data['filter_motor']['data'] = $item['data'];
                        $data['filter_motor']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'Объем'){
                        $data['filter_engine_capacity']['data'] = $item['data'];
                        $data['filter_engine_capacity']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'КПП'){
                        $data['filter_transmission']['data'] = $item['data'];
                        $data['filter_transmission']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'Тип кузова'){
                        $data['filter_body_type']['data'] = $item['data'];
                        $data['filter_body_type']['list'] = $item['list'];
                    }
                }
            }


            if(isset($data['filter_marks'])){
                $data['model_if_mark']['list'] = $this->model_extension_module_filtersearch->getFilters($data['filter_marks']['data']['filter_id']);
            }
            if(isset($data['filter_models'])){
                $data['modification_if_models']['list'] = $this->model_extension_module_filtersearch->getFilters($data['filter_models']['data']['filter_id']);
            }
            if(isset($data['filter_modification'])){
                $modification_filter_id = $data['filter_modification']['data']['filter_id'];
                $filters_temp['childFilters'] = $this->model_extension_module_filtersearch->getFilters($modification_filter_id);
                if(isset($filters_temp['childFilters'])) {
                    foreach ($filters_temp['childFilters'] as $filter) {
                        if ($filter['filter_group_id'] == 7) {
                            $data['fuel_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 8) {
                            $data['motor_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 11) {
                            $data['engine_capacity_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 9) {
                            $data['transmission_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 4) {
                            $data['body_type_if_models']['list'][] = $filter;
                        }
                    }
                }
            }
                $this->response->setOutput($this->load->view('product/category', $data));
        }



        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///
        // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti /// // zapchasti ///




        elseif (empty($category_info) && isset($this->request->get['filter'])) {

            $this->load->model('extension/module/filtersearch');

            $data['category_custom'] = '';


            if(isset($this->request->get['filter'])){
                $temp_array = explode(',', $this->request->get['filter']);
                foreach ($temp_array as $key => $item){
                    $sql = "SELECT filter_group_id, filter_id FROM " . DB_PREFIX . "filter WHERE filter_id='" . $item . "'";
                    $temp = $this->db->query($sql)->row;


                    if(isset($temp['filter_group_id']) && $temp['filter_group_id'] == 3){

                        $filters['childFilters'] = $this->model_extension_module_filtersearch->getFilters($item);
//                        $filters['countChildFilters'] = $this->model_extension_module_filtersearch->countFilters($filters['childFilters']);
                        $parentFilters = $this->model_extension_module_filtersearch->getAllGroupFilters();

                        $filters['parentFilters'] = [];
                        foreach ($parentFilters as $key => $sideFilter) {
                            if ($sideFilter['name'] == 'Тип двигателя') {
                                unset($parentFilters[$key]);
                                array_unshift($filters['parentFilters'], $sideFilter);
                            } else {
                                $filters['parentFilters'][] = $sideFilter;
                            }
                        }

                        $deleteRestail = array_search('Рестайлинг', array_column($filters['parentFilters'], 'name'));
                        unset($filters['parentFilters'][$deleteRestail]);

                        $temp = [];
                        foreach ($filters['parentFilters'] as $parent_filters) {
                            foreach ($filters['childFilters'] as $childFilter) {
                                if ($childFilter['filter_group_id'] == $parent_filters['filter_group_id']) {
                                    $temp[$parent_filters['name']][] = $childFilter;

                                }
                            }
                        }

                    }

                    $filters_custom[$item]['data'] = $this->model_extension_module_filtersearch->getFiltersData($item)[0];
                    if($filters_custom[$item]['data']['filter_group_id'] != 3){
                        $filters_custom[$item]['list'] = $this->model_extension_module_filtersearch->getFilters($item);
                    }
                }


                foreach ($filters_custom as &$filter){
                    foreach($temp as $key => $inner){
                        if($key == $filter['data']['group_name']){
                            $filter['list'] = $inner;
                        }
                    }
                }
                $filters_custom = array_values($filters_custom);

                foreach ($filters_custom as $key => $item){
                    if($item['data']['group_name'] == 'Марка'){
                        $data['filter_marks']['data']  = $item['data'];
                        $data['filter_marks']['list'] = $this->model_extension_module_filtersearch->getAllMarks();
                    }elseif ($item['data']['group_name'] == 'Модель'){
                        $data['filter_models']['data'] = $item['data'];
                        $data['filter_models']['list'] = $filters_custom[$key -1]['list'];
                    }elseif ($item['data']['group_name'] == 'Модификация'){
                        $data['filter_modification']['data'] = $item['data'];
                        $data['filter_modification']['list'] = $filters_custom[$key -1]['list'];
                    }elseif ($item['data']['group_name'] == 'Тип топлива'){
                        $data['filter_fuel']['data'] = $item['data'];
                        $data['filter_fuel']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'Тип двигателя'){
                        $data['filter_motor']['data'] = $item['data'];
                        $data['filter_motor']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'Объем'){
                        $data['filter_engine_capacity']['data'] = $item['data'];
                        $data['filter_engine_capacity']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'КПП'){
                        $data['filter_transmission']['data'] = $item['data'];
                        $data['filter_transmission']['list'] = $item['list'];
                    }elseif ($item['data']['group_name'] == 'Тип кузова'){
                        $data['filter_body_type']['data'] = $item['data'];
                        $data['filter_body_type']['list'] = $item['list'];
                    }
                }
            }

            if(isset($data['filter_marks'])){
                $data['model_if_mark']['list'] = $this->model_extension_module_filtersearch->getFilters($data['filter_marks']['data']['filter_id']);
            }
            if(isset($data['filter_models'])){
                $data['modification_if_models']['list'] = $this->model_extension_module_filtersearch->getFilters($data['filter_models']['data']['filter_id']);
            }

            if(isset($data['filter_modification'])){
                $modification_filter_id = $data['filter_modification']['data']['filter_id'];
                $filters_temp['childFilters'] = $this->model_extension_module_filtersearch->getFilters($modification_filter_id);
                if(isset($filters_temp['childFilters'])) {
                    foreach ($filters_temp['childFilters'] as $filter) {
                        if ($filter['filter_group_id'] == 7) {
                            $data['fuel_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 8) {
                            $data['motor_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 11) {
                            $data['engine_capacity_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 9) {
                            $data['transmission_if_models']['list'][] = $filter;
                        } elseif ($filter['filter_group_id'] == 4) {
                            $data['body_type_if_models']['list'][] = $filter;
                        }
                    }
                }
            }


            if (!isset($this->request->get['path'])) {
                if (isset($this->request->get['filter']) && isset($_GET['filter'])) {
                    $requestGet = explode(',', $this->request->get['filter']);
                    $globalGet = explode(',', $_GET['filter']);

                    $deletingEllements = array_uintersect($globalGet, $requestGet, 'strcasecmp');

                    foreach ($deletingEllements as $key => $element) {
                        unset($requestGet[$key]);
                    }

                    $requestGet = $this->model_catalog_category->getMeta(implode(',', $requestGet));

                    $data['metaFor'] = 'Запчасти на ' . implode(' ', array_column($requestGet, 'name'));
                    $data['meta'] = ' ' . implode(' ', array_column($requestGet, 'name'));

//                    $this->document->setTitle($data['metaFor'] . ' купить в Москве и Смоленске | Продажа ' . $data['metaFor'] . ' б/у');
//                    $this->document->setDescription($data['metaFor'] . ' б/у из Европы на легковых автомобилей и микроавтобусов ➤ Компания Ф-АВТО ☎ ' . $this->config->get('config_telephone') . ' Гарантия, доставка по всей России.');
//                    $this->document->setKeywords($category_info['meta_keyword']);
                } elseif (isset($this->request->get['filter']) && !isset($_GET['filter'])) {

                    $requestGet = $this->model_catalog_category->getMeta($this->request->get['filter']);

                    $data['metaFor'] = 'Запчасти на ' . implode(' ', array_column($requestGet, 'name'));

//                    $this->document->setTitle($category_info['name'] . $data['metaFor'] . ' купить в Москве и Смоленске | Продажа ' . $data['metaFor'] . ' б/у');
//                    $this->document->setDescription($data['metaFor'] . ' б/у из Европы на легковых автомобилей и микроавтобусов ➤ Компания Ф-АВТО ☎ ' . $this->config->get('config_telephone') . ' Гарантия, доставка по всей России.');
//                    $this->document->setKeywords($category_info['meta_keyword']);
                } elseif (!isset($this->request->get['filter']) && !isset($_GET['filter'])) {
                    $data['metaFor'] = '';
                }
            }

            $data['catalog'] = $this->url->link('product/catalog');;
            $data['heading_title'] = '';
            $data['text_refine'] = $this->language->get('text_refine');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['text_quantity'] = $this->language->get('text_quantity');
            $data['text_manufacturer'] = $this->language->get('text_manufacturer');
            $data['text_model'] = $this->language->get('text_model');
            $data['text_price'] = $this->language->get('text_price');
            $data['text_tax'] = $this->language->get('text_tax');
            $data['text_points'] = $this->language->get('text_points');
            $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
            $data['text_sort'] = $this->language->get('text_sort');
            $data['text_limit'] = $this->language->get('text_limit');
            $data['siteBase'] = HTTP_SERVER;
            $data['button_cart'] = $this->language->get('button_cart');
            $data['button_wishlist'] = $this->language->get('button_wishlist');
            $data['button_compare'] = $this->language->get('button_compare');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['button_list'] = $this->language->get('button_list');
            $data['button_grid'] = $this->language->get('button_grid');
            $data['url'] = explode('/', $this->request->get['_route_']);


            if (isset($_GET['_route_'])) {
                $breadcrumbs_filters = explode('/', $this->request->get['_route_']);

                if ($breadcrumbs_filters[0] == 'zapchasti') {
                    unset($breadcrumbs_filters['0']);
                }

                $filter_info_element = 'zapchasti';
                foreach ($breadcrumbs_filters as $breadcrumbs_filter) {
                    if (!empty($breadcrumbs_filter)) {
                        $filter_info = $this->model_catalog_category->getMainFiltersBreadcrumbs($breadcrumbs_filter);
                        $filter_info_element .= '/' . $filter_info['keyword'];

                        $data['breadcrumbs'][] = [
                            'text' => $filter_info['name'],
                            'href' => $data['siteBase'] . ltrim($filter_info_element, '/')
                        ];
                    }
                }
            }

            $data['description'] = html_entity_decode('Category description', ENT_QUOTES, 'UTF-8');
            $data['compare'] = $this->url->link('product/compare');

            $url = '';

            if (isset($this->request->get['years'])) {
                $url .= '&years=' . $this->request->get['years'];
            }

            if (isset($this->request->get['order_years'])) {
                $url .= '&order_years=' . $this->request->get['order_years'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
                $sort = $this->request->get['sort'];
            }else {
                $sort = 'p.sort_order';
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
                $order = $this->request->get['order'];
            }else{
                $order = 'ASC';
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }






            $data['categories'] = array();

            $results = $this->model_catalog_category->getCategories($category_id);

            foreach ($results as $result) {
                $filter_data = array(
                    'filter_category_id' => $result['category_id'],
                    'filter_sub_category' => true
                );

                $data['categories'][] = array(
                    'name' => '',
                    'href' => ''
                );
            }

            $data['products'] = array();

//            echo '<pre>';
//            print_r($_GET);
//            echo '</pre>';
//            die;

            if (isset($_GET['filter']) && !empty($_GET['filter'])) {
                $newFilters = $_GET['filter'];
            } elseif (!isset($_GET['filter']) && empty($_GET['filter'])) {
                $getRoute = rtrim($_GET['_route_'], '/');

                if (strpos($getRoute, '/') == FALSE) {
                    $lastFilter = $getRoute;
                } else {
                    $lastFilter = str_replace('/', '', strrchr($getRoute, '/'));
                }
                if(isset($this->request->get['filter'])){
                    $array_of_ids_all_filters = explode(',', $this->request->get['filter']);
                    if(count($array_of_ids_all_filters) >= 2){
                        $filter_parent_id = $array_of_ids_all_filters[count($array_of_ids_all_filters) - 2];
                        $lastFilter = $this->model_catalog_product->getLastFilterIdWithParent($lastFilter, $filter_parent_id);
                    }else{
                        $lastFilter = $this->model_catalog_product->getLastFilterId($lastFilter);
                    }
                }else{

                    $lastFilter = $this->model_catalog_product->getLastFilterId($lastFilter);
                }

                $newFilters = $lastFilter['filter_id'];
            }



            $filter_data = array(
                'filter_category_id' => $category_id,
                'filter_filter' => $newFilters,
                'sort' => $sort,
                'years' => $years,
                'order_years' => $order_years,
                'special' => $special,
                'order' => $order,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );
//echo '<pre>';
//print_r($filter_data);
//echo '</pre>';
//die;
            $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

            if (isset($this->request->get['price'])) {
                // Current min && max price.
                $hiddenMinPrice = (int)ltrim($this->request->post['hidden__min-price'], ' ₽');
                $hiddenMaxPrice = (int)ltrim($this->request->post['hidden__max-price'], ' ₽');

                // Current DB min && max price.
                $minPrice = floatval(ltrim($this->currency->unFormat($this->tax->calculate($hiddenMinPrice, $this->config->get('config_tax'), $this->config->get('config_tax')), $this->session->data['currency']), ' ₽'));
                $maxPrice = floatval(ltrim($this->currency->unFormat($this->tax->calculate($hiddenMaxPrice, $this->config->get('config_tax'), $this->config->get('config_tax')), $this->session->data['currency']), ' ₽'));
            } else {
                $price = $this->model_catalog_category->getRangeProductPrice();
                $minPrice = $price['min_product_price'];
                $maxPrice = $price['max_product_price'];
            }





            $results_cat = $this->model_catalog_category->getAllCategories();
            foreach ($results_cat as $cat){
                $cats[$cat['parent_id']][$cat['category_id']] =  $cat;
            }

            $data['parent_categories'] = $this->recursive($cats, 0, '', '', '');

            $this->load->language('extension/module/filtersearch');
            $this->load->model('catalog/product');
            $this->load->model('extension/module/filtersearch');
            $data['marks'] = $this->model_extension_module_filtersearch->getAllMarks();
            $data['parentFilters'] = $this->model_extension_module_filtersearch->getAllGroupFilters();
            $data['childFilters'] = $this->model_extension_module_filtersearch->getAllChildFilters();
            $data['siteBase'] = HTTP_SERVER;
            $data['allCategories'] = [];
            $data['allCategories'] = $this->model_extension_module_filtersearch->getAllCategories();






            $results = $this->model_catalog_product->getProducts($filter_data, $minPrice, $maxPrice);
            if(isset($this->request->get['filter'])){
                 $data_filters = $this->model_extension_module_filtersearch->getFiltersData($this->request->get['filter']);
                    foreach ($data_filters as $data_filter){
                        if($data_filter['group_name'] == 'Марка'){
                            $data['marka'] = $data_filter;
                        }elseif($data_filter['group_name'] == 'Модель'){
                            $data['model'] = $data_filter;
                        }elseif($data_filter['group_name'] == 'Модификация'){
                            $data['modification'] = $data_filter;
                        }elseif($data_filter['group_name'] == 'Тип двигателя'){
                            $data['type_engine'] = $data_filter;
                        }elseif($data_filter['group_name'] == 'Объем'){
                            $data['volume'] = $data_filter;
                        }elseif($data_filter['group_name'] == 'КПП'){
                            $data['kpp'] = $data_filter;
                        }elseif($data_filter['group_name'] == 'Тип кузова'){
                            $data['type_body'] = $data_filter;
                        }
                    }
            }



            $data['nonProducts'] = ($product_total == 0) ? 'В данной категории нет товаров.' : null;

            foreach ($results as $result) {
                $images = [];
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                    $product_images = $this->model_catalog_product->getProductImages($result['product_id']);

                    if ($product_images) {
                        foreach ($product_images as $product_image) {
                            $images[] = $this->model_tool_image->resize($product_image['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                        }
                    }
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = (int)$result['rating'];
                } else {
                    $rating = false;
                }

                $images = [];

                $results_images = $this->model_catalog_product->getProductImages($result['product_id']);
                $attributes = $this->model_catalog_product->getProductAttributes($result['product_id']);


                foreach ($results_images as $results_image) {
                    $images[] = [
                        'thumb' => $this->model_tool_image->resize($results_image['image'], $this->config->get($this->config->get('config_theme') . '_image_additional_width'), $this->config->get($this->config->get('config_theme') . '_image_additional_height'))
                    ];
                }

                if (strlen($result['name']) > 85) {
                    $name = substr($result['name'], 0, 77) . "...";
                } else {
                    $name = $result['name'];
                }


                $data['products'][] = array(
                    'product_id' => $result['product_id'],
                    'thumb' => $image,
                    'thumbs' => $images,
                    'images' => $images,
                    'name' => $name,
                    'attributes' => (isset($attributes[0]['attribute'])) ? $attributes[0]['attribute'] : '',
                    'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price' => $price,
                    'special' => $special,
                    'tax' => $tax,
                    'minimum' => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'rating' => $result['rating'],
                    'href' => str_replace(HTTP_SERVER,'goods/', $this->url->link('product/product', 'path=' . '&product_id=' . $result['product_id'] . $url)),
                    'newHref' => $this->config->get('config_url') . 'goods/' . $result['url_alias'],
                );
            }

            $url = '';


//            echo '<pre>';
//            print_r($data['products']);
//            echo '</pre>';
//            die;
//            if (isset($this->request->get['filter'])) {
            //	$url .= '&filter=' . $this->request->get['filter'];
//            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['sorts'] = array();

            $extendedFilter = '';
            if (isset($_GET['filter']) && !empty($_GET['filter'])) {
                $extendedFilter = '&filter=' . $_GET['filter'];
            }
            $fullHref = $data['siteBase'] . $_GET['_route_'] . $extendedFilter;

            $data['sorts'][] = array(
                'text' => 'Без сортировки',
                'value' => '',
                'href' => $fullHref . $url
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_asc'),
                'value' => 'p.price-ASC',
                'href' => $fullHref . '&sort=p.price&order=ASC' . $url
            );

            $data['sorts'][] = array(
                'text' => $this->language->get('text_price_desc'),
                'value' => 'p.price-DESC',
                'href' => $fullHref . '&sort=p.price&order=DESC' . $url
            );

            $data['years'][] = array(
                'text' => $this->language->get('text_sort_year'),
                'value' => '',
                'href' => $fullHref . $url
            );

            $data['years'][] = array(
                'text' => $this->language->get('text_sort_year_asc'),
                'value' => 'p.jan-ASC',
                'href' => $fullHref . '&years=p.jan&order_years=ASC' . $url
            );

            $data['years'][] = array(
                'text' => $this->language->get('text_sort_year_desc'),
                'value' => 'p.jan-DESC',
                'href' => $fullHref . '&years=p.jan&order_years=DESC' . $url
            );

            $data['specials'][] = array(
                'text' => $this->language->get('text_sort_special'),
                'value' => '',
                'href' => $fullHref . $url
            );

            $data['specials'][] = array(
                'text' => $this->language->get('text_sort_special_asc'),
                'value' => 'true',
                'href' => $fullHref . '&special=true' . $url
            );

            $data['specials'][] = array(
                'text' => $this->language->get('text_sort_special_desc'),
                'value' => 'false',
                'href' => $fullHref . '&special=false' . $url
            );


//            $data['sorts'][] = array(
//                'text'  => $this->language->get('text_price_asc'),
//                'value' => 'p.price-ASC',
//                'href'  => $this->url->link('product/category', 'path=' . '&sort=p.price&order=ASC' . $url)
//            );
//
//            $data['sorts'][] = array(
//                'text'  => $this->language->get('text_price_desc'),
//                'value' => 'p.price-DESC',
//                'href'  => $this->url->link('product/category', 'path=' . '&sort=p.price&order=DESC' . $url)
//            );

//            if ($this->config->get('config_review_status')) {
//                $data['sorts'][] = array(
//                    'text'  => $this->language->get('text_rating_desc'),
//                    'value' => 'rating-DESC',
//                    'href'  => $this->url->link('product/category', 'path=' . '&sort=rating&order=DESC' . $url)
//                );
//
//                $data['sorts'][] = array(
//                    'text'  => $this->language->get('text_rating_asc'),
//                    'value' => 'rating-ASC',
//                    'href'  => $this->url->link('product/category', 'path=' . '&sort=rating&order=ASC' . $url)
//                );
//            }

//            $data['sorts'][] = array(
//                'text'  => $this->language->get('text_model_asc'),
//                'value' => 'p.model-ASC',
//                'href'  => $this->url->link('product/category', 'path=' . '&sort=p.model&order=ASC' . $url)
//            );

//            $data['sorts'][] = array(
//                'text'  => $this->language->get('text_model_desc'),
//                'value' => 'p.model-DESC',
//                'href'  => $this->url->link('product/category', 'path=' . '&sort=p.model&order=DESC' . $url)
//            );

            $url = '';

            if (isset($this->request->get['filter'])) {
                //		$url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            $data['limits'] = array();

            $limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

            sort($limits);

            foreach ($limits as $value) {
                $data['limits'][] = array(
                    'text' => $value,
                    'value' => $value,
                    'href' => $this->url->link('product/category', 'path=' . $url . '&limit=' . $value)
                );
            }

//            var_dump($limits);die;

            $url = '';

            if (isset($this->request->get['filter'])) {
                //		$url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $pagination = new Pagination();
            $pagination->total = $product_total;
            $pagination->page = $page;
            $pagination->limit = $limit;
            $pagination->url = substr(HTTP_SERVER, 0, -1) . $_SERVER['REQUEST_URI'] . '&page={page}';
//            $pagination->url = $data['siteBase'] . $this->request->get['_route_'] . '&page={page}';

            $data['pagination'] = $pagination->render(1);

            $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

            // http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
            if ($page == 1) {
                $this->document->addLink($this->url->link('product/category', 'path=', true), 'canonical');
            } elseif ($page == 2) {
                $this->document->addLink($this->url->link('product/category', 'path=', true), 'prev');
            } else {
                $this->document->addLink($this->url->link('product/category', 'path=' . '&page=' . ($page - 1), true), 'prev');
            }

            if ($limit && ceil($product_total / $limit) > $page) {
                $this->document->addLink($this->url->link('product/category', 'path=' . '&page=' . ($page + 1), true), 'next');
            }
            $data['total'] = $product_total;
            $data['sort'] = $sort;
            $data['order'] = $order;
            $data['limit'] = $limit;

            $data['continue'] = $this->url->link('common/home');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $data['search'] = $this->load->controller('common/search');

            if(isset($_SERVER["HTTP_REFERER"])){
                $data['back'] = $_SERVER["HTTP_REFERER"];
            }else{
                $data['back'] = $this->url->link('common/home');
            }

            $this->response->setOutput($this->load->view('product/category', $data));
        } else {
            $url = '';

            if (isset($this->request->get['path'])) {
                $url .= '&path=' . $this->request->get['path'];
            }

            if (isset($this->request->get['filter'])) {
                //		$url .= '&filter=' . $this->request->get['filter'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            if (isset($this->request->get['limit'])) {
                $url .= '&limit=' . $this->request->get['limit'];
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('product/category', $url)
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $data['siteBase'] = HTTP_SERVER;


            $this->response->setOutput($this->load->view('error/not_found', $data));
        }
    }

    public function recursive($cats, $parent_id, $url, $path = '', $path_id){

        if(is_array($cats) and isset($cats[$parent_id])){
            $tree = [];
            foreach($cats[$parent_id] as $key => $cat){

                $tree[$key]['category_id'] = $cat['category_id'];
                $tree[$key]['name'] = $cat['name'];

                if($parent_id == 0){
                    $tree[$key]['href'] =  str_replace(HTTP_SERVER,'catalog/', $this->url->link('product/category', 'path=' . $cat['category_id'] . $url));
                    $tree[$key]['path_id'] = $cat['category_id'];
                }else{
                    $tree[$key]['href'] = str_replace(HTTP_SERVER,'catalog/', $this->url->link('product/category', 'path=' . $path_id . '_' . $cat['category_id'] . $url));
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
