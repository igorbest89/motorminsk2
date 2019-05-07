<?php

class ModelExtensionModuleImport extends Model
{
    /**
     * Insert data from API.
     *
     * @param $data
     */
    public function createOrUpdateCategory($data)
    {
        $category = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "category WHERE category_id = '" . $data['id'] . "'");

        if (!empty($category->row)) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = " . $data['id']);
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = " . $data['id']);
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . $data['id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = " . $data['id'] . "");
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = " . $data['id'] . "");
            $this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = " . $data['id'] . "");
        }

        $this->db->query("
            INSERT INTO " . DB_PREFIX . "category
            SET
                `category_id`   = '" . $data['id'] . "',
                `image`         = '" . $data['img'] . "',
                `parent_id`     = '" . $data['parent_id'] . "',
                `top`           = '" . $data['top'] . "',
                `column`        = '" . $data['column'] . "',
                `sort_order`    = '" . $data['sort_order'] . "',
                `status`        = '" . $data['status'] . "',
                `date_modified` = NOW(),
                `date_added`    = NOW()
        ");
        $this->db->query("
            INSERT " . DB_PREFIX . "category_description
            SET
                `category_id`      = '" . $data['id'] . "',
                `language_id`      = '" . $data['language_id'] . "',
                `name`             = '" . $this->db->escape($data['title']) . "',
                `description`      = '" . $this->db->escape($data['description']) . "',
                `meta_title`       = '" . $data['meta_title'] . "',
                `meta_description` = '" . $data['meta_description'] . "',
                `meta_keyword`     = '" . $data['meta_keyword'] . "'
        ");

        $this->db->query("
            INSERT " . DB_PREFIX . "url_alias
            SET `query`   = 'category_id=" . $data['id'] . "', `keyword` = '" . $data['seo_url'] . "'
        ");
        if ($data['parent_path_id'] == '0') {
            $this->db->query("
                INSERT " . DB_PREFIX . "category_path
                SET `category_id` = '" . $data['id'] . "', `path_id` = '" . $data['id'] . "', `level`       = '0'
            ");
        } else {
            $this->db->query("
                INSERT " . DB_PREFIX . "category_path
                SET `category_id` = '" . $data['id'] . "', `path_id` = '" . $data['parent_path_id'] . "', `level`       = '0'
            ");
            $this->db->query("
                INSERT " . DB_PREFIX . "category_path
                SET `category_id` = '" . $data['id'] . "', `path_id` = '" . $data['id'] . "', `level` = '1'
            ");
        }

        $this->db->query("
            INSERT " . DB_PREFIX . "category_to_layout
            SET `category_id` = '" . $data['id'] . "', `store_id` = '0', `layout_id` = '0'
        ");
        $this->db->query("
            INSERT " . DB_PREFIX . "category_to_store
            SET `category_id` = '" . $data['id'] . "', `store_id` = '0'
        ");
    }

    /**
     * Select all categories from DB.
     *
     * @return mixed
     */
    public function getAllCategories()
    {
        $categories = $this->db->query("SELECT c.category_id, c.parent_id, c.status FROM " . DB_PREFIX . "category c")->rows;
        return $categories;
    }

    public function updateCurrencyPrice($rur)
    {
        $result = $this->db->query("
            UPDATE oc_currency
            SET value = " . $rur . " 
            WHERE code = 'RUR'
        ");

        return ($result == TRUE) ? TRUE : FALSE;
    }

    public function getCategoriesIds($categoriesIds) {
        if (!empty($categoriesIds)) {
            $catIds = implode(', ', $categoriesIds);
            $sql = "
                SELECT category_id
                FROM " . DB_PREFIX . "category
                WHERE category_id IN (" . $catIds . ")
            ";
            $issetCategories = $this->db->query($sql)->rows;
            $issetCategories = array_column($issetCategories, 'category_id');

            return $issetCategories;
        }
    }

    /**
     * Select all filters from DB.
     *
     * @return mixed
     */
    public function getAllFilters()
    {
        $filters = $this->db->query("SELECT fd.filter_id, fd.name, fd.url_alias_id FROM " . DB_PREFIX . "filter_description fd")->rows;
        return $filters;
    }

    /**
     * Relation allFilters to allCategories.
     *
     * @param $categoryId
     */
    public function filtersToCategories($categoryId)
    {
        $filters = self::getAllFilters();
        foreach ($filters as $filter) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_filter (category_id, filter_id) VALUES (" . $categoryId . ", " . $filter['filter_id'] . ")");
        }
    }

    /**
     * Truncate table category_filter in Database.
     *
     * @return mixed
     */
    public function clearDatabase($dbName)
    {
        return $this->db->query("TRUNCATE TABLE " . DB_PREFIX . $dbName);
    }

    /**
     * Creating or Updating all filters SEO URL.
     *
     * @param null $filter
     * @return mixed
     */
    public function createOrUpdateFiltersUrl($filter = NULL)
    {
        if ($filter == NULL) {
            $filters = self::getAllFilters();
            return $filters;
        } else {
            if (!empty($filter['url_alias_id'])) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE `query` = 'filter=" . $filter['id'] . "'");
            }
            $this->db->query("INSERT " . DB_PREFIX . "url_alias SET `query` = 'filter=" . $filter['id'] . "', keyword = '" . $this->db->escape($filter['title']) . "'");
            $lastId = $this->db->getLastId();
            $this->db->query("UPDATE " . DB_PREFIX . "filter_description SET url_alias_id = '" . $lastId . "' WHERE filter_id = '" . $filter['id'] . "'");
        }
    }

    /**
     * GET all products for filter_id.
     *
     * @param $filterId
     * @return mixed
     */
    public function getProductOfFilter($filterId)
    {
        return $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_filter WHERE filter_id = '" . $filterId . "'")->rows;
    }

    /**
     * Get child filter.
     *
     * @param $products
     * @return mixed
     */
    public function getChildFilter($products, $parentName)
    {
        return $this->db->query("SELECT fd.name, fd.filter_id FROM " . DB_PREFIX . "filter_description fd, " . DB_PREFIX . "filter_group_description fgd, " . DB_PREFIX . "product_filter pf WHERE pf.product_id IN (" . $products . ") AND fgd.name = '" . $parentName . "' AND fgd.filter_group_id = fd.filter_group_id AND pf.filter_id = fd.filter_id GROUP BY fd.name")->rows;
    }

    /**
     * Set filter_id and parent_filter_id in DB.
     *
     * @param $filterId
     * @param $childFilterId
     * @return mixed
     */
    public function setParentFilter($filterId, $childFilterId)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "filter_parent (filter_id, child_filter_id) VALUES (" . $filterId . ", " . $childFilterId . ")");
        return $childFilterId;
    }

    /**
     * Isnset manufactures to Database.
     *
     * @param $manufacturer
     */
    public function addManufacturer($manufacturer)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "', name = '" . $this->db->escape($manufacturer['name']) . "', sort_order = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "', store_id = '0'");
        $this->cache->delete('manufacturer');
    }

    /**
     * Update manufactures in Database.
     *
     * @param $manufacturer_id
     * @param $data
     */
    public function editManufacturer($manufacturer_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '0' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '0'");
        $this->cache->delete('manufacturer');
    }

    /**
     * Insert manufacture image url in Database.
     *
     * @param $manufacturer_id
     * @param $image
     */
    public function addManufacturerImage($manufacturer_id, $image)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($image) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
    }

    /**
     * Select manufacture from database.
     *
     * @param $manufacturer_name
     * @return mixed
     */
    public function getManufacturer($manufacturer_name)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "manufacturer WHERE name='" . $manufacturer_name . "'";
        $query = $this->db->query($sql);

        return $query->row;
    }

    /**
     * Select Attribute ID from Database.
     *
     * @param $name
     * @return mixed
     */
    public function getAtributeId($name)
    {
        $sql = "SELECT attribute_id FROM " . DB_PREFIX . "oc_attribute_description WHERE language_id = " . (int)$this->config->get('config_language_id') . " AND name='" . $name . "'";
        $qwery = $this->db->qwery($sql);

        return $qwery->row['attribute_id'];
    }

    /**
     * Insert image for category in Database.
     *
     * @param $category_id
     * @param $image
     */
    public function addCategoryImage($category_id, $image)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($image) . "' WHERE category_id = '" . (int)$category_id . "'");
    }

    /**
     * Select product_ean from Database.
     *
     * @param $ean
     * @return mixed
     */
    public function getProduct($ean)
    {
        $query = $this->db->query("SELECT product_id AS product_id, ean AS ean FROM " . DB_PREFIX . "product WHERE ean = '" . (int)$ean . "'");

        return $query->row;
    }

    /**
     * Insert product in Database.
     *
     * @param $data
     */
    public function addProduct($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) .
            "', sku = '', upc = '', ean = '" . $this->db->escape($data['ean']) .
            "', jan = '" . $data['jan'] . "', isbn = '', mpn = '', location = '', quantity = '" . (int)$data['count'] .
            "', minimum = '1', subtract = '0', stock_status_id = '7', date_available = 'NOW()', manufacturer_id = '" . (int)$data['manufacturer_id'] .
            "', shipping = '1', price = '" . (float)$data['price'] .
            "', points = '0', weight = '0.00000000', weight_class_id = '1', length = '0.00000000', width = '0.00000000', height = '0.00000000', length_class_id = '1', status = '" . (int)$data['status'] . "', tax_class_id = '9', sort_order = '0', date_added = NOW()");
        $product_id = $this->db->getLastId();
        foreach ($data['product_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '', meta_keyword = ''");
        }
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
        if (isset($data['categories'])) {
            foreach ($data['categories'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id .
                    "', category_id = '" . (int)$category_id . "'");
            }
        }
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '0', layout_id = '0'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = 'd" . (int)$product_id . "-" . $data['url_name'] . "'");

        $sql = "DELETE FROM ".DB_PREFIX."product_attribute WHERE product_id='".$product_id."'";
        $this->db->query($sql);

        foreach ($data['attributes_filter'] as $key=>$value)
        {
            $sql = "INSERT INTO ".DB_PREFIX."product_attribute SET product_id='".$product_id.
                "' , attribute_id='".$key.
                "' , language_id='".(int)$this->config->get('config_language_id') .
                "' , text='".$this->db->escape($value)."'";

            $this->db->query($sql);
        }

        foreach ($data['product_filters'] as $filterProduct) {

                foreach ($filterProduct as $filter) {
                    $sql = "INSERT INTO " . DB_PREFIX . "product_filter SET product_id='" . $product_id . "', filter_id='".$filter."'";
                    $this->db->query($sql);
                }
        }
        $this->cache->delete('product');
        return $product_id;
    }

    /**
     * Insert product_image in Database.
     *
     * @param $product_id
     * @param $images
     */
    public function addProductImage($product_id, $images)
    {

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

        $k = 0;
        foreach ($images as $image) {
            if ($k == 0) {
                $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($image) . "' WHERE product_id = '" . (int)$product_id . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image) . "', sort_order = '0'");
            }
            $k++;
        }
    }

    /**
     * Update product in Database.
     *
     * @param $product_id
     * @param $data
     */
    public function editProduct($product_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET  price = '" . (float)$data['price'] . "' WHERE product_id = '" . (int)$product_id . "'");
        $this->cache->delete('product');
    }

    /**
     * Update attributes for products in Database.
     *
     * @param $product_id
     * @param $data
     */
    public function editAtributeProduct($product_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET sku = '" . $this->db->escape($data['sku']) . "' WHERE product_id = '" . (int)$product_id . "'");
        if (isset($data['model']) && $data['model'] != '') {
            $query = $this->db->query("SELECT f.filter_id AS filter_id FROM " . DB_PREFIX . "filter f, " . DB_PREFIX . "filter_description fd WHERE fd.name = '" . $this->db->escape($data['model']) . "' AND f.filter_group_id = '2' AND f.filter_group_id=fd.filter_group_id AND fd.filter_id=f.filter_id");
            if (isset($query->row['filter_id'])) {
                $filter_id = $query->row['filter_id'];
            } else {
                $query = $this->db->query("INSERT INTO " . DB_PREFIX . "filter  SET  filter_group_id = '2', sort_order = '0'");
                $filter_id = $this->db->getLastId();
                $query = $this->db->query("INSERT INTO " . DB_PREFIX . "filter_description  SET  filter_id='" . (int)$filter_id . "', language_id ='1', filter_group_id = '2', name = '" . $this->db->escape($data['model']) . "'");
            }
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "' AND filter_id = '" . (int)$filter_id . "'");
            $query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter  SET  product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
        }
    }

    /**
     * Insert filters for product in Database.
     *
     * @param $product_id
     * @param $filter_id
     */
    public function addFilterProduct($product_id, $filter_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "' AND filter_id = '" . (int)$filter_id . "'");
        $query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter  SET  product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
    }

    /**
     * Add attribute for product in Databse.
     *
     * @param $product_attribute
     */
    public function addAtributeProduct($product_attribute)
    {
        foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_attribute['product_id'] . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape($product_attribute_description['name']) . "'");
        }
    }

    public function addAtributeToProduct($productId, $data)
    {
        /*
        foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_attribute['product_id'] . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape($product_attribute_description['name']) . "'");
        }*/
    }

    /**
     * Select filter_group_id from filter_group_description.
     *
     * @param $filter_name
     * @return mixed
     */
    public function getFilterGroup($filter_name)
    {
        $sql = "SELECT filter_group_id FROM " . DB_PREFIX . "filter_group_description WHERE language_id='" . (int)$this->config->get('config_language_id') . "' AND name='" . $filter_name . "'";
        $qwery = $this->db->query($sql);
        if($qwery->num_rows>0) {
            return $qwery->row;
        }else{
            return -1;
        }
    }

    /**
     * Insert filter_group in Database.
     *
     * @param $data
     * @return mixed
     */
    public function addFilterGroup($data)
    {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort'] . "'");
            $filter_group_id = $this->db->getLastId();
            foreach ($data['filter_group_description'] as $language_id => $value) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE name='" . $this->db->escape($value['name']) . "'");

                if ($query->num_rows > 0) {
                    $this->db->query("DELETE FROM " . DB_PREFIX . "filter_group WHERE filter_group_id='".$filter_group_id."'");
                    return $query->row['filter_group_id'];
                }else{
                    $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
                }

            }

        return $filter_group_id;
    }

    /**
     * Select filter_id from filter_description.
     *
     * @param $filter_group_id
     * @param $filter_name
     * @return mixed
     */
    public function getFilterId($filter_group_id, $filter_name)
    {
        $sql = "SELECT filter_id FROM " . DB_PREFIX . "filter_description WHERE language_id='" . (int)$this->config->get('config_language_id') . "' AND filter_group_id='" . (int)$filter_group_id . "' AND name='" . $filter_name . "'";

        $qwery = $this->db->query($sql);
        return $qwery->row;
    }

    /**
     * Insert filters_value in Database.
     *
     * @param $data
     * @param $filter_group_id
     * @return mixed
     */
    public function addFilterValue($data, $filter_group_id)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$data['sort_order'] . "'";

        $this->db->query($sql);
        $filter_value_id = $this->db->getLastId();
        foreach ($data['filter_description'] as $language_id => $filter_description) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_value_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
        }

        return $filter_value_id;
    }



    public function getCheackFilterValue($filterGroupId,$filterValue,$filterList = [])
    {

        $sql = "SELECT filter_id FROM ".DB_PREFIX."filter_description WHERE language_id='".(int)$this->config->get('config_language_id')."' AND filter_group_id='".$filterGroupId."' AND name='".$filterValue."'";
        $query = $this->db->query($sql);
        if($query->num_rows>0)
        {
            return $query->row;
        }else{
            return -1;
        }

    }



    /**
     * Insert filters in Database.
     *
     * @param $data
     * @return mixed
     */
    public function addFilter($data)
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "'");
        $filter_group_id = $this->db->getLastId();
        foreach ($data['filter_group_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }
        if (isset($data['filter'])) {
            foreach ($data['filter'] as $filter) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "'");
                $filter_id = $this->db->getLastId();
                foreach ($filter['filter_description'] as $language_id => $filter_description) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
                }
            }
        }

        return $filter_group_id;
    }

    /**
     * Update filters in Database.
     *
     * @param $filter_group_id
     * @param $data
     */
    public function editFilter($filter_group_id, $data)
    {
        $this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "' WHERE filter_group_id = '" . (int)$filter_group_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");
        foreach ($data['filter_group_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }
        $this->db->query("DELETE FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");
        if (isset($data['filter'])) {
            foreach ($data['filter'] as $filter) {
                if ($filter['filter_id']) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_id = '" . (int)$filter['filter_id'] . "', filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
                }
                $filter_id = $this->db->getLastId();
                foreach ($filter['filter_description'] as $language_id => $filter_description) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
                }
            }
        }
    }

    /**
     * Select `attribute_group_id` from `attribute_group_description`.
     *
     * @param $atributeGroup
     * @return mixed
     */
    public function getAtributeGroup($atributeGroup)
    {
        $query = $this->db->query("SELECT attribute_group_id AS attribute_group_id FROM " . DB_PREFIX . "attribute_group_description WHERE name = '" . $this->db->escape($atributeGroup) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
        return $query->row;
    }

    public function addFilterValueToGroup($filterGroupId, $filterValue, $filterParentId = 0)
    {
        $sql = "SELECT * FROM ".DB_PREFIX."filter_description WHERE filter_group_id='".$filterGroupId."' AND name='".$this->db->escape($filterValue)."' AND filter_id IN (SELECT filter_id FROM ".DB_PREFIX."filter_parent WHERE filter_parent_id='".$filterParentId."')";
        $query = $this->db->query($sql);

        if($query->num_rows==0) {
            $sql = "INSERT INTO " . DB_PREFIX . "filter SET filter_group_id='" . $filterGroupId . "', sort_order='0'";
            $query = $this->db->query($sql);
            $filter_id = $this->db->getLastId();
            $sql = "INSERT INTO " . DB_PREFIX . "filter_description SET filter_id='" . $filter_id . "', language_id = '" . (int)$this->config->get('config_language_id') .
                "', filter_group_id='" . $filterGroupId . "', name='" . $this->db->escape($filterValue) . "' ";
            $query = $this->db->query($sql);

            $sql = "INSERT INTO " . DB_PREFIX . "filter_parent SET filter_id='" . $filter_id . "' ,filter_parent_id='" . $filterParentId . "'";
            $this->db->query($sql);
            return $filter_id;
        }else{
            return $query->row['filter_id'];
        }
    }


    /**
     * Insert `attribute_group_description` in Database.
     *
     * @param $data
     * @return mixed
     */
    public function addAtributeGroup($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET sort_order = '" . (int)$data['sort_order'] . "'");
        $attribute_group_id = $this->db->getLastId();
        foreach ($data['attribute_group_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }

        return $attribute_group_id;
    }

    /**
     * Insert into `attribute_description` in Database.
     *
     * @param $atribute_group_id
     * @param $data
     * @return mixed
     */
    public function addAttribute($atribute_group_id, $data)
    {

        $this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$atribute_group_id . "', sort_order = '0'");
        $attribute_id = $this->db->getLastId();
        foreach ($data['attribute_description'] as $language_id => $value) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE name='" . $this->db->escape($value['name']) . "'");
            if ($query->num_rows > 0) {
                $this->db->query('DELETE FROM ' . DB_PREFIX . 'attribute WHERE attribute_id = ' . (int)$attribute_id);
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
            }

        }

        return $attribute_id;
    }

    /**
     * Select * from `attribute` and `attribute_description`.
     *
     * @param $atribute_group_id
     * @param $attribute_name
     * @return mixed
     */
    public function getAttribute($attribute_name)
    {
        $query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "attribute_description WHERE name='" . $this->db->escape($attribute_name) . "'");
        /* $query = $this->db->query("SELECT a.attribute_id AS attribute_id FROM " . DB_PREFIX . "attribute a LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE ad.name = '" . $attribute_name . "' AND a.attribute_group_id = '" . (int)$atribute_group_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");
 */
        return $query->row;
    }

    public function updateProductParent($productId = NULL, $compl = NULL)
    {
        if (!is_null($compl)) {
            if (isset($compl['childNodes'])) {
                self::updateProductParent($productId, $compl['childNodes']);
            }

            foreach ($compl as $data) {
                if (isset($data['id'])) {
                    $result = $this->db->query("
                    SELECT *
                    FROM " . DB_PREFIX . "product_parent pp
                    WHERE pp.product_id = '" . $productId . "' AND pp.product_parent_id = '" . $data['id'] . "'
                ")->row;

                    if (!empty($result)) {
                        $this->db->query("
                        DELETE FROM " . DB_PREFIX . "product_parent WHERE product_id = '" . $productId . "' AND product_parent_id = '" . $data['id'] . "';
                    ");
                    }

                    if (!is_null($data['id']) && $data['id'] != 0) {
                        $this->db->query("
                    INSERT INTO " . DB_PREFIX . "product_parent (product_id, product_parent_id) VALUES ('" . $productId . "', '" . $data['id'] . "');
                ");
                    }
                }
            }
        }
    }

    public function setCacheExtendedFiltersCount()
    {
        $countProducts = $this->cache->get('extendedFilterSearch.allFiltersCount');

        if ($countProducts == false) {
            $countProducts = $this->db->query("
                SELECT f.filter_id, f.filter_group_id, fd.name,
                       (SELECT count(product_id) FROM " . DB_PREFIX . "product_filter WHERE filter_id = f.filter_id) AS count_product
                FROM " . DB_PREFIX . "filter f
                LEFT JOIN " . DB_PREFIX . "filter_description fd ON f.filter_id = fd.filter_id
            ")->rows;

            $this->cache->set('extendedFilterSearch.allFiltersCount', $countProducts);
        } else {
            $this->cache->delete('extendedFilterSearch.allFiltersCount');

            $countProducts = $this->db->query("
                SELECT f.filter_id, f.filter_group_id, fd.name,
                       (SELECT count(product_id) FROM " . DB_PREFIX . "product_filter WHERE filter_id = f.filter_id) AS count_product
                FROM " . DB_PREFIX . "filter f
                LEFT JOIN " . DB_PREFIX . "filter_description fd ON f.filter_id = fd.filter_id
            ")->rows;

            $this->cache->set('extendedFilterSearch.allFiltersCount', $countProducts);
        }
    }

    /**
     * Get categories for site map.
     *
     * @return array
     */
    public function xmlGetCategories()
    {
        $baseUrl = $this->config->get('site_base') . 'catalog/';

        $result = $this->db->query("
            SELECT cd.category_id, cd.name, ua.keyword, c.parent_id
            FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id
            LEFT JOIN " . DB_PREFIX . "url_alias ua ON CONCAT('category_id=', cd.category_id) = ua.query
        ")->rows;

        $categories = [];
        foreach ($result as $cat) {
            $categories[] = [
                'name' => $cat['name'],
                'url'  => ((int)$cat['parent_id'] == 0) ? $baseUrl . $cat['keyword'] : $baseUrl . $this->xmlGetChildCategories((int)$cat['parent_id'], $cat['keyword'])
            ];
        }

        return $categories;
    }

    /**
     * Get child categories for site map.
     *
     * @param $catId
     * @param null $url
     * @return string|null
     */
    public function xmlGetChildCategories($catId, $url = null)
    {
        if ($catId != 0) {

            $child = $this->db->query("
                SELECT cd.category_id, cd.name, ua.keyword, c.parent_id
                FROM " . DB_PREFIX . "category c
                LEFT JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id
                LEFT JOIN " . DB_PREFIX . "url_alias ua ON CONCAT('category_id=', cd.category_id) = ua.query
                WHERE c.category_id = " . (int)$catId . "
            ")->row;
            $url = $child['keyword'] . '/' . $url;

            if ($child['parent_id'] == 0) {
                return $url;
            } else {
                return $this->xmlGetChildCategories($child['parent_id'], $url);
            }
        }
    }

    public function xmlGetGoods()
    {
        $goods = $this->db->query("
            SELECT ua.keyword
            FROM oc_product p
            LEFT JOIN oc_url_alias ua ON concat('product_id=', p.product_id) = ua.query
            WHERE p.price > 0
        ")->rows;

        $goods = array_chunk(array_column($goods, 'keyword'), 50000);

        return $goods;
    }











}