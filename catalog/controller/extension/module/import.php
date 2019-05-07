<?php

class ControllerExtensionModuleImport extends Controller
{

    private function colorText($color, $text)
    {
        // 1 - RED color text
        // 2 - GREEN color text
        // 3 - ORANGE color text
        // 4 - ANGRY_RED color text
        // 5 - BLUE color text
        // 6 - AQUA color text
        passthru('echo $(tput setaf ' . $color . ')');
        echo $text;
        passthru('echo $(tput sgr0)');

        return;
    }



    /**
     * Config to connect API.
     *
     * @var array
     */
    private $connectConfig = [
        'url' => 'https://f-avto.by/api.php?key=KeyZa23052018&method=',
        'start' => 1082130,
        'step' => 10000,
        'end' => 1082135,
//        'end' => 3020000,
//        'start' => 1845824,
//        'step' => 1,
//        'end' => 1845830,
        'maxProducts' => 4020000,
        'status' => true,
    ];

    /**
     * ID group. Need create in group id 'Описание'.
     *
     * @var int
     */
    private $_idGroupAttrebut = 1;

    /**
     * Filters config.
     *
     * @var array
     */
    private $filterConfig = [
        'auto' => 'Марка',
        'model' => 'Модель',
        'modification' => 'Модификация',
        'body_type' => 'Тип кузова',
        'restailing' => 'Рестайлинг',
        'year' => 'Год',
        'fuel' => 'Тип топлива',
        'dvs' => 'Тип двигателя',
        'kpp' => 'КПП',
        'engine_type' => 'Тип двигателя',
        'volume' => 'Объем',
        'no_modification' => "нет модификации"
    ];

    /**
     * Attribute config.
     *
     * @var array
     */
    private $atributeConfig = [
        'id_car' => 'ID авто',
        'leyba' => 'Лейба авто',
        'engine_number' => 'Номер ДВС/Тип КПП',
        'race' => 'Пробег',
        'doors' => 'Кол-во дверей',
        'hp' => 'кВт/л.с.'
    ];

    /**
     * Attributes for products.
     *
     * @var array
     */
    private $_attributeValue = [
        'auto' => 'Марка',
        'model' => 'Модель',
        'modification' => 'Модификация',
        'body_type' => 'Тип кузова',
        'year' => 'Год',
        'restailing' => 'Рестайлинг',
        'fuel' => 'Тип топлива',
        'dvs' => 'Тип двигателя',
        'kpp' => 'КПП',
        'engine_type' => 'Тип двигателя',
        'volume' => 'Объем',
    ];

    /**
     * Array for preview categories.
     *
     * @var array
     */
    private $mainCategories = [
        'Двигатели',
        'Запчасти для двигателя',
        'Кузовные запчасти',
        'Рулевое управление',
        'Топливная система',
        'Трансмиссия'
    ];

    /**
     * Function to API connect.
     *
     * @param $method
     * @return mixed|null
     */
    private function apiConnect($method)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->connectConfig['url'] . $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, TRUE);
    }

    /**
     * Transliterate russian letters to english.
     *
     * @param $string
     * @return string
     */
    private function translit($string)
    {
        $string = (string)$string;
        $string = trim($string);
        $string = function_exists('mb_strtolower') ? mb_strtolower($string) : strtolower($string);
        $string = strtr($string, [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya', 'ъ' => '', 'ь' => '', ' ' => '-', '!' => '',
            '(' => '', ')' => '', "'" => '', '&' => ''
        ]);
        return $string;
    }



        /**
     * Upload images from API.
     *
     * @param $type
     * @param $url
     * @return string
     */
    private function uploadImage($type, $url)
    {
        if (!empty($url)) {

            $uploadDir = DIR_IMAGE . 'catalog/' . $type . '/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            $clearUrl = strtok($url, '?');
            $uploadedImg = 'catalog/' . $type . '/' . basename($clearUrl);
            if (!file_exists($uploadDir . basename($clearUrl))) {
                file_put_contents($uploadDir . basename($clearUrl), file_get_contents($url));
                return $uploadedImg;
            } else {
                return 'no_image.png';
            }
        }
    }

    /**
     * Get categories from API.
     *
     * @return mixed|null
     */
    public function getCategories()
    {
        $categories = self::apiConnect('getCategory');
//        echo '<pre>';
//        print_r($categories);
//        echo '</pre>';
//        die;

//        foreach ($categories as $categoryKey => $categoryValue) {
//            if (!in_array($categoryValue['title'], $this->mainCategories)) {
//                unset($categories[$categoryKey]);
//            }
//        }

        return $categories;
    }

    /**
     * Add categories from API.
     *
     * @throws Exception
     */
    public function addCategories($categories = NULL, $categoyId = NULL)
    {
        $this->load->model('catalog/category');
        $this->load->model('extension/module/import');

        if (is_null($categories) && is_null($categoyId)) {
            $categories = $this->getCategories();
        }

        foreach ($categories as $category) {
            $data = [
                'id' => $category['id'],
                'title' => $category['title'],
                'img' => self::uploadImage('category', $category['img']),
                'description' => $category['description'],
                'parent_id' => ($categoyId != NULL) ? $categoyId : '0',
                'top' => (in_array($category['title'], $this->mainCategories) ? '1' : '0'),
                'column' => 4,
                'sort_order' => 0,
                'level' => 0,
                'status' => 1,
                'language_id' => 1,
                'seo_url' => self::translit($category['title']),
                'parent_path_id' => ($categoyId != NULL) ? $categoyId : '0',
                'meta_title' => $category['title'],
                'meta_description' => '',
                'meta_keyword' => ''
            ];
            $this->model_extension_module_import->createOrUpdateCategory($data);
            if (isset($category['childNodes']) && !empty($category['childNodes'])) {
                $this->addCategories($category['childNodes'], $category['id']);
            }
        }
    }

    /**
     * Set filters to categories.
     *
     * @throws Exception
     */
    public function filtersToCategories()
    {
        $this->load->model('extension/module/import');
        $this->model_extension_module_import->clearDatabase('category_filter');
        $categories = $this->model_extension_module_import->getAllCategories();
        foreach ($categories as $category) {
            $this->model_extension_module_import->filtersToCategories($category['category_id']);
        }
    }

    /**
     * Update URL for filters in DB.
     *
     * @DB insert;
     */
    public function updateFilterUrl()
    {
        $this->load->model('extension/module/import');
        $filters = $this->model_extension_module_import->getAllFilters();
        foreach ($filters as $filter) {
            $filterData = [
                'id' => $filter['filter_id'],
                'title' => self::translit($filter['name']),
                'url_alias_id' => $filter['url_alias_id']
            ];
            $this->model_extension_module_import->createOrUpdateFiltersUrl($filterData);
        }
    }

    /**
     * Set parent filters in filters.
     *
     * @throws Exception
     */
    public function parentFilters()
    {
        $this->load->model('extension/module/import');
        $this->load->model('extension/module/filtersearch');

        $this->model_extension_module_import->clearDatabase('filter_parent');
        $marks = $this->model_extension_module_filtersearch->getAllMarks();

        foreach ($marks as $mark) {
            $products = $this->model_extension_module_import->getProductOfFilter($mark['filter_id']);
            $products = implode(', ', array_map(function ($prodId) {
                return implode('~', $prodId);
            }, $products));
            $childFilters = $this->model_extension_module_import->getChildFilter($products, 'Модель');

            foreach ($childFilters as $childFilter) {
                $this->model_extension_module_import->setParentFilter($mark['filter_id'], $childFilter['filter_id']);
                $childProducts = $this->model_extension_module_import->getProductOfFilter($childFilter['filter_id']);
                $childProducts = implode(', ', array_map(function ($chilProdId) {
                    return implode('~', $chilProdId);
                }, $childProducts));
                $serieses = $this->model_extension_module_import->getChildFilter($childProducts, 'Модификация');
                foreach ($serieses as $series) {
                    $this->model_extension_module_import->setParentFilter($childFilter['filter_id'], $series['filter_id']);
                }
            }
        }
    }

    /**
     * Add manufacturer.
     *
     * @throws Exception
     */
    public function addManufacturer()
    {
        $manufactures = self::apiConnect('getManufacturer');

        $this->load->model('catalog/manufacturer');
        $this->load->model('extension/module/import');

        foreach ($manufactures as $data) {
            $manufacturer = array(
                'manufacturer_id' => $data['id'],
                'name' => $data['title'],
                'image' => $data['img']
            );

            $manufacturer_id = $this->model_catalog_manufacturer->getManufacturer($manufacturer['manufacturer_id']);

            if ((!empty($manufacturer_id)) && ($manufacturer_id['manufacturer_id'] == $manufacturer['manufacturer_id'])) {
                $this->model_extension_module_import->editManufacturer($manufacturer_id, $manufacturer);
            } else {
                $this->model_extension_module_import->addManufacturer($manufacturer);
            }

            if (!empty($manufacturer['image'])) {
                $image = $this->uploadImage('manufacturer', $manufacturer['image']);
                $this->model_extension_module_import->addManufacturerImage($manufacturer['manufacturer_id'], $image);
            }
        }

    }

    /**
     * UPDATE ALL PRODUCTS FROM TERMINAL PHP.
     */
    public function updateProduct()
    {
        $this->addAttrebut();
        $this->addFilters();

        $products_ids = $this->getAvalibleProduct();
        sort($products_ids);
        $chunks = array_chunk($products_ids,100);
        $count = 0;
        foreach ($chunks as $chunk) {
                $end = count($chunk) - 1;
                $this->connectConfig['start'] = $chunk[0];
                $this->connectConfig['end'] = $chunk[$end];
                $this->nap();
                $count = $count + 100;
                print_r( "Add " . $count . " products \n  From id=" . $chunk[0] . " to id=" . $chunk[99] . "\n");

        }


//        while ($this->connectConfig['status']) {

//        foreach ($products_ids as $key => $products_id){
//            $this->connectConfig['start'] = $products_id;
//            $this->connectConfig['end'] = $products_id;
//            $this->nap();
//            sleep(1);
//            echo "Start = " . $this->connectConfig['start'] . " End = " . $this->connectConfig['end'] . "\n";
//        }

//            $this->connectConfig['end'] = $this->connectConfig['start'] + $this->connectConfig['step'];
//            $this->nap();
//            sleep(1);
//            echo "Start = " . $this->connectConfig['start'] . " End = " . $this->connectConfig['end'] . "\n";
//            $this->connectConfig['start'] = $this->connectConfig['end'];
//        }
    }


    /**
     * FULL CLEAR & START CLI.
     */
    public function fullUpdateCli()
    {
	// Truncate attributes
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."attribute");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."attribute_description");




	// Truncate categories
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."category");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."category_description");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."category_filter");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."category_path");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."category_to_layout");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."category_to_store");

	// Truncate filters.
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."filter");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."filter_description");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."filter_group");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."filter_group_description");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."filter_parent");

	// Truncate products.
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_attribute");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_description");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_discount");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_filter");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_image");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_option");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_option_value");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_parent");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_recurring");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_related");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_reward");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_special");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_to_category");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_to_download");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_to_layout");
	$this->db->query("TRUNCATE TABLE ". DB_PREFIX ."product_to_store");

	// Delete from oc_url_alias old URL`s.
	$this->db->query("DELETE FROM ". DB_PREFIX ."url_alias WHERE query LIKE '%filter%'");
	$this->db->query("DELETE FROM ". DB_PREFIX ."url_alias WHERE query LIKE '%product_id%'");
	$this->db->query("DELETE FROM ". DB_PREFIX ."url_alias WHERE query LIKE '%category_id%'");

	// Start other CLI.
	exec('php /app/oc_cli.php catalog extension/module/import/addCategories');
	exec('php /app/oc_cli.php catalog extension/module/import/updateProduct');
	exec('php /app/oc_cli.php catalog extension/module/import/updateFilterUrl');
	exec('php /app/oc_cli.php catalog extension/module/import/filtersToCategories');

	die;
    }


    /*private $filterConfig = [
        'auto' => 'Марка',
        'model' => 'Модель',
        'modification' => 'Модификация',
        'body_type' => 'Тип кузова',
        'restailing' => 'Рестайлинг',
        'year' => 'Год',
        'fuel' => 'Тип топлива',
        'dvs' => 'Тип двигателя',
        'kpp' => 'КПП',
        'engine_type' => 'Тип двигателя',
        'volume' => 'Объем'
    ];*/

    public function getAvalibleProduct()
    {
        echo "asd";
        $url = 'getAvailableProducts';
        $datas = self::apiConnect($url);
//        print_r($datas);
//        $products_id = json_decode($datas);
//        print_r($products_id);
        return $datas;
    }

    /**
     * Upload products from API to DB.
     *
     * @throws Exception
     */
    public function nap()
    {
        $this->load->model('catalog/product');
        $this->load->model('extension/module/import');



        $url = 'getProducts&start=' . $this->connectConfig['start'] . '&end=' . $this->connectConfig['end'];
        $datas = self::apiConnect($url);


        if ($datas == "false") {
            $this->connectConfig['status'] = false;
        }

        foreach ($datas as $data) {
            $categoriesIds = array();
            if (!empty($data['ids_menu'])) {
                $categoriesIds = $this->model_extension_module_import->getCategoriesIds($data['ids_menu']);

                $attrebutList = [];

                foreach ($data['attributes_filter'] as $key => $attrebut) {
                    if ($attrebut != "") {
                        if (array_key_exists($key, $this->_attributeValue)) {
                            $attrebut_id = $this->model_extension_module_import->getAttribute($this->_attributeValue[$key])['attribute_id'];
                            $attrebutList[$attrebut_id] = $attrebut;
                        }
                    }
                }

                $filterList = [];
                foreach ($data['attributes_filter'] as $key => $filter) {
                    if ($filter != "") {
                        if (array_key_exists($key, $this->filterConfig)) {
                            $filter_group = $this->model_extension_module_import->getFilterGroup($this->filterConfig[$key]);
                            if ($filter_group != -1) {
                                switch ($key) {
                                    case 'auto':
                                        $filter_id = $this->model_extension_module_import->addFilterValueToGroup($filter_group['filter_group_id'], $filter);

                                        if ($filter_id == -1) {

                                            $filterList['auto'][$filter_group['filter_group_id']] = $filter_id;
                                        } else {
                                            $filterList['auto'][$filter_group['filter_group_id']] = $filter_id;
                                        }
                                        break;
                                    case 'model':
                                        foreach ($filterList['auto'] as $key => $value) {
                                            $filter_id = $this->model_extension_module_import->addFilterValueToGroup($filter_group['filter_group_id'], $filter, $value);
                                            $filterList['model'][$filter_group['filter_group_id']] = $filter_id;
                                        }
                                        break;
                                    case 'modification':
                                        foreach ($filterList['model'] as $key => $value) {
                                            $filter_id = $this->model_extension_module_import->addFilterValueToGroup($filter_group['filter_group_id'], $filter, $value);
                                            $filterList['modification'][$filter_group['filter_group_id']] = $filter_id;
                                        }
                                        break;
                                    default:
                                        if (array_key_exists('modification', $filterList)) {
                                            foreach ($filterList['modification'] as $key => $value) {
                                                $filter_id = $this->model_extension_module_import->addFilterValueToGroup($filter_group['filter_group_id'], $filter, $value);
                                                $filterList[$key][$filter_group['filter_group_id']] = $filter_id;
                                            }

                                        } else {
                                            $filter_id = $this->model_extension_module_import->addFilterValueToGroup($filter_group['filter_group_id'], $filter, $this->_filter_value_id);
                                            $filterList[$key][$filter_group['filter_group_id']] = $filter_id;

                                        }

                                }
                            }
                        }
                    }
                }

                if ($data['price'] > 0) {
                    $productStatus = 1;
                } else {
                    $productStatus = 0;
                }

                $product = array(
                    'product_description' => array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $data['nomenclature'] . " на " . $data['attributes_filter']['auto'] . " " . $data['attributes_filter']['model'] . " " . $data['attributes_filter']['modification'],
                            'meta_title' => $data['nomenclature'] . " на " . $data['attributes_filter']['auto'] . " " . $data['attributes_filter']['model'] . " " . $data['attributes_filter']['modification'],
                            'description' => $data['description']
                        ),
                    ),
                    'model' => $data['attributes_filter']['detail_code'],
                    'ean' => $data['id'],                                 //исходный код продукта
                    //                'categories' => $data['ids_menu'], //array
                    'categories' => $categoriesIds, //array
                    'jan' => $data['attributes_filter']['year'],
                    'manufacturer_id' => $data['attributes_filter']['producter_id'],
                    'attributes_filter' => $attrebutList, //array
                    'price' => $data['price'],
                    'attributes' => $data['attributes'], //array
                    'product_filters' => $filterList,
                    'images' => $data['imgs'],  //array
                    'count' => $data['count'],
                    'compl' => $data['compl'], //array
                    'ids_similar' => $data['ids_similar'],
                    'status' => $productStatus,
                    'url_name' => $this->translit($data['nomenclature'] . " на " . $data['attributes_filter']['auto'] . " " . $data['attributes_filter']['model'] . " " . $data['attributes_filter']['modification'])
                );


                $product_id = $this->model_extension_module_import->getProduct($product['ean']);

                if ((!empty($product_id)) && ($product_id['ean'] == $product['ean'])) {
                    $this->model_extension_module_import->editProduct($product_id['product_id'], $product);
                } else {
                    $product_id = $this->model_extension_module_import->addProduct($product);
                }

                if (isset($data['compl']) && !empty($data['compl'])) {
                    $this->model_extension_module_import->updateProductParent($data['id'], $data['compl']);
                }

                if ((!empty($product['images'])) && $product['images'][0] !== '') {
                    $i = 0;
                    $images = [];
                    foreach ($product['images'] as $image) {
//                        $url = strtok($image, '?');
                        $this->uploadImage('product', $image);
                        $value = 'catalog/product/' . basename($product['images'][$i], "?nowatermark");
                        array_push($images, $value);
                        $i++;
                    }
                    $this->model_extension_module_import->addProductImage($product_id, $images);
                }

            }
        }
    }

    private $_filter_value_id = null;

    private function addFilters()
    {
        $this->load->model('extension/module/import');
        $filters_group = [];

        foreach ($this->filterConfig as $key => $filter) {
            $filters_group = [
                'sort' => 0,
                'filter_group_description' => [
                    (int)$this->config->get('config_language_id') => [
                        "name" => $filter
                    ]
                ]
            ];
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filters_group);

            if ($key == "no_modification") {

                $filterData = array();
                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $filter
                    )
                );

                $this->_filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_group_id);
            }
        }

    }

    private function addAttrebut()
    {
        $this->load->model('extension/module/import');
        $atribute_group_main = [];
        foreach ($this->filterConfig as $key => $attrebut) {
            $atribute_group_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $attrebut
                )
            );

            $atribute_group_id = $this->model_extension_module_import->addAttribute($this->_idGroupAttrebut, $atribute_group_main);
        }

    }

    public function addProduct()
    {
        $this->load->model('catalog/product');
        $this->load->model('extension/module/import');

        $url = 'getProducts&start=' . $this->connectConfig['start'] . '&end=' . $this->connectConfig['end'];
        $datas = self::apiConnect($url);

        if (empty($datas)) {
            $datas = [];
        }

        $atribute_group_id = $this->model_extension_module_import->getAtributeGroup($this->atributeGroup);
        if (empty($atribute_group_id)) {

            $atribute_group_main['attribute_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $this->atributeGroup
                )
            );
            $atribute_group_main['sort_order'] = "";
            $atribute_group_id = $this->model_extension_module_import->addAtributeGroup($atribute_group_main);

        }

        $auto_avto = $this->filterConfig['auto'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $auto_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $auto_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($auto_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $auto_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }

        $model_avto = $this->filterConfig['model'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $model_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $model_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($model_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $model_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }

        $modification_avto = $this->filterConfig['modification'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $modification_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $modification_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($modification_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $modification_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }
        $body_type_avto = $this->filterConfig['body_type'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $body_type_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $body_type_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($body_type_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $body_type_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }

        $restailing_avto = $this->filterConfig['restailing'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $restailing_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $restailing_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($restailing_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $restailing_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }
        $year_avto = $this->filterConfig['year'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $year_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $year_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($year_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $year_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }

        $fuel_avto = $this->filterConfig['fuel'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $fuel_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $fuel_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($fuel_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $fuel_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }

        $dvs_avto = $this->filterConfig['dvs'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $dvs_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $dvs_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($dvs_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $dvs_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }
        $kpp_avto = $this->filterConfig['kpp'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $kpp_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $kpp_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($kpp_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $kpp_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }
        $engine_type_avto = $this->filterConfig['engine_type'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $engine_type_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $engine_type_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($engine_type_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $engine_type_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }
        $volume_avto = $this->filterConfig['volume'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $volume_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $volume_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $filter_group_id = $this->model_extension_module_import->getFilterGroup($volume_avto);
        if (empty($filter_group_id)) {
            $filter_main['filter_group_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $volume_avto
                )
            );
            $filter_main['sort'] = "";
            $filter_group_id = $this->model_extension_module_import->addFilterGroup($filter_main);
        }
        $id_car_avto = $this->atributeConfig['id_car'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $id_car_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $id_car_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }


        $leyba_avto = $this->atributeConfig['leyba'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $leyba_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $leyba_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $engine_number_avto = $this->atributeConfig['engine_number'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $engine_number_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $engine_number_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $race_avto = $this->atributeConfig['race'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $race_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $race_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $doors_avto = $this->atributeConfig['doors'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $doors_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $doors_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        $hp_avto = $this->atributeConfig['hp'];

        $atribute_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $hp_avto);
        if (empty($atribute_id)) {
            $atribute_main['attribute_description'] = array(
                (int)$this->config->get('config_language_id') => array(
                    'name' => $hp_avto
                )
            );
            $atribute_main['sort_order'] = "";
            $atribute_id = $this->model_extension_module_import->addAttribute($atribute_group_id['attribute_group_id'], $atribute_main);
        }

        foreach ($datas as $data) {
            $product = array(
                'product_description' => array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $data['nomenclature'] . " на " . $data['attributes_filter']['model'] . " " . $data['attributes_filter']['modification'],
                        'meta_title' => $data['nomenclature'] . " на " . $data['attributes_filter']['model'] . " " . $data['attributes_filter']['modification'],
                        'description' => $data['description']
                    ),
                ),
                'model' => $data['attributes_filter']['detail_code'],
                'ean' => $data['id'],                                 //исходный код продукта
                'categories' => $data['ids_menu'], //array
                'manufacturer_id' => $data['attributes_filter']['producter_id'],
                'attributes_filter' => $data['attributes_filter'], //array
                'price' => $data['price'],
                'attributes' => $data['attributes'], //array
                'images' => $data['imgs'],  //array
                'count' => $data['count'],
                'compl' => $data['compl'], //array
                'ids_similar' => $data['ids_similar']
            );

            $product_id = $this->model_extension_module_import->getProduct($product['ean']);

            if ((!empty($product_id)) && ($product_id['ean'] == $product['ean'])) {
                $this->model_extension_module_import->editProduct($product_id['product_id'], $product);
            } else {
                $this->model_extension_module_import->addProduct($product);
            }


            $product_id = $this->model_extension_module_import->getProduct($product['ean']);
            $product['product_id'] = $product_id['product_id'];

            $attributes_filter = $product['attributes_filter'];
            $attribut_filter = array(
                'sku' => $attributes_filter['detail_code'],
                'manufacturer' => $attributes_filter['auto'],
                'model' => htmlspecialchars($attributes_filter['model'], ENT_QUOTES),
                'modification' => $attributes_filter['modification'],
                'body_type' => $attributes_filter['body_type'],
                'year' => $attributes_filter['year'],
                'restailing' => $attributes_filter['restailing'],
                'fuel' => $attributes_filter['fuel'],
                'dvs' => $attributes_filter['dvs'],
                'kpp' => $attributes_filter['kpp'],
                'manufacturer_id' => $attributes_filter['producter_id'],
                'model_id' => $attributes_filter['model_id'],
                'engine_type' => $attributes_filter['engine_type'],
                'volume' => $attributes_filter['volume']
            );


            if (!empty($attribut_filter['manufacturer'])) {
                $manufacturer = $this->filterConfig['auto'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($manufacturer);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['manufacturer']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['manufacturer']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['manufacturer']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $manufacturer);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['manufacturer']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }
            }

            if (!empty($attribut_filter['model'])) {
                $model = $this->filterConfig['model'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($model);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['model']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['model']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['model']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);


                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $model);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['model']
                        )
                    );


                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }
            }

            if (!empty($attribut_filter['year'])) {
                $year = $this->filterConfig['year'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($year);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['year']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['year']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['year']);
                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);
                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $year);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['year']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }
            }

            if (!empty($attribut_filter['modification'])) {
                $modification = $this->filterConfig['modification'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($modification);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['modification']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['modification']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['modification']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $modification);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['modification']
                        )
                    );


                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut_filter['body_type'])) {
                $body_type = $this->filterConfig['body_type'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($body_type);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['body_type']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['body_type']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['body_type']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $body_type);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['body_type']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut_filter['fuel'])) {
                $fuel = $this->filterConfig['fuel'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($fuel);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['fuel']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['fuel']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['fuel']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $fuel);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['fuel']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut_filter['dvs'])) {
                $dvs = $this->filterConfig['dvs'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($dvs);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['dvs']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['dvs']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['dvs']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $dvs);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['dvs']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut_filter['kpp'])) {
                $kpp = $this->filterConfig['kpp'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($kpp);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['kpp']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['kpp']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['kpp']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $kpp);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['kpp']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }


            if (!empty($attribut_filter['engine_type'])) {
                $engine_type = $this->filterConfig['engine_type'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($engine_type);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['engine_type']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['engine_type']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['engine_type']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $engine_type);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['engine_type']
                        )
                    );
                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }
            }


            if (!empty($attribut_filter['volume'])) {
                $volume = $this->filterConfig['volume'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($volume);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['volume']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['volume']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['volume']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $volume);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['volume']
                        )
                    );
                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }
            }

            if (!empty($attribut_filter['restailing'])) {
                $restailing = $this->filterConfig['restailing'];

                $filter_id = $this->model_extension_module_import->getFilterGroup($restailing);

                $filterData = array();

                $filterData['sort_order'] = "";
                $filterData['filter_description'] = array(
                    (int)$this->config->get('config_language_id') => array(
                        'name' => $attribut_filter['restailing']
                    )
                );

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['restailing']);

                if (empty($filter_value_id)) {
                    $filter_value_id = $this->model_extension_module_import->addFilterValue($filterData, $filter_id['filter_group_id']);
                }

                $filter_value_id = $this->model_extension_module_import->getFilterId($filter_id['filter_group_id'], $attribut_filter['restailing']);

                $this->model_extension_module_import->addFilterProduct($product['product_id'], $filter_value_id['filter_id']);

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $restailing);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut_filter['restailing']
                        )
                    );


                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            $attributes = $product['attributes'];

            $attribut = array(
                'id_parent' => $attributes['id_parent'],
                'id_car' => $attributes['id_car'],
                'leyba' => $attributes['leyba'],
                'discount' => $attributes['discount'],
                'engine_number' => $attributes['engine_number'],
                'race' => $attributes['race'],
                'doors' => $attributes['doors'],
                'hp' => $attributes['hp']
            );


            if (!empty($attribut['id_car'])) {
                $id_car = $this->atributeConfig['id_car'];

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $id_car);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut['id_car']
                        )
                    );


                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut['leyba'])) {
                $leyba = $this->atributeConfig['leyba'];

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $leyba);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut['leyba']
                        )
                    );


                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut['race'])) {
                $race = $this->atributeConfig['race'];

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $race);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut['race']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut['engine_number'])) {
                $engine_number = $this->atributeConfig['engine_number'];

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $engine_number);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut['engine_number']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }


            if (!empty($attribut['doors'])) {
                $doors = $this->atributeConfig['doors'];

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $doors);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut['doors']
                        )
                    );


                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if (!empty($attribut['hp'])) {
                $hp = $this->atributeConfig['hp'];

                $attribut_id = $this->model_extension_module_import->getAttribute($atribute_group_id['attribute_group_id'], $hp);
                if (!empty($attribut_id)) {
                    $attributData = array();
                    $attributData['product_id'] = $product['product_id'];
                    $attributData['attribute_id'] = $attribut_id['attribute_id'];
                    $attributData['product_attribute_description'] = array(
                        (int)$this->config->get('config_language_id') => array(
                            'name' => $attribut['hp']
                        )
                    );

                    $this->model_extension_module_import->addAtributeProduct($attributData);
                }

            }

            if ((!empty($product['images'])) && $product['images'][0] !== '') {

                $i = 0;
                $images = [];
                foreach ($product['images'] as $image) {
                    $url = strtok($image, '?');
                    $this->uploadImage('product', $url);
                    $value = 'catalog/product/' . basename($product['images'][$i], "?nowatermark");
                    array_push($images, $value);
                    $i++;
                }

                $this->model_extension_module_import->addProductImage($product['product_id'], $images);
            }
        }
    }

    public function updateCurrencyPrice()
    {
        $this->load->model('extension/module/import');

        $prices = self::apiConnect('getCurrency');
        $rur = $prices['RUR'] / $prices['BYN'];

        $result = $this->model_extension_module_import->updateCurrencyPrice($rur);
    }

    public function siteMapGeneration() {

        $this->load->model('extension/module/import');

        $xmlFiles         = glob('*.xml');
        $siteMapGoods     = 'sitemap-goods-';
        $siteMapCatalog   = 'sitemap-catalog.xml';
        $siteMapZapchasti = 'sitemap-zapchasti.xml';

        if (!empty($xmlFiles)) {
            foreach ($xmlFiles as $xmlFile) {
                unlink($xmlFile);
            }
        }

        if (!file_exists($siteMapCatalog)) {
            $categories = $this->model_extension_module_import->xmlGetCategories();
            $content = '';
            foreach ($categories as $category) {
                $content .= "\t<url>\r\n";
                $content .= "\t\t<loc>" . $category['url'] . '/' . "</loc>\r\n";
                $content .= "\t</url>\r\n";
            }
            $this->writeToXML($siteMapCatalog, $content);
        }

        if (!file_exists($siteMapGoods)) {
            $goods = $this->model_extension_module_import->xmlGetGoods();

            foreach ($goods as $key => $products) {
                $content = '';
                foreach ($products as $product) {
                    $content .= "\t<url>\r\n";
                    $content .= "\t\t<loc>" . $this->config->get('site_base') . 'goods/' . $product . '/' . "</loc>\r\n";
                    $content .= "\t</url>\r\n";
                }
                $key++;
                $this->writeToXML($siteMapGoods, $content, $key);
            }
        }

//        if (!file_exists($siteMapZapchasti)) {
//            $categories = $this->model_extension_module_import->xmlGetCategories();
//            $content = '';
//            foreach ($categories as $category) {
//                $content .= "\t<url>\r\n";
//                $content .= "\t\t<loc>" . $category['url'] . "</loc>\r\n";
//                $content .= "\t</url>\r\n";
//            }
//            $this->writeToXML($siteMapCatalog, $content);
//        }

        $this->generateMainSiteMap($siteMapCatalog, $siteMapZapchasti, $siteMapGoods);
    }

    public function generateMainSiteMap($catalog, $zapchasti, $goods)
    {
        $siteBase   = $this->config->get('site_base');
        $xmlHeader  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
        $xmlHeader .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
        $xmlFooter  = "</sitemapindex>\r\n";

        $content    = '';
        $content   .= "\t<sitemap>\r\n";
        $content   .= "\t\t<loc>" . $this->config->get('site_base') . $catalog . "</loc>\r\n";
        $content   .= "\t</sitemap>\r\n";
//        $content   .= "\t<sitemap>\r\n";
//        $content   .= "\t\t<loc>" . $zapchasti . "</loc>\r\n";
//        $content   .= "\t<sitemap>\r\n";
        $content   .= "\t<sitemap>\r\n";
        $content   .= "\t\t<loc>" . $this->config->get('site_base') .'sitemap-goods-1.xml' . "</loc>\r\n";
        $content   .= "\t</sitemap>\r\n";

        $file = fopen('sitemap.xml', 'w');
        fwrite($file, $xmlHeader . $content . $xmlFooter);
        fclose($file);
    }

    protected function writeToXML($file, $content, $count = null) {

        $siteBase   = $this->config->get('site_base');
        $xmlHeader  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
        $xmlHeader .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
        $xmlFooter  = "</sitemapindex>\r\n";

        ($file == 'sitemap-goods-') ? $file = $file . $count . '.xml' : $file;
        $file = fopen($file, 'w');
        fwrite($file, $xmlHeader . $content . $xmlFooter);
        fclose($file);
    }

    public function elasticGenerateCache()
    {

        ini_set('memory_limit', '512M');

        $url = HTTP_SERVER_ELASTIC . '_all';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        print_r($result);
        $url_sitemap = $this->load->controller('extension/feed/google_sitemap');
        require_once('simple_html_dom.php');
        $content_product = file_get_contents($url_sitemap);
        $site_map_xml = new SimpleXMLElement($content_product);
        foreach ($site_map_xml->url as $loc) {
            $loc_tags[] = $loc;
        }
        $count = 0;
        foreach ($loc_tags as $xml_file) {

            print_r($count);


            if (is_int(strpos($xml_file->loc, 'category'))) {
                $content_each_xml_file = file_get_html($xml_file->loc);
                if (!is_object($content_each_xml_file)) {
                    file_put_contents('test_log.txt',$xml_file->loc . "\n");
                } else {
                    $h1 = $content_each_xml_file->find('h1[id=xml_search_category_name]', 0)->plaintext;
                    $alias = $content_each_xml_file->find('div[id=xml_search_category_alias]', 0)->plaintext;
                    if (!empty($h1)) {

                        $href = HTTP_SERVER . "catalog" . $alias;

                        $params = [
                            'title' => (string)$h1,
                            'id' => '0',
                            'href' => $href,
                            'tag' => "category",
                        ];
                        $data_string = json_encode($params);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, HTTP_SERVER_ELASTIC . "elastic/feed");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        if (empty($response)) {
                            echo 'fail generate cache' . PHP_EOL;
                            die;
                        } else {
                            print_r($response);
                        }
                    }
                }
            } else if (is_int(strpos($xml_file->loc, 'product_id')) && (strpos($xml_file->loc, 'path') === FALSE)) {
                $content_each_xml_file = file_get_html($xml_file->loc);
                if (!is_object($content_each_xml_file)) {
                    file_put_contents('test_log.txt',$xml_file->loc . "\n");
                } else {
                    try{
                        $h1 = $content_each_xml_file->find('h1[id=xml_search_product_name]', 0)->plaintext;
                        $id = $content_each_xml_file->find('div[id=xml_search_product_id]', 0)->plaintext;
                        $alias = $content_each_xml_file->find('div[id=xml_search_product_alias]', 0)->plaintext;
                        //Если fopen возвращает логическое значение FALSE, то возникает ошибка.
                        if($h1 === false || $id === false){
                            throw new Exception('Невозможно открыть');
                        }
                    }
                    catch (Exception $ex) {
                        file_put_contents('test_log.txt',$xml_file->loc . "\n");
                    }
                    if (!empty($h1)) {


                        $href = HTTP_SERVER . "goods/" . $alias;



                        $params = [
                            'title' => (string)$h1,
                            'id' => (string)$id,
                            'href' => $href,
                            'tag' => "product",
                        ];
                        $data_string = json_encode($params);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, HTTP_SERVER_ELASTIC . "elastic/feed");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        if (empty($response)) {
                            echo 'fail generate cache' . PHP_EOL;
                            die;
                        } else {
                            print_r($response);
                        }
                    }
                }
            }
            $count++;
        }
    }

    public function test()
    {
        require_once('simple_html_dom.php');
        $content_product = file_get_contents("http://motorminsk.loc/custom_sitemap_chpu.xml");
        $site_map_xml = new SimpleXMLElement($content_product);
        foreach ($site_map_xml->url as $loc) {
            $loc_tags[] = $loc;
        }
        foreach ($loc_tags as $xml_file) {
            if (is_int(strpos($xml_file->loc, 'category'))) {
                $content_each_xml_file = file_get_html($xml_file->loc);
                if (!is_object($content_each_xml_file)) {
                    file_put_contents('test_log.txt',$xml_file->loc . "\n");
                } else {
                    try{
                        $h1 = $content_each_xml_file->find('h1[id=xml_search_category_name]', 0)->plaintext;
                        $alias = $content_each_xml_file->find('div[id=xml_search_category_alias]', 0)->plaintext;
                        //Если fopen возвращает логическое значение FALSE, то возникает ошибка.
                    }
                    catch (Exception $ex) {
                        file_put_contents('test_log.txt',$xml_file->loc . "\n");
                    }
                    if (!empty($h1)) {

                        $href = HTTP_SERVER . "catalog" . $alias;

                        $params = [
                            'title' => (string)$h1,
                            'id' => 0,
                            'href' => $href,
                            'tag' => "category",
                        ];
echo '<pre>';
print_r($params);
echo '</pre>';
die;
                        $data_string = json_encode($params);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, HTTP_SERVER_ELASTIC . "elastic/feed");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        var_dump($response);
                        die();
                    }
                }
            }
        }
    }

}
