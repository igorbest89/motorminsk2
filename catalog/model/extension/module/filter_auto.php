<?php

class ModelExtensionModuleFilterAuto extends Model {
	
	  public function getBrands()
    {
        $sql = "SELECT DISTINCT ab.brand_id, ab.brand_name 
                FROM " . DB_PREFIX . "auto_brands ab 
                JOIN " . DB_PREFIX . "auto_models am ON (am.brand_id = ab.brand_id) AND am.active = 1
                JOIN " . DB_PREFIX . "auto_modifications amf ON (amf.model_id = am.model_id) AND amf.active = 1
                JOIN " . DB_PREFIX . "auto_link_part alp ON alp.modif_id = amf.modif_id
                JOIN " . DB_PREFIX . "product_to_category ptc ON ptc.product_id = alp.part_id
                WHERE ab.active = 1
                ORDER BY ab.brand_name";

        $query = $this->db->query($sql);

        return $query->rows;
    }
	
/*
    public function getBrands($category_id)
    {
        $sql = "SELECT DISTINCT ab.brand_id, ab.brand_name 
                FROM " . DB_PREFIX . "auto_brands ab 
                JOIN " . DB_PREFIX . "auto_models am ON (am.brand_id = ab.brand_id) AND am.active = 1
                JOIN " . DB_PREFIX . "auto_modifications amf ON (amf.model_id = am.model_id) AND amf.active = 1
                JOIN " . DB_PREFIX . "auto_link_part alp ON alp.modif_id = amf.modif_id
                JOIN " . DB_PREFIX . "product_to_category ptc ON ptc.product_id = alp.part_id
                WHERE ab.active = 1 AND ptc.category_id = " . (int) $category_id . " 
                ORDER BY ab.brand_name";

        $query = $this->db->query($sql);

        return $query->rows;
    }
*/
    public function getModels($brand_id)
    {
        $sql = "SELECT DISTINCT am.model_id, am.model_name 
                FROM " . DB_PREFIX . "auto_models am
                JOIN " . DB_PREFIX . "auto_modifications amf ON (amf.model_id = am.model_id) AND amf.active = 1
                JOIN " . DB_PREFIX . "auto_link_part alp ON alp.modif_id = amf.modif_id
                JOIN " . DB_PREFIX . "product_to_category ptc ON ptc.product_id = alp.part_id
                WHERE am.active = 1 AND
                    am.brand_id = " . (int) $brand_id . "
                ORDER BY am.model_name";

        $query = $this->db->query($sql);

        return $query->rows;
    }
	
	/*    public function getModels($category_id, $brand_id)
    {
        $sql = "SELECT DISTINCT am.model_id, am.model_name 
                FROM " . DB_PREFIX . "auto_models am
                JOIN " . DB_PREFIX . "auto_modifications amf ON (amf.model_id = am.model_id) AND amf.active = 1
                JOIN " . DB_PREFIX . "auto_link_part alp ON alp.modif_id = amf.modif_id
                JOIN " . DB_PREFIX . "product_to_category ptc ON ptc.product_id = alp.part_id
                WHERE am.active = 1 AND 
                    ptc.category_id = " . (int) $category_id . " AND
                    am.brand_id = " . (int) $brand_id . "
                ORDER BY am.model_name";

        $query = $this->db->query($sql);

        return $query->rows;
    }
*/
    public function getModifies($model_id)
    {
        $sql = "SELECT DISTINCT amf.*  
                FROM " . DB_PREFIX . "auto_modifications amf
                JOIN " . DB_PREFIX . "auto_link_part alp ON alp.modif_id = amf.modif_id
                JOIN " . DB_PREFIX . "product_to_category ptc ON ptc.product_id = alp.part_id
                WHERE amf.active = 1 AND
                    amf.model_id = " . (int) $model_id . "
                ORDER BY amf.modif_name";

        $query = $this->db->query($sql);

        return $query->rows;
    }
	
	/*
	 public function getModifies($category_id, $model_id)
    {
        $sql = "SELECT DISTINCT amf.*  
                FROM " . DB_PREFIX . "auto_modifications amf
                JOIN " . DB_PREFIX . "auto_link_part alp ON alp.modif_id = amf.modif_id
                JOIN " . DB_PREFIX . "product_to_category ptc ON ptc.product_id = alp.part_id
                WHERE amf.active = 1 AND 
                    ptc.category_id = " . (int) $category_id . " AND
                    amf.model_id = " . (int) $model_id . "
                ORDER BY amf.modif_name";

        $query = $this->db->query($sql);

        return $query->rows;
    }
	*/

    public function getModifyInfo($modify_id)
    {
        $sql = "SELECT amf.*, am.*, ab.*
                FROM " . DB_PREFIX . "auto_modifications amf 
                LEFT JOIN " . DB_PREFIX . "auto_models am ON am.model_id = amf.model_id 
                LEFT JOIN " . DB_PREFIX . "auto_brands ab ON ab.brand_id = am.brand_id 
                WHERE amf.modif_id = " . (int) $modify_id;

        $query = $this->db->query($sql);

        return $query->row;
    }
}