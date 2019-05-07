<?php

class ControllerExtensionModuleFiltersearch extends Controller
{


    private $filterConfig = [
        'auto' => 'Марка',
        'model' => 'Модель',
        'modification' => 'Модификация'
    ];

    private $filterallConfig = [
        'Тип кузова',
        'Рестайлинг',
        'Год',
        'Тип топлива',
        'Тип двигателя',
        'КПП',
        'Тип двигателя',
        'Объем'
    ];

    /**
     * View select filters on home page.
     *
     * @throws Exception
     */
    public function index()
    {
        $this->load->language('extension/module/filtersearch');
        $this->load->model('catalog/product');
        $this->load->model('extension/module/filtersearch');


//        $this->document->addStyle('catalog/view/javascript/jquery/jquery-nice-select-1.1.0/jquery-nice-select-1.1.0/css/nice-select.css');
//        $this->document->addStyle('catalog/view/theme/default/stylesheet/filter_css.css');
//        $this->document->addScript('catalog/view/javascript/jquery/jquery-nice-select-1.1.0/jquery-nice-select-1.1.0/js/jquery.nice-select.js');


        $data['heading_title'] = $this->language->get('heading_title');
        $data['marks'] = $this->model_extension_module_filtersearch->getAllMarks();

        $data['parentFilters'] = $this->model_extension_module_filtersearch->getAllGroupFilters();
        $data['childFilters'] = $this->model_extension_module_filtersearch->getAllChildFilters();
        $data['siteBase'] = $this->config->get('site_base');
        $data['allCategories'] = [];

        $data['allCategories'] = $this->model_extension_module_filtersearch->getAllCategories();


        return $this->load->view('extension/module/filtersearch', $data);
    }

    /**
     * Upload more select filters.
     *
     * @view Filters
     */
    public function getSelectFilters()
    {
        $this->load->model('extension/module/filtersearch');

        if (isset($this->request->post['filterKeyword']) && isset($this->request->post['filterType']) && isset($this->request->post['filterId'])) {
            if ($this->request->post['filterMethod'] == 'generation') {
                $filters['massSum'] = 0;
                $filters['childFilters'] = $this->model_extension_module_filtersearch->getFilters($this->request->post['filterId']);
                $filters['countChildFilters'] = $this->model_extension_module_filtersearch->countFilters($filters['childFilters']);
//                $filters['parentFilters'] = $this->model_extension_module_filtersearch->getAllGroupFilters();
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
//                $deleteObj = array_search('Объем', array_column($filters['parentFilters'], 'name'));
                unset($filters['parentFilters'][$deleteRestail]);
//                unset($filters['parentFilters'][$deleteObj]);

                $temp = [];
                foreach ($filters['parentFilters'] as $parent_filters) {
                    foreach ($filters['childFilters'] as $childFilter) {
                        if ($childFilter['filter_group_id'] == $parent_filters['filter_group_id']) {
                            $temp[$parent_filters['name']][] = $childFilter;
                        }
                    }
                }
//echo '<pre>';
//print_r($this->request->post['filterId']);
//echo '</pre>';
//die;
                $this->response->addHeader('Content-Type: application/json');
                return $this->response->setOutput(json_encode($temp));
//                return $this->response->setOutput($this->load->view('extension/module/select_extended_filters', $data));
            } else {
                $filters['filters'] = $this->model_extension_module_filtersearch->getFilters($this->request->post['filterId']);
                return $this->response->setOutput($this->load->view('extension/module/select_filters', $filters));
            }
        }
    }

    /**
     * Upload more select filters.
     *
     * @view Filters
     */
    public function getSelectExtendedFilters()
    {
        $this->load->model('extension/module/filtersearch');

        if (isset($this->request->post['allMarks'])) {
            $filtersId = implode(', ', $this->request->post['allMarks']);
            $filters['massSum'] = 0;
            $filters['childFilters'] = $this->model_extension_module_filtersearch->getSecondFilters($filtersId);
//            $filters['countChildFilters'] = $this->model_extension_module_filtersearch->countFilters($filters['childFilters']);
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

            return $this->response->setOutput($this->load->view('extension/module/category_extended_filters', $filters));
        }

        if (isset($this->request->post['filterKeyword']) && isset($this->request->post['filterType']) && isset($this->request->post['filterId'])) {
            if ($this->request->post['filterMethod'] == 'generation') {
                $filters['massSum'] = 0;
                $filters['childFilters'] = $this->model_extension_module_filtersearch->getFilters($this->request->post['filterId']);
                $filters['countChildFilters'] = $this->model_extension_module_filtersearch->countFilters($filters['childFilters']);
                $parentFilters = $this->model_extension_module_filtersearch->getAllGroupFilters();

//                $parentFilters['parentFilters'] = array_splice($filters['parentFilters'],array_search('Тип двигателя',array_keys($filters['parentFilters'])),1) + $filters['parentFilters'];
//
//                return $this->response->setOutput($this->load->view('extension/module/select_extended_filters', $filters));
//            } elseif ($this->request->post['filterMethod'] == 'generation') {
//                $filters['parentFilters'] = $this->model_extension_module_filtersearch->getAllGroupFilters();
//                $filters['childFilters'] = $this->model_extension_module_filtersearch->getFilters($this->request->post['filterId']);
//                $filters['countChildFilters'] = $this->model_extension_module_filtersearch->countFilters($filters['childFilters']);

                $filters['parentFilters'] = [];
                foreach ($parentFilters as $key => $sideFilter) {
                    if ($sideFilter['name'] == 'Тип двигателя') {
                        unset($parentFilters[$key]);
                        array_unshift($filters['parentFilters'], $sideFilter);
                    } else {
                        $filters['parentFilters'][] = $sideFilter;
                    }
                }

                return $this->response->setOutput($this->load->view('extension/module/category_extended_filters', $filters));
            } else {
                $filters['filters'] = $this->model_extension_module_filtersearch->getFilters($this->request->post['filterId']);
                return $this->response->setOutput($this->load->view('extension/module/select_filters', $filters));
            }
        }
    }

    public function allfilters()
    {
        $this->load->model('extension/module/filtersearch');
        $filters_main = $this->filterallConfig;

        $json = array();

        foreach ($filters_main as $filter_main) {
            $filtergroups = $this->model_extension_module_filtersearch->getFilterGroup($filter_main);

            $children_data = array();
            $children = $this->model_extension_module_filtersearch->getAllFilter($filtergroups['filter_group_id']);

            foreach ($children as $child) {
                $children_data[] = array(
                    'filter_id' => $child['filter_id'],
                    'name' => $child['name']
                );
            }
            $json[] = array(
                'filter_group_id' => $filtergroups['filter_group_id'],
                'name' => $filtergroups['name'],
                'children' => $children_data
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function models()
    {

        $json = array();

        $filters_model = $this->filterConfig['model'];


        $brand_id = $this->request->get['filter_id'];


        if (isset($this->request->post['brand_id'])) {
            $data['brand_id'] = (int)$this->request->post['brand_id'];
        } elseif (isset($this->session->data['brand_id'])) {
            $data['brand_id'] = $this->session->data['brand_id'];
        } else {
            $data['brand_id'] = '';
        }

        if ($brand_id) {
            $this->load->model('extension/module/filtersearch');

            $product_id = $this->model_extension_module_filtersearch->getPoductFilter($brand_id);


            $models = array();
            foreach ($product_id as $product) {
                $models[] = $this->model_extension_module_filtersearch->getModels($product['product_id'], $filters_model);
            }

            $model_name = array();
            foreach ($models as $model) {
                $model_name[] = $model['filter_id'];
            }
            $model_name = array_unique($model_name);

            foreach ($model_name as $model_namenew) {
                $json[] = $this->model_extension_module_filtersearch->getFilter($model_namenew);
            }
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function modification()
    {
        $json = array();

        $filters_modification = $this->filterConfig['modification'];
        $model_id = $this->request->get['filter_id'];

        if (isset($this->request->post['model_id'])) {
            $data['model_id'] = (int)$this->request->post['model_id'];
        } elseif (isset($this->session->data['model_id'])) {
            $data['model_id'] = $this->session->data['model_id'];
        } else {
            $data['model_id'] = '';
        }

        if ($model_id) {
            $this->load->model('extension/module/filtersearch');

            $product_id = $this->model_extension_module_filtersearch->getPoductFilter($model_id);
            $modifications = array();
            foreach ($product_id as $product) {
                $modifications[] = $this->model_extension_module_filtersearch->getModels($product['product_id'], $filters_modification);
            }

            $modification_name = array();
            foreach ($modifications as $modification) {
                $modification_name[] = $modification['filter_id'];
            }
            $modification_name = array_unique($modification_name);

            foreach ($modification_name as $modification_namenew) {
                $json[] = $this->model_extension_module_filtersearch->getFilter($modification_namenew);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function products()
    {
        if (isset($this->request->get['filters'])) {
            $filter_id = $this->request->get['filters'];
        } else {
            $filter_id = array();
        }

        if (!empty($filter_id)) {

            $filter_auto = $filter_id;

            $this->load->model('catalog/product');
            $filter_data_auto = array(
                'filter_auto' => $filter_auto
            );
            $poducts = $this->model_catalog_product->getProducts($filter_data_auto);
        }

        $json = array();


        foreach ($poducts as $jsonhoduct) {
            $json [] = array(
                'product_id' => $jsonhoduct['product_id'],
                'name' => $jsonhoduct['name'],
                'href' => $this->url->link('product/product', '&product_id=' . $jsonhoduct['product_id'])
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function autocomplete()
    {
        $json = array();
        if (isset($this->request->get['filter_name'])) {
            $this->load->model('extension/module/filtersearch');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'filter_id' => isset($this->request->get['filter_id']) ? $this->request->get['filter_id'] : 1,
                'sort' => 'name',
                'order' => 'ASC',
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_extension_module_filtersearch->getCategorysToFilter($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'category_id' => $result['category_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'keyword' => strip_tags(html_entity_decode($result['keyword'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}