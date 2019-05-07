<?php
class ModelLocalisationLocation extends Model {
	public function getLocation($location_id) {
		$query = $this->db->query("SELECT location_id, name, address, geocode, telephone, fax, email, image, open, comment, store_group_id, settings FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

		return $query->row;
	}

    public function getStoreGroups() {

        $sql = "SELECT * FROM " . DB_PREFIX . "store_groups WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY order_id ASC";


        $query = $this->db->query($sql);

        return $query->rows;

    }

    public function getLocationsByStoreGroupId($store_group_id){
        $sql = "SELECT location_id, name, address, geocode, telephone, fax, image, open, email, comment, store_group_id, settings FROM " . DB_PREFIX . "location WHERE store_group_id = '" . (int)$store_group_id . "'";

        $query = $this->db->query($sql);

        return $query->rows;

    }
}