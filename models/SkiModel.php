<?php

class SkiModel {
	protected $db;
	
	public function __construct() {
		$this->db = require('db/pdo_connection.php');
    }

	/**
	 * Returns a collection containing all the resources in the database
	 * @return array an array of resources
	 */
	function getAllSkiTypes(): array {
		$query = '
                SELECT * 
                FROM ski_type
        ';

		$res = array();

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return $res;
	}
	
	/**
	 * Returns a collection containing all the resources in the database
	 * based on the supplied id. The array will be empty
	 * if there are no matching resources
	 * @param int $id the id to filter by
	 * @return array an array of resources
	 */
	function getSkiTypeById(int $id): array {
		$query = '
                SELECT * 
                FROM ski_type 
                WHERE id = :id';

        $res = array();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return $res;
	}
	
	/**
	 * Returns a collection containing all the resources in the database
	 * based on the supplied model. The array will be empty
	 * if there are no matching resources
	 * @param string $model the model to filter by
	 * @return array an array of resources
	 */
	function getSkiTypeByModel(string $model): array {
		$query = 'SELECT * FROM ski_type WHERE model = :model';

        $res = array();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':model', $model);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return $res;
	}
	
	/**
	 * Returns a collection containing all the resources in the database
	 * based on the supplied grip system. The array will be empty
	 * if there are no matching resources
	 * @param string $grip_system the grip_system to filter by
	 * @return array an array of resources
	 */
	function getSkiTypeByGrip(string $grip_system): array {
		$query = 'SELECT * FROM ski_type WHERE grip_system = :grip_system';

        $res = array();

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':grip_system', $grip_system);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return $res;
	}
}