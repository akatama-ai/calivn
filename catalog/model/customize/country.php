<?php
class ModelCustomizeCountry extends Model {
	// public function getCountry() {
	// 	$query = $this->db->query("
	// 		SELECT country_id AS id, name 
	// 		FROM " . DB_PREFIX . "country 
	// 		WHERE status = 1 and country_id IN (36,230,116,129,188,209,223)
	// 		");
	// 	return $query->rows;
	// }
	// public function getProvince() {
	// 	$query = $this->db->query("
	// 		SELECT *
	// 		FROM " . DB_PREFIX . "provinces 
			
	// 		");
	// 	return $query->rows;
	// }

	public function getCountry() {
		$query = $this->db->query("SELECT country_id AS id, name FROM " . DB_PREFIX . "country WHERE status = '1'");

		return $query->rows;
	}
	public function getCountrys($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");

		return $query->row;
	}

	public function getCountries() {
		$country_data = $this->cache->get('country.status');

		if (!$country_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' ORDER BY name ASC");

			$country_data = $query->rows;

			$this->cache->set('country.status', $country_data);
		}

		return $country_data;
	}
	public function getProvince() {
	
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE status = '1' ORDER BY name ASC");

			$country_data = $query->rows;

			

		return $country_data;
	}
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row;
	}

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

			$zone_data = $query->rows;

			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}
}