<?php

class ControllerStartupSeoUrl extends Controller
{
    public function index()
    {

        // Add rewrite to url class
        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }


        // Decode URL
        if (isset($this->request->get['_route_'])) {

            $parts = $fullUrl = explode('/', $this->request->get['_route_']);

            if ($parts[0] !== 'catalog' && $parts[0] !== 'goods' && $parts[0] !== 'zapchasti') {

                $request = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($parts[0]) . "'")->row;
                $requestType = explode('=', $request['query']);
                if ($requestType[0] == 'category_id' || $requestType[0] == 'filter' || $requestType[0] == 'product_id') {
                    $this->request->get['route'] = 'error/not_found';
                }
            } else {
                $first_element_of_url = $parts['0'];
                unset($parts['0']);
            }

            // remove any empty arrays from trailing
            if (utf8_strlen(end($parts)) == 0) {
                array_pop($parts);
            }


            $this->load->model('catalog/product');


//echo '<pre>';
//print_r($first_element_of_url);
//echo '<pre>';
//print_r($parts);
//echo '<pre>';
//print_r($this->request->get);
//echo '</pre>';
//die;


            if (isset($first_element_of_url) && ($first_element_of_url == 'zapchasti')) {
                foreach ($parts as $key => $alias) {
                    if ($key > 1) {
                        $temp_array_of_filter_ids = explode(',', $this->request->get['filter']);
                        $lastFilter = $this->model_catalog_product->getLastFilterIdWithParent($alias, end($temp_array_of_filter_ids));
                        $this->request->get['filter'] .= ',' . $lastFilter['filter_id'];

                    } else {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($alias) . "'");
                        if ($query->num_rows) {
                            $url = explode('=', $query->row['query']);
                            if ($url[0] == 'filter') {
                                if (!isset($this->request->get['filter'])) {
                                    $this->request->get['filter'] = $url[1];
                                } else {
                                    $this->request->get['filter'] .= ',' . $url[1];
                                }
                            }
                        }
                    }

                }
            }

            if (isset($first_element_of_url) && ($first_element_of_url == 'catalog')) {
                $filters = [];
                foreach ($parts as $key => $alias) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($alias) . "'");
                    if ($query->num_rows) {
                        $url = explode('=', $query->row['query']);
                        if ($url[0] == 'filter') {
                            $filters[] = $alias;
                        }
                    }
                }
                $parent_id = 0;

                foreach ($filters as $key => $alias) {
                    if ($key == 0) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($alias) . "'");
                        $url = explode('=', $query->row['query']);
                        $parent_id = $url[1];
                        if(isset($this->request->get['filter'])){
                            $this->request->get['filter'] .= ',' . $parent_id;
                        }else{
                            $this->request->get['filter'] = $parent_id;
                        }
                    }else{
                        $lastFilter = $this->model_catalog_product->getLastFilterIdWithParent($alias, $parent_id);
                        $this->request->get['filter'] .= ',' . $lastFilter['filter_id'];
                        $parent_id = $lastFilter['filter_id'];
                    }
                }
            }








            foreach ($parts as $part) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

                if ($query->num_rows) {
                    $url = explode('=', $query->row['query']);

                    if ($url[0] == 'product_id') {
                        $this->request->get['product_id'] = $url[1];
                    }

                    if ($url[0] == 'category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }

//                    if ($url[0] == 'filter' && $first_element_of_url == 'catalog') {
//                        if (!isset($this->request->get['filter'])) {
//                            $this->request->get['filter'] = $url[1];
//                        } else {
//                            $this->request->get['filter'] .= ',' . $url[1];
//                        }
//                    }

                    if ($url[0] == 'manufacturer_id') {
                        $this->request->get['manufacturer_id'] = $url[1];
                    }

                    if ($url[0] == 'information_id') {
                        $this->request->get['information_id'] = $url[1];
                    }

                    if ($query->row['query'] && $url[0] != 'information_id' && $url[0] != 'manufacturer_id' && $url[0] != 'category_id' && $url[0] != 'product_id' && $url[0] != 'filter') {
                        $this->request->get['route'] = $query->row['query'];
                    }
                } else {
                    $this->request->get['route'] = 'error/not_found';
                    break;
                }
            }

            if (!isset($this->request->get['route'])) {
                if (isset($this->request->get['product_id'])) {
                    $this->request->get['route'] = 'product/product';
                } elseif (isset($this->request->get['filter'])) {
                    $this->request->get['route'] = 'product/category';
                } elseif (isset($this->request->get['path'])) {
                    $this->request->get['route'] = 'product/category';
                } elseif (isset($this->request->get['manufacturer_id'])) {
                    $this->request->get['route'] = 'product/manufacturer/info';
                } elseif (isset($this->request->get['information_id'])) {
                    $this->request->get['route'] = 'information/information';
                }
            }
        }

    }

    public function rewrite($link)
    {

//        echo '<pre>';
//        print_r($link);
//        echo '</pre>';
//        die;
        $url_info = parse_url(str_replace('&amp;', '&', $link));

        $url = '';

        $data = array();

        parse_str($url_info['query'], $data);



        foreach ($data as $key => $value) {
            if (isset($data['route'])) {
                if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
                    if ($query->num_rows && $query->row['keyword']) {
                        $url .= '/' . $query->row['keyword'];
                        unset($data[$key]);
                    }
                } elseif ($data['route'] == 'extension/module/filter') {
                } elseif ($key == 'path') {
                    $categories = explode('_', $value);

                    foreach ($categories as $category) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");

                        if ($query->num_rows && $query->row['keyword']) {
                            $url .= '/' . $query->row['keyword'];
                        } else {
                            $url = '';

                            break;
                        }
                    }

                    unset($data[$key]);
                }
            }
        }

        if ($url) {
            unset($data['route']);

            $query = '';

            if ($data) {

                if (isset($this->request->get['filter'])) {

                    foreach ($data as $key => $value) {
                        $query .= rawurlencode((string)$key) . '/' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
                    }

                    if ($query) {
                        $query = '/' . str_replace('&', '&amp;', trim($query, '&'));
                    }
                } else {
                    foreach ($data as $key => $value) {
                        $query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
                    }

                    if ($query) {
                        $query = '?' . str_replace('&', '&amp;', trim($query, '&'));
                    }
                }


            }

            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
        } else {
            return $link;
        }
    }
}
