<?php

class ModelExtensionModuleSiteMapGenerate extends Model
{

    /**
     * Count all categories where status = 1.
     *
     * @return mixed
     */
    public function countCategories()
    {
        $count = $this->db->query("
			SELECT count(category_id) AS count
			FROM " . DB_PREFIX . "category
			WHERE status = 1
		")->row['count'];

        return $count;
    }

    /**
     * Get categories from start to end.
     *
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getCategories($limit, $offset)
    {
        $categories = $this->db->query("
			SELECT *
			FROM " . DB_PREFIX . "category
			WHERE status = 1
			LIMIT " . (int)$limit . " OFFSET " . (int)$offset . ";
		")->rows;

        return $categories;
    }

    /**
     * Get category URL by category_id.
     *
     * @param $categoryId
     * @return mixed
     */
    public function getCategoryAlias($categoryId)
    {
        $alias = $this->db->query("
			SELECT *
			FROM " . DB_PREFIX . "url_alias
			WHERE query = 'category_id=" . (int)$categoryId . "'
		")->row['keyword'];

        return $alias;
    }

    /**
     * Get category_id && parent_id from database.
     *
     * @param $categoryId
     * @return mixed
     */
    public function getCategoryInfo($categoryId)
    {
        $category = $this->db->query("
			SELECT category_id, parent_id
			FROM " . DB_PREFIX . "category
			WHERE category_id = " . (int)$categoryId . "
		")->row;

        return $category;
    }

    /**
     * Count all products with enabled status.
     *
     * @return mixed
     */
    public function countProducts()
    {
        $count = $this->db->query("
			SELECT count(product_id) AS count
			FROM " . DB_PREFIX . "product
			WHERE status = 1
		")->row['count'];

        return $count;
    }

    /**
     * Get all products with status enable.
     *
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getProducts($limit, $offset)
    {
        $products = $this->db->query("
			SELECT p.product_id, ptc.category_id
			FROM " . DB_PREFIX . "product p
			LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id
			WHERE status = 1
			LIMIT " . (int)$limit . " OFFSET " . (int)$offset . "
		")->rows;

        return $products;
    }

    /**
     * Get current url alias for product.
     *
     * @param $productId
     * @return mixed
     */
    public function getProductAlias($productId)
    {
        $alias = $this->db->query("
			SELECT *
			FROM " .  DB_PREFIX . "url_alias
			WHERE query = 'product_id=" . (int)$productId . "'
		")->row['keyword'];

        return $alias;
    }
}