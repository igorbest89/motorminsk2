<?php

class ModelExtensionModuleFiltersearch extends Model
{
    /**
     * Array of configurations for sampling from the database.
     *
     * @var array
     */
    private $configSearch = [
        'mark' => 'Марка',
        'models' => 'Модель',
        'generation' => 'Модификация'
    ];

    /**
     * Get all marks for filters.
     *
     * @return mixed
     */
    public function getAllMarks()
    {

        $sql = "
            SELECT f.filter_id, fd.name, ua.keyword, (
                SELECT count(pf.product_id) FROM oc_product_filter pf WHERE f.filter_id = pf.filter_id
            ) AS count_product
            FROM " . DB_PREFIX . "filter_group_description fgd
            LEFT JOIN " . DB_PREFIX . "filter f ON fgd.filter_group_id = f.filter_group_id
            LEFT JOIN " . DB_PREFIX . "filter_description fd ON f.filter_id = fd.filter_id
            lEFT JOIN " . DB_PREFIX . "url_alias ua ON fd.url_alias_id = ua.url_alias_id
            WHERE fgd.name = '" . $this->configSearch['mark'] . "' ORDER BY fd.name
        ";
        $marks = $this->db->query($sql)->rows;
        return $marks;
    }

    /**
     * Get filters for filter input.
     *
     * @param $filterId
     * @return mixed
     */
    public function getFilters($filterId)
    {
        $sql = "
            SELECT fd.name, fd.filter_id, ua.keyword, ua.query, f.filter_group_id,
               (
                   SELECT count(pf.product_id) FROM oc_product_filter pf
                   LEFT JOIN oc_product p ON pf.product_id = p.product_id WHERE filter_id = fp.filter_id AND p.price > 0 AND p.status = 1
               ) AS count_product
            FROM oc_filter_parent fp
                   LEFT JOIN " . DB_PREFIX . "filter f ON fp.filter_id = f.filter_id
                   LEFT JOIN " . DB_PREFIX . "filter_description fd ON fp.filter_id = fd.filter_id
                   LEFT JOIN " . DB_PREFIX . "url_alias ua ON fd.url_alias_id = ua.url_alias_id
            WHERE fp.filter_parent_id IN ('" . $filterId . "') ORDER BY fd.name
        ";
        $filters = $this->db->query($sql)->rows;
        return $filters;
    }

    public function getFiltersData($filterId){
        $temp = explode(',', $filterId);
        $temp_array = [];
        foreach ($temp as $id){
            $sql = "SELECT fd.filter_id,fd.name, fd.filter_group_id, fgd.name as group_name FROM " . DB_PREFIX . "filter_description fd
            LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON fd.filter_group_id = fgd.filter_group_id
            WHERE filter_id='" . $id . "'";
            $temp_array[] = $this->db->query($sql)->row;
        }
        return $temp_array;
    }
    /**
     *
     *
     * @param $filtersId
     * @return mixed
     */
    public function getSecondFilters($marksId)
    {
        $models = $this->getStrFilters($marksId);
        $generations = $this->getStrFilters($models);

        $filters = $this->db->query("
            SELECT fd.filter_id, fd.filter_group_id, fd.name,
                (SELECT count(product_id) FROM " . DB_PREFIX . "product_filter WHERE filter_id = fd.filter_id) AS product_count
            FROM " . DB_PREFIX . "filter_parent fp
            LEFT JOIN " . DB_PREFIX . "filter_description fd ON fp.filter_id = fd.filter_id
            WHERE fp.filter_parent_id IN (" . $generations . ");
        ")->rows;


        $result = [];
        foreach ($filters as $key =>  $filter) {
            if (array_key_exists($filter['name'], $result)) {
                $result[$filter['name']]['count_product'] += $filter['product_count'];
                $result[$filter['name']]['filter_id'] .= ',' .$filter['filter_id'];
                $result[$filter['name']]['filter_group_id'] = $filter['filter_group_id'];
                $result[$filter['name']]['name'] = $filter['name'];
            } else {
                $result[$filter['name']]['count_product'] = $filter['product_count'];
                $result[$filter['name']]['filter_id'] = $filter['filter_id'];
                $result[$filter['name']]['filter_group_id'] = $filter['filter_group_id'];
                $result[$filter['name']]['name'] = $filter['name'];
            }
        }

        return $result;
    }

    /**
     * Get string filters from parent filters in DB.
     *
     * @param $parentsId
     * @return string
     */
    private function getStrFilters($parentsId) {
        $query = $this->db->query("
            SELECT *
            FROM " . DB_PREFIX . "filter_parent fp
            WHERE fp.filter_parent_id IN (" . $parentsId . ")
        ")->rows;
        $filtersId = implode(', ', array_column($query, 'filter_id'));

        return $filtersId;
    }

    public function getFilterIdFromUrl($urlAlias)
    {
        $sql = "
            SELECT ua.query, ua.keyword, fd.filter_id, fd.name, fgd.filter_group_id, fgd.name as group_name
            FROM " . DB_PREFIX . "url_alias ua
            LEFT JOIN " . DB_PREFIX . "filter_description fd ON ua.url_alias_id = fd.url_alias_id
            LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON fd.filter_group_id = fgd.filter_group_id
            WHERE ua.keyword = '" . $urlAlias . "'
        ";
        $result = $this->db->query($sql)->row;
        return $result;
    }

    public function getChildFilterForCategories($parentFilterId)
    {
        $sql = "
            SELECT fd.name, fd.filter_id, fp.filter_parent_id, fd.filter_group_id, ua.query, ua.keyword,
                   (
                       SELECT count(product_id)
                       FROM " . DB_PREFIX . "product_filter
                       WHERE filter_id = fp.filter_id
                   ) AS product_count
            FROM " . DB_PREFIX . "filter_parent fp
                   LEFT JOIN " . DB_PREFIX . "filter_description fd ON fp.filter_id = fd.filter_id
                   LEFT JOIN " . DB_PREFIX . "url_alias ua ON fd.url_alias_id = ua.url_alias_id
            WHERE fp.filter_parent_id IN (" . $parentFilterId . ")
        ";
        $result = $this->db->query($sql)->rows;
        return $result;
    }

    public function getExtendedFIltersTitle()
    {
        $sql = "
            SELECT *
            FROM " . DB_PREFIX . "filter_group_description fgd
            WHERE fgd.name NOT IN ('Марка', 'Модель', 'Модификация', 'нет модификации')
        ";
        $result = $this->db->query($sql)->rows;

        return $result;
    }

    public function getFiltersForCategories($filterId)
    {
        $sql = "
            SELECT fd.filter_id, fd.name, ua.query, ua.keyword, fgd.name AS group_name
            FROM " . DB_PREFIX . "filter_parent fp
            LEFT JOIN " . DB_PREFIX . "filter_description fd ON fp.filter_id = fd.filter_id
            LEFT JOIN oc_url_alias ua ON fd.url_alias_id = ua.url_alias_id
            LEFT JOIN oc_filter_group_description fgd ON fd.filter_group_id = fgd.filter_group_id 
            WHERE fp.filter_parent_id = " . $filterId . "
        ";
        return $this->db->query($sql)->rows;
    }

    /**
     * Count all products for block filter in categories.
     *
     * @return mixed
     */
    public function countAllProducts()
    {
        $sql = "
            SELECT count(p.product_id) AS count_all_products
            FROM " . DB_PREFIX . "product p
        ";
        return $this->db->query($sql)->row;
    }

    /**
     * Get count product in Categories.
     *
     * @param $categoriesId
     * @return mixed
     */
    public function getCountProducts($categoriesId)
    {
        $sql = "
            SELECT count(ptc.product_id) AS count_products
            FROM " . DB_PREFIX . "product_to_category ptc
            WHERE ptc.category_id IN ($categoriesId);
        ";
        $count = $this->db->query($sql)->row;
        return $count;
    }

    public function getCategoriesPath($categoriesId)
    {
        $sql = "
            SELECT ua.keyword
            FROM " . DB_PREFIX . "url_alias ua
            WHERE ua.query IN (" . $categoriesId . ")
        ";
        $path = $this->db->query($sql)->rows;
        return $path;
    }

    public function countFilters($filters)
    {
        $count = [];
        foreach ($filters as $filter) {
            $count[] = $this->db->query("
                SELECT filter_id, count(product_id) AS count
                FROM " . DB_PREFIX . "product_filter
                WHERE filter_id = " . $filter['filter_id'] . "
            ")->row;
        }
        return $count;
    }

    public function getAllGroupFilters()
    {
        return $groupFilters = $this->db->query("
            SELECT fgd.filter_group_id, fgd.name
            FROM " . DB_PREFIX . "filter_group_description fgd
            WHERE fgd.name NOT IN ('Модель', 'Марка', 'Модификация', 'нет модификации')
        ")->rows;
    }

    public function getAllChildFilters()
    {
        return $childFilters = $this->db->query("
            SELECT fd.filter_id, fd.filter_group_id, fd.name, ua.keyword, ua.query
            FROM " . DB_PREFIX . "filter_description fd
            LEFT JOIN " . DB_PREFIX . "url_alias ua ON fd.url_alias_id = ua.url_alias_id
        ")->rows;
    }

    public function getAllFilter($filter_group_id)
    {
        $query = $this->db->query("SELECT fd.filter_id AS filter_id, name AS name FROM " . DB_PREFIX . "filter fl, " . DB_PREFIX . "filter_description fd, " . DB_PREFIX . "filter_group fg WHERE fg.filter_group_id='" . (int)$filter_group_id . "' AND fl.filter_group_id=fg.filter_group_id AND fg.filter_group_id=fl.filter_group_id AND fl.filter_id=fd.filter_id AND fd.language_id ='" . (int)$this->config->get('config_language_id') . "' ORDER BY fl.sort_order ASC");
        return $query->rows;
    }

    public function getAllFilterGroup()
    {
        $query = $this->db->query("SELECT fgd.filter_group_id AS filter_group_id, fgd.name AS name  FROM " . DB_PREFIX . "filter_group fg, " . DB_PREFIX . "filter_group_description fgd WHERE fgd.language_id ='" . (int)$this->config->get('config_language_id') . "' AND fgd.filter_group_id=fg.filter_group_id ORDER BY fg.sort_order");
        return $query->rows;
    }

    public function getFilterGroup($filter_main)
    {
        $query = $this->db->query("SELECT fgd.filter_group_id AS filter_group_id, fgd.name AS name  FROM " . DB_PREFIX . "filter_group fg, " . DB_PREFIX . "filter_group_description fgd WHERE fgd.name = '" . $filter_main . "' AND fgd.language_id ='" . (int)$this->config->get('config_language_id') . "' AND fgd.filter_group_id=fg.filter_group_id ORDER BY fg.sort_order");
        return $query->row;
    }

    public function getPoductFilter($brand_id)
    {
        $query = $this->db->query("SELECT product_id AS product_id FROM " . DB_PREFIX . "product_filter WHERE filter_id = '" . (int)$brand_id . "'");
        return $query->rows;
    }

    public function getModels($product_id, $filters_model)
    {
        $query = $this->db->query("SELECT fd.name AS name, fd.filter_id AS filter_id FROM " . DB_PREFIX . "filter_description fd, " . DB_PREFIX . "filter_group_description fgd, " . DB_PREFIX . "product_filter pf WHERE pf.product_id = '" . (int)$product_id . "' AND fgd.name = '" . $filters_model . "' AND fgd.filter_group_id = fd.filter_group_id AND pf.filter_id = fd.filter_id GROUP BY fd.name");
        return $query->row;
    }

    public function getFilter($filter_id)
    {
        $query = $this->db->query("SELECT name AS name, filter_id AS filter_id FROM " . DB_PREFIX . "filter_description WHERE filter_id = '" . (int)$filter_id . "' ");
        return $query->row;
    }

    public function getCategorysToFilter($filterData)
    {
        $sql = "
            SELECT *
            FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
            LEFT JOIN " . DB_PREFIX . "category_filter cf ON (c.category_id = cf.category_id)
            LEFT JOIN " . DB_PREFIX . "url_alias ua ON (concat('category_id=', c.category_id) = ua.query)
            WHERE cf.filter_id = '" . (int)$filterData['filter_id'] . "'
                AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                AND cd.name LIKE '%" . $filterData['filter_name'] . "%'
                AND c.status = '1'
            GROUP BY c.category_id ORDER BY c.sort_order, LCASE(cd.name)
        ";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getAllCategories($path = null)
    {
        $sql = "
            SELECT cd.category_id, cd.name, ua.keyword, c.parent_id
            FROM " . DB_PREFIX . "category_description cd
            LEFT JOIN " . DB_PREFIX . "url_alias ua ON concat('category_id=', cd.category_id) = ua.query
            LEFT JOIN " . DB_PREFIX . "category c ON cd.category_id = c.category_id
        ";
        $results = $this->db->query($sql)->rows;

        $categories = [];
        foreach ($results as $result) {
            $categories[] = [
                'category_id' => $result['category_id'],
                'name' => $result['name'],
                'keyword' => $result['keyword'],
                'href' => ($result['parent_id'] == 0) ? $result['keyword'] : $this->getFullPathCategory($result['parent_id'] . $path) . $result['keyword']
            ];
        }
        return $categories;
    }

    public function getFullPathCategory($id, $path = null)
    {
        $sql = "
            SELECT c.category_id, c.parent_id, ua.keyword
            FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "url_alias ua ON concat('category_id=', c.category_id) = ua.query
            WHERE c.category_id = " . $id . "
        ";

        $result = $this->db->query($sql)->row;

        $fullPath = '';
        $fullPath .= $result['keyword'] . '/' . $path;

        if ($result['parent_id'] == 0) {
            return $fullPath;
        } else {
            return $this->getFullPathCategory($result['parent_id'], $fullPath);
        }
    }

}