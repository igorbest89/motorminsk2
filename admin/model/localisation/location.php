<?php
class ModelLocalisationLocation extends Model {
	public function addLocation($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "location SET name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "', geocode = '" . $this->db->escape($data['geocode']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', image = '" . $this->db->escape($data['image']) . "', open = '" . $this->db->escape($data['open']) . "', email = '" . $this->db->escape($data['email']) . "', comment = '" . $this->db->escape($data['comment']) . "', store_group_id = '" . $this->db->escape($data['store_group_id']) ."'");
	
		return $this->db->getLastId();
	}

	public function editLocation($location_id, $data) {

        $this->db->query("UPDATE " . DB_PREFIX . "location SET name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "', geocode = '" . $this->db->escape($data['geocode']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', image = '" . $this->db->escape($data['image']) . "', open = '" . $this->db->escape($data['open']) . "', email = '" . $this->db->escape($data['email']) . "', comment = '" . $this->db->escape($data['comment']) . "', store_group_id = '" . $this->db->escape($data['store_group_id']) . "', settings = '" . $this->db->escape(json_encode($data['settings'])) . "'  WHERE location_id = '" . (int)$location_id . "'");
	}

	public function deleteLocation($location_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "location WHERE location_id = " . (int)$location_id);
	}

	public function getLocation($location_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

		return $query->row;
	}

	public function getLocations($data = array()) {
		$sql = "SELECT location_id, name, address, store_group_id FROM " . DB_PREFIX . "location";

		$sort_data = array(
			'name',
			'address',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalLocations() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "location");

		return $query->row['total'];
	}

	public function getStoreGroups($data = array()) {

            $sql = "SELECT * FROM " . DB_PREFIX . "store_groups WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sql .= " ORDER BY name";

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;

    }
    public function getTotalStoreGroups()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store_groups WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }

    public function getStoreGroupDescriptions($store_group_id) {
        $store_group_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_groups WHERE store_group_id = '" . (int)$store_group_id . "'");

        foreach ($query->rows as $result) {
            $store_group_data[$result['language_id']] = array('name' => $result['name'], 'order_id' => $result['order_id']);
        }

        return $store_group_data;
    }

    public function addStoreGroup($data){

        foreach ($data['store_group'] as $language_id => $value) {
            if (isset($store_group_id)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "store_groups SET store_group_id = '" . (int)$store_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', order_id = '" . $this->db->escape($value['order_id']) . "'");
            } else {
                $this->db->query("INSERT INTO " . DB_PREFIX . "store_groups SET language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', order_id = '" . $this->db->escape($value['order_id']) . "'");

                $store_group_id = $this->db->getLastId();
            }
        }

        $this->cache->delete('store_group');

        return $store_group_id;
    }
    public function editStoreGroup($store_group_id, $data) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "store_groups WHERE store_group_id = '" . (int)$store_group_id . "'");

        foreach ($data['store_group'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "store_groups SET store_group_id = '" . (int)$store_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', order_id = '" . $this->db->escape($value['order_id']) . "'");
        }

        $this->cache->delete('store_group');
    }

    public function getTotalLocationsByStoreGroupId($store_group_id){
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "location WHERE store_group_id = '" . (int)$store_group_id . "'");

        return $query->row['total'];
    }
    public function deleteStoreGroup($store_group_id){
        $this->db->query("DELETE FROM " . DB_PREFIX . "store_groups WHERE store_group_id = '" . (int)$store_group_id . "'");

        $this->cache->delete('store_group');
    }

    public function getStoreGroupName($store_group_id) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_groups WHERE store_group_id = '" . (int)$store_group_id . "'");
        $store_group = $query->row;
        return $store_group['name'];
    }

}
