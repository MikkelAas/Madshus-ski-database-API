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
      SELECT `id`, `model`, `type`, `temperature`, 
             `grip_system`, `size`, `weight_class`, 
             `description`, `historical`, `url`, `msrp` 
      FROM ski_type
    ';

    $stmt = $this->db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();
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
      SELECT `id`, `model`, `type`, `temperature`, 
             `grip_system`, `size`, `weight_class`, 
             `description`, `historical`, `url`, `msrp` 
      FROM ski_type 
      WHERE id = :id
    ';

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  /**
   * Returns a collection containing all the resources in the database
   * based on the supplied model. The array will be empty
   * if there are no matching resources
   * @param string $model the model to filter by
   * @return array an array of resources
   */
  function getSkiTypeByModel(string $model): array {
    $query = '
      SELECT `id`, `model`, `type`, `temperature`, 
             `grip_system`, `size`, `weight_class`, 
             `description`, `historical`, `url`, `msrp`
      FROM ski_type 
      WHERE model = :model
    ';

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':model', $model);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  /**
   * Returns a collection containing all the resources in the database
   * based on the supplied grip system. The array will be empty
   * if there are no matching resources
   * @param string $grip_system the grip_system to filter by
   * @return array an array of resources
   */
  function getSkiTypeByGrip(string $grip_system): array {
    $query = '
      SELECT `id`, `model`, `type`, `temperature`, 
             `grip_system`, `size`, `weight_class`, 
             `description`, `historical`, `url`, `msrp`
      FROM ski_type 
      WHERE grip_system = :grip_system
    ';

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':grip_system', $grip_system);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  /**
   * Will create a record of a produced ski with no order connected to it
   * @param int $skiType
   * @param string $productionDate
   */
  function addProducedSki(int $skiType, string $productionDate) {
    $date = DateTime::createFromFormat('Y-m-d', $productionDate);
    if (!$date || $date->format('Y-m-d') != $productionDate) {
      throw new InvalidArgumentException("Invalid date.");
    }

    $query = '
        INSERT INTO `produced_skis` (`prod_num`, `prod_date`, `ski_type`, `order_id`) 
        VALUES (NULL, :prodDate, :skiType, NULL) 
    ';

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':prodDate', $productionDate);
    $stmt->bindValue(':skiType', $skiType);
    $stmt->execute();
  }
}
